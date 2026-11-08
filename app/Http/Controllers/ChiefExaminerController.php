<?php

namespace App\Http\Controllers;

use App\Mail\ChiefExaminerBroadcastMail;
use App\Models\BroadcastMessage;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\ResitRequest;
use App\Models\SupplementaryCycle;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ChiefExaminerController extends Controller
{
    /**
     * Display the Chief Examiner portal dashboard.
     */
    public function dashboard(): View
    {
        // 1. Stats
        $activeCoursesCount = Course::where('is_active', true)->count();
        $pendingApprovalCount = Registration::where('status', Registration::STATUS_APPROVED)
            ->whereHas('payment')
            ->count();
        $approvedCount = Registration::where('status', Registration::STATUS_APPROVED)->count();
        $totalFeesCollected = Payment::where('status', Payment::STATUS_SUCCESSFUL)->sum('amount');

        // 2. Confirmed resit registrations (auto-approved on MoMo payment — read-only view for Chief Examiner)
        $pendingApprovals = Registration::with(['user', 'course', 'payment'])
            ->where('status', Registration::STATUS_APPROVED)
            ->whereHas('payment')
            ->latest()
            ->get();

        // 3. Supplementary Applications (Doctor's Reports & Extenuating Letters Queue)
        $pendingSupplementary = ResitRequest::with('user')
            ->where('exam_type', 'supplementary')
            ->where('status', 'pending')
            ->latest('id')
            ->get();

        // 4. Recently processed resit registrations
        $processedRegistrations = Registration::with(['user', 'course', 'payment'])
            ->whereIn('status', [Registration::STATUS_APPROVED, Registration::STATUS_REJECTED])
            ->latest('updated_at')
            ->take(15)
            ->get();

        // 5. Recently processed supplementary decisions
        $processedSupplementary = ResitRequest::with('user')
            ->where('exam_type', 'supplementary')
            ->whereIn('status', ['approved', 'rejected'])
            ->latest('updated_at')
            ->take(15)
            ->get();

        // 6. Course catalogue - all courses with active courses first
        $courses = Course::orderBy('is_active', 'desc')->orderBy('course_code')->get();

        // 7. Timetables (chronological master schedule)
        $timetables = Timetable::with('course')->orderBy('exam_date')->orderBy('start_time')->get();

        // 8. Active and recent broadcast notices
        $broadcasts = BroadcastMessage::latest('id')->take(15)->get();

        return view('chief-examiner.dashboard', [
            'activeCoursesCount' => $activeCoursesCount,
            'pendingApprovalCount' => $pendingApprovalCount,
            'approvedCount' => $approvedCount,
            'totalFeesCollected' => $totalFeesCollected,
            'pendingApprovals' => $pendingApprovals,
            'pendingSupplementary' => $pendingSupplementary,
            'processedRegistrations' => $processedRegistrations,
            'processedSupplementary' => $processedSupplementary,
            'courses' => $courses,
            'timetables' => $timetables,
            'broadcasts' => $broadcasts,
            'currentCycle' => SupplementaryCycle::current(),
            'registrationOpen' => SupplementaryCycle::registrationIsOpenNow(),
        ]);
    }

    /**
     * Approve a student's paid resit registration.
     */
    public function approveRegistration(Registration $registration): RedirectResponse
    {
        if ($registration->status !== Registration::STATUS_PAID) {
            return back()->with('error', 'Only registrations with verified payments can be approved.');
        }

        $registration->update([
            'status' => Registration::STATUS_APPROVED,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $studentName = $registration->user->full_name ?? 'Student';
        $courseCode = $registration->course->course_code ?? 'Course';

        return back()->with('success', "Registration for {$studentName} ({$courseCode}) has been approved! The student can now print their exam admission slip.");
    }

    /**
     * Reject a student's resit registration.
     */
    public function rejectRegistration(Request $request, Registration $registration): RedirectResponse
    {
        $registration->update([
            'status' => Registration::STATUS_REJECTED,
        ]);

        $studentName = $registration->user->full_name ?? 'Student';

        return back()->with('info', "Registration for {$studentName} has been rejected.");
    }

    /**
     * View or download an uploaded supplementary justification document (Medical Report or Official Letter).
     */
    public function viewDocument(ResitRequest $resitRequest, string $type): BinaryFileResponse
    {
        $path = $type === 'letter' ? $resitRequest->letter_path : $resitRequest->medical_report_path;

        if (! $path || ! Storage::disk('public')->exists($path)) {
            abort(404, 'The requested examination document was not found or has been removed.');
        }

        return response()->file(Storage::disk('public')->path($path));
    }

    /**
     * Approve a student's supplementary examination request after verifying medical/justification documents.
     */
    public function approveSupplementary(ResitRequest $resitRequest): RedirectResponse
    {
        $resitRequest->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        // Automatically clear any corresponding student registrations
        Registration::where('user_id', $resitRequest->user_id)
            ->where('status', '!=', Registration::STATUS_APPROVED)
            ->update([
                'status' => Registration::STATUS_APPROVED,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

        $studentName = $resitRequest->name ?? ($resitRequest->user->full_name ?? 'Student');

        return back()->with('success', "Supplementary application for {$studentName} has been verified and approved. The student is cleared to write their supplementary examination.");
    }

    /**
     * Reject a student's supplementary request with a clear reason directing them to register for resits.
     */
    public function rejectSupplementary(Request $request, ResitRequest $resitRequest): RedirectResponse
    {
        $reason = trim($request->input('rejection_reason', ''));
        if (empty($reason)) {
            $reason = "Your doctor's medical report or justification letter is unacceptable. Please go back to register for a standard resit examination.";
        }

        $resitRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);

        // Update corresponding registrations to rejected
        Registration::where('user_id', $resitRequest->user_id)
            ->where('status', '!=', Registration::STATUS_APPROVED)
            ->update([
                'status' => Registration::STATUS_REJECTED,
            ]);

        $studentName = $resitRequest->name ?? ($resitRequest->user->full_name ?? 'Student');

        return back()->with('info', "Supplementary application for {$studentName} has been rejected. Notification and redirection to resit registration have been sent to the student dashboard.");
    }

    /**
     * Create a new course in the active database table.
     * Note: Level and Semester are made optional so courses can be scheduled flexibly.
     */
    public function storeCourse(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course_code' => ['required', 'string', 'max:20', 'unique:courses,course_code'],
            'course_name' => ['required', 'string', 'max:255'],
            'credit_hours' => ['required', 'integer', 'min:1', 'max:12'],
            'level' => ['nullable', 'integer'],
            'semester' => ['nullable', 'string', 'max:50'],
            'registration_fee' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $validated['level'] = $validated['level'] ?? 100;
        $validated['semester'] = $validated['semester'] ?? 'All Semesters';
        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : true;

        Course::create($validated);

        return back()->with('success', "Course {$validated['course_code']} ({$validated['course_name']}) has been added successfully!");
    }

    /**
     * Update an existing course in the active database table.
     */
    public function updateCourse(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'course_code' => ['required', 'string', 'max:20', Rule::unique('courses', 'course_code')->ignore($course->id)],
            'course_name' => ['required', 'string', 'max:255'],
            'credit_hours' => ['required', 'integer', 'min:1', 'max:12'],
            'level' => ['nullable', 'integer'],
            'semester' => ['nullable', 'string', 'max:50'],
            'registration_fee' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $validated['level'] = $validated['level'] ?? ($course->level ?? 100);
        $validated['semester'] = $validated['semester'] ?? ($course->semester ?? 'All Semesters');
        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : false;

        $course->update($validated);

        return back()->with('success', "Course {$course->course_code} has been updated successfully!");
    }

    /**
     * Delete a course from the active database table.
     */
    public function destroyCourse(Course $course): RedirectResponse
    {
        $code = $course->course_code;
        $name = $course->course_name;
        $course->delete();

        return back()->with('success', "Course {$code} ({$name}) has been deleted successfully!");
    }

    /**
     * Display the dedicated Student Broadcast System management view.
     */
    public function broadcastsIndex(): View
    {
        $broadcasts = BroadcastMessage::latest('id')->get();
        return view('chief-examiner.broadcasts', [
            'broadcasts' => $broadcasts,
            'currentCycle' => SupplementaryCycle::current(),
            'registrationOpen' => SupplementaryCycle::registrationIsOpenNow(),
        ]);
    }

    /**
     * Broadcast an urgent announcement or schedule update to all students and dispatch email alerts.
     */
    public function storeBroadcast(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'category' => ['required', 'string', 'in:general,postponement,exam_time,urgent'],
            'send_email' => ['nullable'],
        ]);

        $broadcast = BroadcastMessage::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'category' => $validated['category'],
            'created_by' => auth()->id(),
            'is_active' => true,
        ]);

        $emailsSent = 0;
        $shouldSendEmail = $request->boolean('send_email', true);

        if ($shouldSendEmail) {
            $students = User::where('role', 'student')->whereNotNull('email')->get();
            foreach ($students as $student) {
                try {
                    Mail::to($student->email)->send(new ChiefExaminerBroadcastMail($broadcast, $student));
                    $emailsSent++;
                } catch (\Throwable $e) {
                    Log::warning("Could not dispatch broadcast email to {$student->email}: " . $e->getMessage());
                }
            }
            $broadcast->update(['emails_sent_count' => $emailsSent]);
        }

        $feedback = "Broadcast update published to all student portals!";
        if ($emailsSent > 0) {
            $feedback .= " Email notifications dispatched to {$emailsSent} student accounts.";
        }

        return back()->with('success', $feedback);
    }

    /**
     * Remove an announcement.
     */
    public function destroyBroadcast(BroadcastMessage $broadcast): RedirectResponse
    {
        $broadcast->delete();
        return back()->with('success', 'Announcement removed from student portals.');
    }

    /**
     * Global administrator toggle to switch application status between Available and Unavailable.
     */
    public function toggleApplications(Request $request): RedirectResponse
    {
        $cycle = SupplementaryCycle::current();

        if (! $cycle) {
            $cycle = SupplementaryCycle::create([
                'name' => 'USTED Resit & Supplementary Cycle',
                'registration_starts_at' => now()->subDay(),
                'registration_ends_at' => now()->addDays(30),
                'student_access_ends_at' => now()->addDays(60),
                'is_current' => true,
                'is_open' => true,
            ]);
        }

        if ($request->has('target_state')) {
            $newOpen = in_array($request->input('target_state'), ['open', '1', 'available', 'true'], true);
        } else {
            $newOpen = ! SupplementaryCycle::registrationIsOpenNow();
        }

        $updateData = ['is_open' => $newOpen];

        if ($newOpen) {
            if ($cycle->registration_starts_at && $cycle->registration_starts_at->isFuture()) {
                $updateData['registration_starts_at'] = now()->subHour();
            }
            if ($cycle->registration_ends_at && $cycle->registration_ends_at->isPast()) {
                $updateData['registration_ends_at'] = now()->addDays(30);
            }
            if ($cycle->student_access_ends_at && $cycle->student_access_ends_at->isPast()) {
                $updateData['student_access_ends_at'] = now()->addDays(60);
            }
        }

        $cycle->update($updateData);

        $statusLabel = $newOpen ? 'Available (Open)' : 'Unavailable (Closed)';
        return back()->with('success', "System registration window is now set to: {$statusLabel}.");
    }

    /**
     * Display the Examination Timetable master scheduler.
     */
    public function timetablesIndex(Request $request): View
    {
        $courses = Course::where('is_active', true)->orderBy('course_code')->get();

        $query = Timetable::with(['course', 'publishedBy']);

        if ($request->filled('search')) {
            $search = '%' . $request->input('search') . '%';
            $query->where(function ($q) use ($search) {
                $q->whereHas('course', function ($sub) use ($search) {
                    $sub->where('course_code', 'like', $search)
                        ->orWhere('course_name', 'like', $search);
                })
                ->orWhere('venue', 'like', $search)
                ->orWhere('academic_year', 'like', $search)
                ->orWhere('semester', 'like', $search);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $timetables = $query->orderBy('exam_date')
            ->orderBy('start_time')
            ->paginate(20)
            ->withQueryString();

        return view('chief-examiner.timetables', [
            'timetables' => $timetables,
            'courses' => $courses,
            'search' => $request->input('search'),
            'statusFilter' => $request->input('status'),
            'publishedCount' => Timetable::where('status', Timetable::STATUS_PUBLISHED)->count(),
            'draftCount' => Timetable::where('status', Timetable::STATUS_DRAFT)->count(),
            'distinctVenuesCount' => Timetable::distinct('venue')->count('venue'),
        ]);
    }

    /**
     * Store and optionally publish a newly constructed examination timetable paper.
     */
    public function storeTimetable(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'academic_year' => ['required', 'string', 'max:50'],
            'semester' => ['required', 'string', 'max:100'],
            'exam_date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'venue' => ['required', 'string', 'max:255'],
            'seating_capacity' => ['required', 'integer', 'min:1', 'max:2000'],
            'status' => ['required', 'in:draft,published'],
        ]);

        // Clash Detection: Check if another exam is scheduled at the same venue and date with overlapping time
        $clash = Timetable::with('course')
            ->conflicting($validated['venue'], $validated['exam_date'], $validated['start_time'], $validated['end_time'])
            ->first();

        if ($clash) {
            $clashCourse = $clash->course ? "{$clash->course->course_code} - {$clash->course->course_name}" : 'Another course';
            return back()
                ->withInput()
                ->with('error', "Venue Scheduling Clash! '{$validated['venue']}' is already booked on {$validated['exam_date']} from " . \Carbon\Carbon::parse($clash->start_time)->format('h:i A') . " to " . \Carbon\Carbon::parse($clash->end_time)->format('h:i A') . " for {$clashCourse}. Please choose another venue or different time.");
        }

        $timetable = Timetable::create([
            'course_id' => $validated['course_id'],
            'academic_year' => $validated['academic_year'],
            'semester' => $validated['semester'],
            'exam_date' => $validated['exam_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'venue' => $validated['venue'],
            'seating_capacity' => $validated['seating_capacity'],
            'status' => $validated['status'],
            'published_by' => auth()->id(),
            'published_at' => $validated['status'] === Timetable::STATUS_PUBLISHED ? now() : null,
        ]);

        $course = Course::find($validated['course_id']);
        $publishedMsg = $validated['status'] === Timetable::STATUS_PUBLISHED ? 'and published to students' : 'saved as draft';

        return back()->with('status', "Timetable for {$course->course_code} successfully scheduled {$publishedMsg}!");
    }

    /**
     * Update an existing examination timetable slot.
     */
    public function updateTimetable(Request $request, Timetable $timetable): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'academic_year' => ['required', 'string', 'max:50'],
            'semester' => ['required', 'string', 'max:100'],
            'exam_date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'venue' => ['required', 'string', 'max:255'],
            'seating_capacity' => ['required', 'integer', 'min:1', 'max:2000'],
            'status' => ['required', 'in:draft,published'],
        ]);

        // Clash Detection: Check if another exam is scheduled at the same venue and date excluding current slot
        $clash = Timetable::with('course')
            ->conflicting($validated['venue'], $validated['exam_date'], $validated['start_time'], $validated['end_time'], $timetable->id)
            ->first();

        if ($clash) {
            $clashCourse = $clash->course ? "{$clash->course->course_code} - {$clash->course->course_name}" : 'Another course';
            return back()
                ->withInput()
                ->with('error', "Venue Scheduling Clash! '{$validated['venue']}' is already booked on {$validated['exam_date']} from " . \Carbon\Carbon::parse($clash->start_time)->format('h:i A') . " to " . \Carbon\Carbon::parse($clash->end_time)->format('h:i A') . " for {$clashCourse}.");
        }

        $wasPublished = $timetable->isPublished();
        $isNowPublished = $validated['status'] === Timetable::STATUS_PUBLISHED;

        $timetable->update([
            'course_id' => $validated['course_id'],
            'academic_year' => $validated['academic_year'],
            'semester' => $validated['semester'],
            'exam_date' => $validated['exam_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'venue' => $validated['venue'],
            'seating_capacity' => $validated['seating_capacity'],
            'status' => $validated['status'],
            'published_by' => auth()->id(),
            'published_at' => $isNowPublished ? ($wasPublished ? $timetable->published_at : now()) : null,
        ]);

        return back()->with('status', "Timetable for {$timetable->course->course_code} successfully updated!");
    }

    /**
     * Toggle timetable publication status between draft and published.
     */
    public function toggleTimetableStatus(Timetable $timetable): RedirectResponse
    {
        if ($timetable->status === Timetable::STATUS_PUBLISHED) {
            $timetable->update([
                'status' => Timetable::STATUS_DRAFT,
                'published_at' => null,
            ]);
            $msg = "Timetable for {$timetable->course->course_code} moved to draft (hidden from students).";
        } else {
            $timetable->update([
                'status' => Timetable::STATUS_PUBLISHED,
                'published_by' => auth()->id(),
                'published_at' => now(),
            ]);
            $msg = "Timetable for {$timetable->course->course_code} is now published and visible to students!";
        }

        return back()->with('status', $msg);
    }

    /**
     * Delete an examination timetable entry.
     */
    public function destroyTimetable(Timetable $timetable): RedirectResponse
    {
        $code = $timetable->course->course_code ?? 'Course';
        $timetable->delete();

        return back()->with('status', "Timetable paper for {$code} deleted successfully.");
    }
}
