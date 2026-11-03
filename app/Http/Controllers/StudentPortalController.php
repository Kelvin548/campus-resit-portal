<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentPortalController extends Controller
{
    /**
     * Display the Student Personal Dashboard.
     */
    public function dashboard(): View
    {
        /** @var User $user */
        $user = Auth::user();

        // Retrieve student's registrations with course and payment information
        $registrations = Registration::with(['course', 'payment'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Calculate key metrics
        $totalRegistered = $registrations->count();
        
        $pendingPaymentCount = $registrations->where('status', Registration::STATUS_PENDING_PAYMENT)->count();
        
        $paidCount = $registrations->whereIn('status', [Registration::STATUS_PAID, Registration::STATUS_APPROVED])->count();
        
        $approvedCount = $registrations->where('status', Registration::STATUS_APPROVED)->count();

        // Fetch published timetables matching courses registered by this student
        $registeredCourseIds = $registrations->pluck('course_id')->unique()->toArray();
        $upcomingTimetables = Timetable::with('course')
            ->where('status', Timetable::STATUS_PUBLISHED)
            ->whereIn('course_id', $registeredCourseIds)
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        return view('student.dashboard', [
            'student' => $user,
            'registrations' => $registrations,
            'totalRegistered' => $totalRegistered,
            'pendingPaymentCount' => $pendingPaymentCount,
            'paidCount' => $paidCount,
            'approvedCount' => $approvedCount,
            'upcomingTimetables' => $upcomingTimetables,
        ]);
    }

    /**
     * Display available courses and fee breakdown to students.
     */
    public function courses(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        $query = Course::query()->where('is_active', true);

        // Optional filtering by level or search query
        if ($request->filled('level')) {
            $query->where('level', $request->input('level'));
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->input('search') . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('course_code', 'like', $searchTerm)
                    ->orWhere('course_name', 'like', $searchTerm);
            });
        }

        $courses = $query->orderBy('level')->orderBy('course_code')->get();

        // Get list of course IDs already registered by this student
        $registeredCourseIds = Registration::where('user_id', $user->id)
            ->pluck('course_id')
            ->toArray();

        return view('student.courses', [
            'courses' => $courses,
            'registeredCourseIds' => $registeredCourseIds,
            'selectedLevel' => $request->input('level'),
            'search' => $request->input('search'),
        ]);
    }

    /**
     * Show registration confirmation / application form for a specific course.
     */
    public function showRegistrationForm(Course $course): View|RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // Verify if registration is currently open
        if (! $course->isRegistrationOpen()) {
            return redirect()->route('student.courses')
                ->with('error', "Registration for {$course->course_code} is currently closed or deadline has passed.");
        }

        // Duplicate check
        $alreadyRegistered = Registration::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if ($alreadyRegistered) {
            return redirect()->route('student.dashboard')
                ->with('warning', "You are already registered for {$course->course_code} ({$course->course_name}).");
        }

        return view('student.register-course', [
            'course' => $course,
            'student' => $user,
        ]);
    }

    /**
     * Submit and store student course registration.
     */
    public function storeRegistration(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'semester' => ['required', 'string', 'max:50'],
            'academic_year' => ['required', 'string', 'max:50'],
            'resit_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $course = Course::findOrFail($validated['course_id']);

        // Check if registration period is valid
        if (! $course->isRegistrationOpen()) {
            return back()->with('error', 'Registration window for this course is closed or the deadline has expired.');
        }

        // Check for duplicate registration
        $exists = Registration::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('semester', $validated['semester'])
            ->where('academic_year', $validated['academic_year'])
            ->exists();

        if ($exists) {
            return redirect()->route('student.dashboard')
                ->with('error', "You have already registered for {$course->course_code} for the {$validated['academic_year']} {$validated['semester']}.");
        }

        // Create the registration record
        $registration = Registration::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'semester' => $validated['semester'],
            'academic_year' => $validated['academic_year'],
            'status' => Registration::STATUS_PENDING_PAYMENT,
        ]);

        return redirect()->route('student.dashboard')
            ->with('status', "Registration submitted successfully for {$course->course_code}! Please complete payment to confirm your seat.");
    }

    /**
     * Display published examination timetables.
     */
    public function timetable(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        $query = Timetable::with(['course', 'publishedBy'])
            ->where('status', Timetable::STATUS_PUBLISHED);

        if ($request->filled('search')) {
            $search = '%' . $request->input('search') . '%';
            $query->whereHas('course', function ($q) use ($search) {
                $q->where('course_code', 'like', $search)
                    ->orWhere('course_name', 'like', $search);
            })->orWhere('venue', 'like', $search);
        }

        $timetables = $query->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        $registeredCourseIds = Registration::where('user_id', $user->id)
            ->pluck('course_id')
            ->toArray();

        return view('student.timetable', [
            'timetables' => $timetables,
            'registeredCourseIds' => $registeredCourseIds,
            'search' => $request->input('search'),
        ]);
    }

    /**
     * Display printable examination registration slip.
     */
    public function examSlip(Registration $registration): View|RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // Enforce ownership
        if ($registration->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this registration slip.');
        }

        $registration->load(['course', 'payment', 'approvedBy']);

        // Check if timetable exists for this course
        $timetable = Timetable::where('course_id', $registration->course_id)
            ->where('status', Timetable::STATUS_PUBLISHED)
            ->first();

        return view('student.exam-slip', [
            'registration' => $registration,
            'student' => $user,
            'timetable' => $timetable,
        ]);
    }

    /**
     * Initiate / Process Mobile Money payment for a pending registration.
     * (Integrates with Member 3 payment specifications)
     */
    public function pay(Request $request, Registration $registration): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($registration->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this registration.');
        }

        if ($registration->isPaid() || $registration->isApproved()) {
            return redirect()->route('student.dashboard')
                ->with('info', 'This registration has already been paid for.');
        }

        $validated = $request->validate([
            'phone_number' => ['required', 'string', 'max:20'],
            'network' => ['required', 'string', 'in:MTN,Telecel,AT'],
        ]);

        $course = $registration->course;
        $amount = $course->registration_fee;

        // Generate unique transaction reference
        $ref = 'PAY-' . strtoupper(Str::random(10));

        // Create Payment record
        $payment = Payment::create([
            'registration_id' => $registration->id,
            'user_id' => $user->id,
            'transaction_reference' => $ref,
            'amount' => $amount,
            'payment_method' => 'momo',
            'phone_number' => $validated['phone_number'],
            'network' => $validated['network'],
            'status' => Payment::STATUS_SUCCESSFUL,
            'gateway_reference' => 'GW-' . strtoupper(Str::random(8)),
            'gateway_response' => [
                'status' => 'success',
                'message' => 'Test Mobile Money payment completed successfully.',
                'timestamp' => now()->toIso8601String(),
            ],
            'paid_at' => now(),
        ]);

        // Update registration status
        $registration->update([
            'status' => Registration::STATUS_PAID,
        ]);

        return redirect()->route('student.dashboard')
            ->with('status', "Payment of GHS {$amount} for {$course->course_code} completed successfully! Reference: {$ref}. Awaiting Chief Examiner review.");
    }
}
