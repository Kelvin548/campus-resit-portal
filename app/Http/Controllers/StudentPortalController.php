<?php

namespace App\Http\Controllers;

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

        // Fetch latest broadcast announcements from Chief Examiner
        $announcements = BroadcastMessage::where('is_active', true)->latest('id')->take(5)->get();

        // Fetch student's supplementary applications and decisions
        $supplementaryRequests = ResitRequest::where('user_id', $user->id)
            ->where('exam_type', 'supplementary')
            ->latest('id')
            ->get();
        $rejectedSupplementary = $supplementaryRequests->where('status', 'rejected')->first();
        $approvedSupplementary = $supplementaryRequests->where('status', 'approved')->first();
        $pendingSupplementary = $supplementaryRequests->where('status', 'pending')->first();

        return view('student.dashboard', [
            'student' => $user,
            'studentRegistrationOpen' => SupplementaryCycle::registrationIsOpenNow(),
            'registrations' => $registrations,
            'totalRegistered' => $totalRegistered,
            'pendingPaymentCount' => $pendingPaymentCount,
            'paidCount' => $paidCount,
            'approvedCount' => $approvedCount,
            'upcomingTimetables' => $upcomingTimetables,
            'announcements' => $announcements,
            'supplementaryRequests' => $supplementaryRequests,
            'rejectedSupplementary' => $rejectedSupplementary,
            'approvedSupplementary' => $approvedSupplementary,
            'pendingSupplementary' => $pendingSupplementary,
        ]);
    }

    /**
     * Show all official Chief Examiner announcements and updates.
     */
    public function announcements(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $announcements = BroadcastMessage::where('is_active', true)->latest('id')->get();

        return view('student.announcements', [
            'student' => $user,
            'announcements' => $announcements,
            'studentRegistrationOpen' => SupplementaryCycle::registrationIsOpenNow(),
        ]);
    }

    /**
     * Show the registration hub page with separate Resit and Supplementary buttons.
     */
    public function registration(): View|RedirectResponse
    {
        if (! SupplementaryCycle::registrationIsOpenNow()) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Resit applications are currently closed. Please check back later.');
        }

        return view('student.registration');
    }

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
            'studentRegistrationOpen' => SupplementaryCycle::registrationIsOpenNow(),
            'registrationClosesAt' => SupplementaryCycle::current()?->registration_ends_at,
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
        if (! SupplementaryCycle::registrationIsOpenNow()) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Resit applications are currently closed. Please check back later.');
        }

        if (! $course->is_active) {
            return redirect()->route('student.courses')
                ->with('error', 'This course is not currently offered for supplementary registration.');
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
            'registrationClosesAt' => SupplementaryCycle::current()?->registration_ends_at,
        ]);
    }

    /**
     * Submit and store student course registration.
     */
    public function storeRegistration(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // Verify if registration is currently open
        if (! SupplementaryCycle::registrationIsOpenNow()) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Resit applications are currently closed. Please check back later.');
        }

        // -------------------------------------------------
        // 1️⃣ Determine exam type (resit or supplementary)
        // -------------------------------------------------
        $examType = $request->input('exam_type', 'resit'); // default to resit

        // -------------------------------------------------
        // 2️⃣ Common validation rules
        // -------------------------------------------------
        $commonRules = [
            'course_id'      => ['required', 'exists:courses,id'],
            'semester'       => ['required', 'string', 'max:50'],
            'academic_year'  => ['required', 'string', 'max:50'],
            'exam_type'      => ['nullable', 'in:resit,supplementary'],
        ];

        // -------------------------------------------------
        // 3️⃣ Additional rules for supplementary exams
        // -------------------------------------------------
        $supplementaryRules = [
            'medical_report' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'letter'         => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ];

        $rules = $commonRules;
        if ($examType === 'supplementary') {
            $rules = array_merge($rules, $supplementaryRules);
        }

        $validated = $request->validate($rules);

        // -------------------------------------------------
        // 4️⃣ Retrieve the course and run generic checks
        // -------------------------------------------------
        $course = Course::findOrFail($validated['course_id']);
        if (! $course->is_active) {
            return back()->with('error', 'This course is not currently offered.');
        }

        // -------------------------------------------------
        // 5️⃣ Guard against duplicate registrations
        // -------------------------------------------------
        $duplicate = Registration::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('semester', $validated['semester'])
            ->where('academic_year', $validated['academic_year'])
            ->exists();

        if ($duplicate) {
            return redirect()->route('student.dashboard')
                ->with('error', "You have already registered for {$course->course_code} for {$validated['academic_year']} {$validated['semester']}.");
        }

        // -------------------------------------------------
        // 6️⃣ Branch based on exam type
        // -------------------------------------------------
        if ($examType === 'resit') {
            // ---- Resit (paid) flow ----
            $registration = Registration::create([
                'user_id'        => $user->id,
                'course_id'      => $course->id,
                'semester'       => $validated['semester'],
                'academic_year'  => $validated['academic_year'],
                'status'         => Registration::STATUS_PENDING_PAYMENT,
            ]);

            return redirect()->route('student.dashboard')
                ->with('success', 'Resit registration saved – please proceed to payment.');
        }

        // ---- Supplementary (free, with uploads) ----
        // Store uploaded files
        $medicalPath = $request->file('medical_report')->store('supplementary/medical', 'public');
        $letterPath   = $request->file('letter')->store('supplementary/letters', 'public');

        // Create supplementary application and its item
        $suppApp = SupplementaryApplication::create([
            'user_id'        => $user->id,
            'status'         => 'pending_approval',
            'medical_report' => $medicalPath,
            'letter'         => $letterPath,
        ]);

        SupplementaryApplicationItem::create([
            'supplementary_application_id' => $suppApp->id,
            'course_id'                    => $course->id,
            'semester'                     => $validated['semester'],
            'academic_year'                => $validated['academic_year'],
        ]);

        return redirect()->route('student.dashboard')
            ->with('success', 'Supplementary application submitted – awaiting approval.');
    }

    /**
     * Display published examination timetables.
     */
    public function timetable(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        $registeredCourseIds = Registration::where('user_id', $user->id)
            ->pluck('course_id')
            ->toArray();

        // Include any course IDs stored in resit_requests course_entries JSON
        $resitRequests = ResitRequest::where('user_id', $user->id)->get();
        foreach ($resitRequests as $req) {
            $entries = $req->course_entries ?? [];
            if (is_array($entries)) {
                foreach ($entries as $entry) {
                    if (is_numeric($entry)) {
                        $registeredCourseIds[] = (int) $entry;
                    } elseif (is_array($entry) && !empty($entry['id'])) {
                        $registeredCourseIds[] = (int) $entry['id'];
                    } elseif (is_array($entry) && !empty($entry['course_id'])) {
                        $registeredCourseIds[] = (int) $entry['course_id'];
                    }
                }
            }
        }
        $registeredCourseIds = array_values(array_unique($registeredCourseIds));

        $query = Timetable::with(['course', 'publishedBy'])
            ->where('status', Timetable::STATUS_PUBLISHED);

        if ($request->filled('search')) {
            $search = '%' . $request->input('search') . '%';
            $query->where(function ($q) use ($search) {
                $q->whereHas('course', function ($sub) use ($search) {
                    $sub->where('course_code', 'like', $search)
                        ->orWhere('course_name', 'like', $search);
                })
                ->orWhere('venue', 'like', $search)
                ->orWhere('academic_year', 'like', $search);
            });
        }

        $timetables = $query->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        return view('student.timetable', [
            'timetables' => $timetables,
            'registeredCourseIds' => $registeredCourseIds,
            'studentRegistrationOpen' => SupplementaryCycle::registrationIsOpenNow(),
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

        if (! SupplementaryCycle::registrationIsOpenNow()) {
            return back()->with('error', 'Payment submissions are closed for this supplementary cycle.');
        }

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
