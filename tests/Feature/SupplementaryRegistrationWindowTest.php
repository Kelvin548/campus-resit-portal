<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Registration;
use App\Models\SupplementaryCycle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SupplementaryRegistrationWindowTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_registration_starts_at_the_global_opening_time_for_any_active_course(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-12-01 00:00:00', 'Africa/Accra'));
        $this->createCycle('2026-12-01 00:00:00', '2027-01-01 00:00:00', '2027-03-01 00:00:00');
        $course = $this->createCourse('CS901', '2026-12-15 00:00:00', '2026-12-20 00:00:00');

        $this->assertTrue(SupplementaryCycle::registrationIsOpenNow());
        $this->assertTrue(SupplementaryCycle::studentPortalIsOpenNow());

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Supplementary registration is open');

        $response = $this->post('/register', [
            'full_name' => 'Test Student',
            'email' => 'start-time@example.com',
            'student_id_number' => '1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertDatabaseHas('users', ['email' => 'start-time@example.com']);

        $student = User::where('email', 'start-time@example.com')->firstOrFail();
        $courseResponse = $this->actingAs($student)->post(route('student.storeRegistration'), [
            'course_id' => $course->id,
            'semester' => 'Semester 1',
            'academic_year' => '2026/2027',
        ]);

        $courseResponse->assertRedirect(route('student.dashboard'));
        $this->assertDatabaseHas('registrations', [
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => Registration::STATUS_PENDING_PAYMENT,
        ]);
    }

    public function test_at_registration_close_new_signups_and_applications_stop_but_exam_access_remains(): void
    {
        Carbon::setTestNow(Carbon::parse('2027-01-01 00:00:00', 'Africa/Accra'));
        $this->createCycle('2026-12-01 00:00:00', '2027-01-01 00:00:00', '2027-03-01 00:00:00');
        $course = $this->createCourse('CS902');
        $student = $this->createStudent('exam-phase@example.com', '1234567891');

        $this->assertFalse(SupplementaryCycle::registrationIsOpenNow());
        $this->assertTrue(SupplementaryCycle::studentPortalIsOpenNow());

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Supplementary registration is closed')
            ->assertSee('view your examination information');

        $accountResponse = $this->post('/register', [
            'full_name' => 'Late Student',
            'email' => 'late@example.com',
            'student_id_number' => '1234567892',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $accountResponse->assertRedirect(route('login'));
        $this->assertDatabaseMissing('users', ['email' => 'late@example.com']);

        $applicationResponse = $this->actingAs($student)->post(route('student.storeRegistration'), [
            'course_id' => $course->id,
            'semester' => 'Semester 1',
            'academic_year' => '2026/2027',
        ]);

        $applicationResponse->assertSessionHas('error');
        $this->assertDatabaseMissing('registrations', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);

        $this->actingAs($student)->get(route('student.dashboard'))->assertOk();

        $registration = Registration::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'semester' => 'Semester 1',
            'academic_year' => '2026/2027',
            'status' => Registration::STATUS_PENDING_PAYMENT,
        ]);

        $paymentResponse = $this->actingAs($student)->post(route('student.pay', $registration), [
            'phone_number' => '0244123456',
            'network' => 'MTN',
        ]);

        $paymentResponse->assertSessionHas('error');
        $this->assertDatabaseMissing('payments', ['registration_id' => $registration->id]);
    }

    public function test_student_access_closes_at_the_cycle_end_until_a_new_cycle_is_set(): void
    {
        Carbon::setTestNow(Carbon::parse('2027-03-01 00:00:00', 'Africa/Accra'));
        $this->createCycle('2026-12-01 00:00:00', '2027-01-01 00:00:00', '2027-03-01 00:00:00');
        $student = $this->createStudent('closed-cycle@example.com', '1234567893');

        $this->assertFalse(SupplementaryCycle::studentPortalIsOpenNow());

        $response = $this->actingAs($student)->get(route('student.dashboard'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('The supplementary examination cycle has ended')
            ->assertDontSee('Sign In');
    }

    public function test_login_page_reports_a_future_registration_start_and_blocks_student_access(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-30 23:59:00', 'Africa/Accra'));
        $this->createCycle('2026-12-01 00:00:00', '2027-01-01 00:00:00', '2027-03-01 00:00:00');

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Supplementary registration has not started')
            ->assertDontSee('Sign In');

        $response = $this->post('/register', [
            'full_name' => 'Early Student',
            'email' => 'early@example.com',
            'student_id_number' => '1234567894',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('users', ['email' => 'early@example.com']);
    }

    public function test_student_access_is_closed_when_no_cycle_has_been_scheduled(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('The next supplementary cycle has not been scheduled')
            ->assertDontSee('Sign In');

        $response = $this->post('/register', [
            'full_name' => 'Unscheduled Student',
            'email' => 'unscheduled@example.com',
            'student_id_number' => '1234567895',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('users', ['email' => 'unscheduled@example.com']);
    }

    private function createCycle(string $startsAt, string $registrationEndsAt, string $accessEndsAt): SupplementaryCycle
    {
        return SupplementaryCycle::create([
            'name' => 'Test Supplementary Cycle',
            'registration_starts_at' => $startsAt,
            'registration_ends_at' => $registrationEndsAt,
            'student_access_ends_at' => $accessEndsAt,
            'is_current' => true,
        ]);
    }

    private function createCourse(
        string $code,
        ?string $courseStartsAt = null,
        ?string $courseEndsAt = null
    ): Course {
        return Course::create([
            'course_code' => $code,
            'course_name' => 'Test Supplementary Course',
            'credit_hours' => 3,
            'level' => 100,
            'semester' => 'Semester 1',
            'registration_fee' => 100,
            'registration_start' => $courseStartsAt,
            'registration_deadline' => $courseEndsAt,
            'is_active' => true,
        ]);
    }

    private function createStudent(string $email, string $indexNumber): User
    {
        return User::create([
            'full_name' => 'Test Student',
            'email' => $email,
            'student_id_number' => $indexNumber,
            'password' => 'password123',
            'role' => 'student',
        ]);
    }
}
