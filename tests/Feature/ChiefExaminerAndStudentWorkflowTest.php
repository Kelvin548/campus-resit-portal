<?php

namespace Tests\Feature;

use App\Mail\ChiefExaminerBroadcastMail;
use App\Models\BroadcastMessage;
use App\Models\Course;
use App\Models\Registration;
use App\Models\ResitRequest;
use App\Models\SupplementaryCycle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ChiefExaminerAndStudentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $chiefExaminer;
    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->chiefExaminer = User::where('email', 'examiner@campus.edu')->firstOrFail();
        $this->student = User::where('email', 'student1@campus.edu')->firstOrFail();
    }

    public function test_database_seeds_official_usted_ict_education_courses(): void
    {
        $this->assertDatabaseHas('courses', [
            'course_code' => 'ICT112',
            'course_name' => 'Principles of Programming',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('courses', [
            'course_code' => 'ITC231',
            'course_name' => 'Database Concepts and Design',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('courses', [
            'course_code' => 'ICT232',
            'course_name' => 'Web Technology and Design',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('courses', [
            'course_code' => 'ICT244',
            'course_name' => 'Systems Analysis and Design',
            'is_active' => true,
        ]);
    }

    public function test_chief_examiner_can_create_course_without_level_and_semester_and_crud_works(): void
    {
        // 1. Create a new course without specifying level or semester (as level & semester are not required)
        $storeResponse = $this->actingAs($this->chiefExaminer)->post(route('chief-examiner.courses.store'), [
            'course_code' => 'ICT355',
            'course_name' => 'Artificial Intelligence in Education',
            'credit_hours' => 3,
            'registration_fee' => 195.00,
            'is_active' => 1,
        ]);

        $storeResponse->assertSessionHas('success');
        $this->assertDatabaseHas('courses', [
            'course_code' => 'ICT355',
            'course_name' => 'Artificial Intelligence in Education',
        ]);

        $newCourse = Course::where('course_code', 'ICT355')->firstOrFail();

        // 2. Read: appears in Chief Examiner dashboard
        $dashboardResponse = $this->actingAs($this->chiefExaminer)->get(route('chief-examiner.dashboard'));
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee('ICT355');
        $dashboardResponse->assertSee('Artificial Intelligence in Education');

        // And immediately appears in Student Resit Form dropdown list without level/semester restrictions
        $studentFormResponse = $this->actingAs($this->student)->get(route('student.resit-form'));
        $studentFormResponse->assertOk();
        $studentFormResponse->assertSee('ICT355');
        $studentFormResponse->assertSee('Artificial Intelligence in Education');

        // 3. Update course
        $updateResponse = $this->actingAs($this->chiefExaminer)->put(route('chief-examiner.courses.update', $newCourse), [
            'course_code' => 'ICT355',
            'course_name' => 'Artificial Intelligence in Education & Adaptive Learning',
            'credit_hours' => 4,
            'registration_fee' => 210.00,
            'is_active' => 1,
        ]);

        $updateResponse->assertSessionHas('success');
        $this->assertDatabaseHas('courses', [
            'id' => $newCourse->id,
            'course_name' => 'Artificial Intelligence in Education & Adaptive Learning',
            'credit_hours' => 4,
        ]);

        // 4. Delete course
        $deleteResponse = $this->actingAs($this->chiefExaminer)->delete(route('chief-examiner.courses.destroy', $newCourse));
        $deleteResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('courses', [
            'id' => $newCourse->id,
        ]);

        // And immediately disappears from student resit form
        $studentFormAfterDelete = $this->actingAs($this->student)->get(route('student.resit-form'));
        $studentFormAfterDelete->assertDontSee('Artificial Intelligence in Education & Adaptive Learning');
    }

    public function test_chief_examiner_can_broadcast_announcements_and_dispatch_emails(): void
    {
        Mail::fake();

        // 1. Chief Examiner broadcasts a postponement announcement
        $broadcastResponse = $this->actingAs($this->chiefExaminer)->post(route('chief-examiner.broadcasts.store'), [
            'title' => 'Emergency Postponement: ITC231 Database Concepts Examination',
            'category' => 'postponement',
            'message' => 'Please note that the ITC231 supplementary examination scheduled for Thursday has been moved to Friday 10:00 AM in Lab 3.',
            'send_email' => 1,
        ]);

        $broadcastResponse->assertSessionHas('success');
        $this->assertDatabaseHas('broadcast_messages', [
            'title' => 'Emergency Postponement: ITC231 Database Concepts Examination',
            'category' => 'postponement',
        ]);

        // Verify emails were sent to students
        Mail::assertSent(ChiefExaminerBroadcastMail::class);

        $broadcast = BroadcastMessage::where('title', 'Emergency Postponement: ITC231 Database Concepts Examination')->firstOrFail();

        // 2. Chief Examiner accesses the dedicated broadcasts page from the navbar
        $navbarPageResponse = $this->actingAs($this->chiefExaminer)->get(route('chief-examiner.broadcasts.index'));
        $navbarPageResponse->assertOk();
        $navbarPageResponse->assertSee('Student Broadcast System');
        $navbarPageResponse->assertSee('Emergency Postponement: ITC231 Database Concepts Examination');

        // 3. Student visits dashboard and sees the broadcast notice
        $dashboardResponse = $this->actingAs($this->student)->get(route('student.dashboard'));
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee('Emergency Postponement: ITC231 Database Concepts Examination');
        $dashboardResponse->assertSee('Official Chief Examiner Announcement');

        // 3. Student visits dedicated notices page
        $announcementsResponse = $this->actingAs($this->student)->get(route('student.announcements'));
        $announcementsResponse->assertOk();
        $announcementsResponse->assertSee('Emergency Postponement: ITC231 Database Concepts Examination');
        $announcementsResponse->assertSee('Friday 10:00 AM in Lab 3');

        // 4. Chief Examiner can remove the broadcast notice
        $deleteResponse = $this->actingAs($this->chiefExaminer)->delete(route('chief-examiner.broadcasts.destroy', $broadcast));
        $deleteResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('broadcast_messages', ['id' => $broadcast->id]);
    }

    public function test_global_registration_toggle_intercepts_student_applications_when_closed(): void
    {
        // 1. Chief Examiner toggles applications to Unavailable (Closed)
        $toggleResponse = $this->actingAs($this->chiefExaminer)->post(route('chief-examiner.toggle-applications'), [
            'target_state' => 'closed',
        ]);
        $toggleResponse->assertSessionHas('success');
        $this->assertFalse(SupplementaryCycle::registrationIsOpenNow());

        // 2. Student attempts to access the resit form -> Intercepted
        $formResponse = $this->actingAs($this->student)->get(route('student.resit-form'));
        $formResponse->assertRedirect(route('student.dashboard'));
        $formResponse->assertSessionHas('error', 'Resit applications are currently closed. Please check back later.');

        // 3. Student attempts to access the registration hub -> Intercepted
        $hubResponse = $this->actingAs($this->student)->get(route('student.registration'));
        $hubResponse->assertRedirect(route('student.dashboard'));
        $hubResponse->assertSessionHas('error', 'Resit applications are currently closed. Please check back later.');

        // 4. Student attempts to submit resit form -> Intercepted
        $course = Course::where('course_code', 'ICT112')->firstOrFail();
        $submitResponse = $this->actingAs($this->student)->post(route('student.resit-form.submit'), [
            'name' => 'Kelvyn Kofi Doe',
            'department' => 'Department of ICT Education',
            'index_number' => '2026000001',
            'level' => '200',
            'programme' => 'B.Sc. Information Technology Education',
            'year_entry' => 2024,
            'year_completion' => 2028,
            'telephone' => '0244123456',
            'exam_type' => 'resit',
            'course_ids' => [$course->id],
        ]);
        $submitResponse->assertRedirect(route('student.dashboard'));
        $submitResponse->assertSessionHas('error', 'Resit applications are currently closed. Please check back later.');

        // 5. Chief Examiner toggles applications back to Available (Open)
        $reopenResponse = $this->actingAs($this->chiefExaminer)->post(route('chief-examiner.toggle-applications'), [
            'target_state' => 'open',
        ]);
        $reopenResponse->assertSessionHas('success');
        $this->assertTrue(SupplementaryCycle::registrationIsOpenNow());

        // 6. Now student can access form normally
        $allowedFormResponse = $this->actingAs($this->student)->get(route('student.resit-form'));
        $allowedFormResponse->assertOk();
        $allowedFormResponse->assertSee('Resit Examination Application');
    }

    public function test_student_resit_registration_dropdown_selection_momo_payment_and_chief_examiner_review_queue(): void
    {
        $course = Course::where('course_code', 'ITC231')->firstOrFail();

        // 1. Student submits Resit Form with dropdown selected course
        $submitResponse = $this->actingAs($this->student)->post(route('student.resit-form.submit'), [
            'name' => 'Kelvyn Kofi Doe',
            'department' => 'Department of ICT Education',
            'index_number' => '2026000001',
            'level' => '200',
            'programme' => 'B.Sc. Information Technology Education',
            'year_entry' => 2024,
            'year_completion' => 2028,
            'telephone' => '0244123456',
            'exam_type' => 'resit',
            'course_ids' => [$course->id],
        ]);

        $submitResponse->assertRedirect(route('student.dashboard'));
        $this->assertDatabaseHas('registrations', [
            'user_id' => $this->student->id,
            'course_id' => $course->id,
            'status' => Registration::STATUS_PENDING_PAYMENT,
        ]);

        $registration = Registration::where('user_id', $this->student->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        // 2. Student completes Mobile Money payment
        $paymentResponse = $this->actingAs($this->student)->post(route('student.pay', $registration), [
            'phone_number' => '0244123456',
            'network' => 'MTN',
        ]);

        $paymentResponse->assertRedirect(route('student.dashboard'));
        $this->assertDatabaseHas('registrations', [
            'id' => $registration->id,
            'status' => Registration::STATUS_PAID,
        ]);

        // 3. Chief Examiner Review Queue contains the student registration
        $examinerDashboard = $this->actingAs($this->chiefExaminer)->get(route('chief-examiner.dashboard'));
        $examinerDashboard->assertOk();
        $examinerDashboard->assertSee($this->student->full_name);
        $examinerDashboard->assertSee('ITC231');
        $examinerDashboard->assertSee('Approve Registration');

        // 4. Chief Examiner approves registration
        $approveResponse = $this->actingAs($this->chiefExaminer)->post(route('chief-examiner.registrations.approve', $registration));
        $approveResponse->assertSessionHas('success');

        $this->assertDatabaseHas('registrations', [
            'id' => $registration->id,
            'status' => Registration::STATUS_APPROVED,
        ]);

        // 5. Student portal now reflects Approved status and printable Exam Slip
        $studentDashboard = $this->actingAs($this->student)->get(route('student.dashboard'));
        $studentDashboard->assertOk();
        $studentDashboard->assertSee('Approved');
        $studentDashboard->assertSee('Exam Slip');

        $slipResponse = $this->actingAs($this->student)->get(route('student.examSlip', $registration));
        $slipResponse->assertOk();
        $slipResponse->assertSee('RESIT EXAMINATION ADMISSION SLIP');
        $slipResponse->assertSee('ITC231');
    }

    public function test_chief_examiner_can_review_documents_and_approve_supplementary_application(): void
    {
        Storage::fake('public');
        $course = Course::where('course_code', 'ICT112')->firstOrFail();

        // 1. Student submits a supplementary application with medical report and excuse letter
        $medicalReport = UploadedFile::fake()->create('medical_report.pdf', 200, 'application/pdf');
        $letter = UploadedFile::fake()->create('justification_letter.pdf', 150, 'application/pdf');

        $submitResponse = $this->actingAs($this->student)->post(route('student.resit-form.submit'), [
            'name' => 'Kelvyn Kofi Doe',
            'department' => 'Department of ICT Education',
            'index_number' => '2026000001',
            'level' => '200',
            'programme' => 'B.Sc. Information Technology Education',
            'year_entry' => 2024,
            'year_completion' => 2028,
            'telephone' => '0244123456',
            'exam_type' => 'supplementary',
            'course_ids' => [$course->id],
            'medical_report' => $medicalReport,
            'letter' => $letter,
        ]);

        $submitResponse->assertRedirect(route('student.dashboard'));
        $this->assertDatabaseHas('resit_requests', [
            'user_id' => $this->student->id,
            'exam_type' => 'supplementary',
            'status' => 'pending',
        ]);

        $suppRequest = ResitRequest::where('user_id', $this->student->id)
            ->where('exam_type', 'supplementary')
            ->firstOrFail();

        // 2. Chief Examiner can view the uploaded medical report and excuse letter
        $viewReportResponse = $this->actingAs($this->chiefExaminer)->get(
            route('chief-examiner.resit-requests.document', ['resitRequest' => $suppRequest, 'type' => 'medical_report'])
        );
        $viewReportResponse->assertOk();

        $viewLetterResponse = $this->actingAs($this->chiefExaminer)->get(
            route('chief-examiner.resit-requests.document', ['resitRequest' => $suppRequest, 'type' => 'letter'])
        );
        $viewLetterResponse->assertOk();

        // 3. Chief Examiner approves the supplementary application
        $approveResponse = $this->actingAs($this->chiefExaminer)->post(
            route('chief-examiner.resit-requests.approve', $suppRequest)
        );
        $approveResponse->assertSessionHas('success');

        $this->assertDatabaseHas('resit_requests', [
            'id' => $suppRequest->id,
            'status' => 'approved',
        ]);

        // 4. Student dashboard reflects approval
        $studentDashboard = $this->actingAs($this->student)->get(route('student.dashboard'));
        $studentDashboard->assertOk();
        $studentDashboard->assertSee('Supplementary Examination Application Verified & Approved!');
    }

    public function test_chief_examiner_can_reject_supplementary_application_and_student_is_directed_to_register_resits(): void
    {
        Storage::fake('public');
        $course = Course::where('course_code', 'ICT232')->firstOrFail();

        // 1. Student submits a supplementary application
        $medicalReport = UploadedFile::fake()->create('clinic_slip.pdf', 100, 'application/pdf');
        $letter = UploadedFile::fake()->create('absence_letter.pdf', 100, 'application/pdf');

        $this->actingAs($this->student)->post(route('student.resit-form.submit'), [
            'name' => 'Kelvyn Kofi Doe',
            'department' => 'Department of ICT Education',
            'index_number' => '2026000001',
            'level' => '200',
            'programme' => 'B.Sc. Information Technology Education',
            'year_entry' => 2024,
            'year_completion' => 2028,
            'telephone' => '0244123456',
            'exam_type' => 'supplementary',
            'course_ids' => [$course->id],
            'medical_report' => $medicalReport,
            'letter' => $letter,
        ]);

        $suppRequest = ResitRequest::where('user_id', $this->student->id)
            ->where('exam_type', 'supplementary')
            ->firstOrFail();

        // 2. Chief Examiner rejects the supplementary application with specific reason
        $rejectionReason = "Your medical report was from an unaccredited clinic and is unacceptable. Go back and register for standard resits.";
        $rejectResponse = $this->actingAs($this->chiefExaminer)->post(
            route('chief-examiner.resit-requests.reject', $suppRequest),
            ['rejection_reason' => $rejectionReason]
        );
        $rejectResponse->assertSessionHas('info');

        $this->assertDatabaseHas('resit_requests', [
            'id' => $suppRequest->id,
            'status' => 'rejected',
            'rejection_reason' => $rejectionReason,
        ]);

        // 3. Student dashboard shows clear rejection notice and button to register resits
        $studentDashboard = $this->actingAs($this->student)->get(route('student.dashboard'));
        $studentDashboard->assertOk();
        $studentDashboard->assertSee('Supplementary Application Rejected');
        $studentDashboard->assertSee($rejectionReason);
        $studentDashboard->assertSee('Go Back & Register Resits');
    }

    public function test_chief_examiner_can_schedule_publish_and_student_views_timetable(): void
    {
        $course = Course::where('course_code', 'ICT112')->firstOrFail();

        // 1. Chief Examiner navigates to timetables builder
        $viewResponse = $this->actingAs($this->chiefExaminer)->get(route('chief-examiner.timetables.index'));
        $viewResponse->assertOk();
        $viewResponse->assertSee('Examination Timetable Builder');

        // 2. Chief Examiner schedules and publishes an exam paper
        $storeResponse = $this->actingAs($this->chiefExaminer)->post(route('chief-examiner.timetables.store'), [
            'course_id' => $course->id,
            'academic_year' => '2026/2027',
            'semester' => 'Supplementary & Resit Session',
            'exam_date' => '2026-11-25',
            'start_time' => '09:00',
            'end_time' => '12:00',
            'venue' => 'ICT Lab 1 (Main Campus)',
            'seating_capacity' => 60,
            'status' => 'published',
        ]);

        $storeResponse->assertRedirect();
        $storeResponse->assertSessionHas('status');

        $this->assertDatabaseHas('timetables', [
            'course_id' => $course->id,
            'venue' => 'ICT Lab 1 (Main Campus)',
            'status' => 'published',
        ]);

        // 3. Student views the official published timetable
        $studentTimetable = $this->actingAs($this->student)->get(route('student.timetable'));
        $studentTimetable->assertOk();
        $studentTimetable->assertSee('ICT112');
        $studentTimetable->assertSee('Principles of Programming');
        $studentTimetable->assertSee('ICT Lab 1 (Main Campus)');
        $studentTimetable->assertSee('09:00 AM - 12:00 PM');
    }

    public function test_chief_examiner_timetable_clash_detection_prevents_double_booking(): void
    {
        $course1 = Course::where('course_code', 'ICT112')->firstOrFail();
        $course2 = Course::where('course_code', 'ITC231')->firstOrFail();

        // Schedule first exam at ICT Lab 1
        $this->actingAs($this->chiefExaminer)->post(route('chief-examiner.timetables.store'), [
            'course_id' => $course1->id,
            'academic_year' => '2026/2027',
            'semester' => 'Supplementary & Resit Session',
            'exam_date' => '2026-11-25',
            'start_time' => '09:00',
            'end_time' => '12:00',
            'venue' => 'ICT Lab 1 (Main Campus)',
            'seating_capacity' => 60,
            'status' => 'published',
        ]);

        // Attempt to schedule conflicting exam at same venue and overlapping time window (10:00 - 13:00)
        $conflictResponse = $this->actingAs($this->chiefExaminer)->post(route('chief-examiner.timetables.store'), [
            'course_id' => $course2->id,
            'academic_year' => '2026/2027',
            'semester' => 'Supplementary & Resit Session',
            'exam_date' => '2026-11-25',
            'start_time' => '10:00',
            'end_time' => '13:00',
            'venue' => 'ICT Lab 1 (Main Campus)',
            'seating_capacity' => 50,
            'status' => 'published',
        ]);

        $conflictResponse->assertSessionHas('error');
        $this->assertDatabaseMissing('timetables', [
            'course_id' => $course2->id,
            'venue' => 'ICT Lab 1 (Main Campus)',
        ]);
    }
}
