<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Registration;
use App\Models\SupplementaryCycle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        SupplementaryCycle::create([
            'name' => 'Student Portal Test Cycle',
            'registration_starts_at' => now()->subDay(),
            'registration_ends_at' => now()->addDay(),
            'student_access_ends_at' => now()->addMonth(),
            'is_current' => true,
        ]);
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sign in to your account');
    }

    public function test_student_can_login_and_reach_dashboard(): void
    {
        $student = User::where('email', 'student1@campus.edu')->first();

        $response = $this->post('/login', [
            'login_identifier' => 'student1@campus.edu',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticatedAs($student);
    }

    public function test_student_can_view_courses_catalog(): void
    {
        $student = User::where('email', 'student1@campus.edu')->first();

        $response = $this->actingAs($student)->get(route('student.courses'));

        $response->assertStatus(200);
        $response->assertSee('Available Resit Courses');
        $response->assertSee('CS101');
    }

    public function test_student_can_register_for_course_and_prevent_duplicate(): void
    {
        $student = User::where('email', 'student1@campus.edu')->first();
        $course = Course::where('course_code', 'CS101')->first();

        // 1. Submit Registration
        $response = $this->actingAs($student)->post(route('student.storeRegistration'), [
            'course_id' => $course->id,
            'academic_year' => '2026/2027',
            'semester' => 'Semester 1',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertDatabaseHas('registrations', [
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => Registration::STATUS_PENDING_PAYMENT,
        ]);

        // 2. Prevent Duplicate Registration
        $duplicateResponse = $this->actingAs($student)->post(route('student.storeRegistration'), [
            'course_id' => $course->id,
            'academic_year' => '2026/2027',
            'semester' => 'Semester 1',
        ]);

        $duplicateResponse->assertRedirect(route('student.dashboard'));
        $duplicateResponse->assertSessionHas('error');
    }

    public function test_student_can_pay_for_registration(): void
    {
        $student = User::where('email', 'student1@campus.edu')->first();
        $course = Course::where('course_code', 'CS204')->first();

        // Create a pending registration
        $registration = Registration::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'academic_year' => '2026/2027',
            'semester' => 'Semester 1',
            'status' => Registration::STATUS_PENDING_PAYMENT,
        ]);

        $response = $this->actingAs($student)->post(route('student.pay', $registration->id), [
            'phone_number' => '0244123456',
            'network' => 'MTN',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('registrations', [
            'id' => $registration->id,
            'status' => Registration::STATUS_PAID,
        ]);

        $this->assertDatabaseHas('payments', [
            'registration_id' => $registration->id,
            'user_id' => $student->id,
            'status' => 'successful',
        ]);
    }

    public function test_student_can_view_exam_slip(): void
    {
        $student = User::where('email', 'student1@campus.edu')->first();
        $course = Course::where('course_code', 'CS305')->first();

        $registration = Registration::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'academic_year' => '2026/2027',
            'semester' => 'Semester 1',
            'status' => Registration::STATUS_APPROVED,
        ]);

        $response = $this->actingAs($student)->get(route('student.examSlip', $registration->id));

        $response->assertStatus(200);
        $response->assertSee('RESIT EXAMINATION ADMISSION SLIP');
        $response->assertSee($student->full_name);
    }
}
