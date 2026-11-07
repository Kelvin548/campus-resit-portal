<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\SupplementaryCycle;
use App\Models\Timetable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChiefExaminerController extends Controller
{
    /**
     * Display the Chief Examiner portal dashboard.
     */
    public function dashboard(): View
    {
        // 1. Stats
        $activeCoursesCount = Course::where('is_active', true)->count();
        $pendingApprovalCount = Registration::where('status', Registration::STATUS_PAID)->count();
        $approvedCount = Registration::where('status', Registration::STATUS_APPROVED)->count();
        $totalFeesCollected = Payment::where('status', Payment::STATUS_SUCCESSFUL)->sum('amount');

        // 2. Registrations awaiting Chief Examiner Approval (Students who have paid via MoMo)
        $pendingApprovals = Registration::with(['user', 'course', 'payment'])
            ->where('status', Registration::STATUS_PAID)
            ->latest()
            ->get();

        // 3. Recently approved or rejected registrations
        $processedRegistrations = Registration::with(['user', 'course', 'payment'])
            ->whereIn('status', [Registration::STATUS_APPROVED, Registration::STATUS_REJECTED])
            ->latest('updated_at')
            ->take(15)
            ->get();

        // 4. Course catalogue
        $courses = Course::where('is_active', true)->orderBy('course_code')->get();

        // 5. Timetables
        $timetables = Timetable::with('course')->latest()->take(10)->get();

        return view('chief-examiner.dashboard', [
            'activeCoursesCount' => $activeCoursesCount,
            'pendingApprovalCount' => $pendingApprovalCount,
            'approvedCount' => $approvedCount,
            'totalFeesCollected' => $totalFeesCollected,
            'pendingApprovals' => $pendingApprovals,
            'processedRegistrations' => $processedRegistrations,
            'courses' => $courses,
            'timetables' => $timetables,
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
}
