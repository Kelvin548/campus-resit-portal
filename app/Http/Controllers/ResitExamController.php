<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Registration;
use App\Models\ResitRequest;
use App\Models\SupplementaryCycle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ResitExamController extends Controller
{
    /**
     * Show the resit / supplementary exam request form.
     */
    public function showForm(Request $request): View|RedirectResponse
    {
        // Intercept when applications are closed
        if (! SupplementaryCycle::registrationIsOpenNow()) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Resit applications are currently closed. Please check back later.');
        }

        $examType = $request->query('type', 'resit');

        // Dynamically fetch active courses from the courses table
        $courses = Course::where('is_active', true)->orderBy('course_code')->get();

        return view('student.resit_form', [
            'examType' => $examType,
            'courses' => $courses,
            'student' => Auth::user(),
        ]);
    }

    /**
     * Process the submitted resit / supplementary form.
     */
    public function submitForm(Request $request): View|RedirectResponse
    {
        // Intercept when applications are closed
        if (! SupplementaryCycle::registrationIsOpenNow()) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Resit applications are currently closed. Please check back later.');
        }

        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'department'         => 'required|string|max:255',
            'index_number'       => 'required|string|max:50',
            'level'              => 'required|string|max:20',
            'programme'          => 'required|string|max:255',
            'paper_count'        => 'nullable|integer|min:1',
            'course_ids'         => 'nullable|array|min:1',
            'course_ids.*'       => 'nullable|exists:courses,id',
            'course_id'          => 'nullable|exists:courses,id',
            'course_entries'     => 'nullable|array',
            'year_entry'         => 'required|integer',
            'year_completion'    => 'required|integer',
            'telephone'          => 'required|string|max:30',
            'exam_type'          => ['required', Rule::in(['resit', 'supplementary'])],
            'medical_report'     => 'required_if:exam_type,supplementary|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'letter'             => 'required_if:exam_type,supplementary|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Resolve selected courses from dropdown
        $courseIds = [];
        if (! empty($validated['course_ids'])) {
            $courseIds = array_filter($validated['course_ids']);
        } elseif (! empty($validated['course_id'])) {
            $courseIds = [$validated['course_id']];
        } elseif (! empty($validated['course_entries'])) {
            // Backward compatibility fallback for legacy text entries
            $activeCourses = Course::where('is_active', true)->get();
            foreach ($validated['course_entries'] as $entry) {
                foreach ($activeCourses as $c) {
                    if (stripos($entry, $c->course_code) !== false) {
                        $courseIds[] = $c->id;
                        break;
                    }
                }
            }
        }

        // If no courses were matched or selected, pick first active course as fallback
        if (empty($courseIds)) {
            $firstCourse = Course::where('is_active', true)->first();
            if ($firstCourse) {
                $courseIds[] = $firstCourse->id;
            }
        }

        $selectedCourses = Course::whereIn('id', $courseIds)->get();

        // Build course entries list for audit record
        $courseEntries = [];
        foreach ($selectedCourses as $c) {
            $courseEntries[] = "{$c->course_code} - {$c->course_name}";
        }

        // Store uploaded files for supplementary applications
        $medicalReportPath = null;
        $letterPath = null;
        if ($request->hasFile('medical_report')) {
            $medicalReportPath = $request->file('medical_report')->store('resit_reports', 'public');
        }
        if ($request->hasFile('letter')) {
            $letterPath = $request->file('letter')->store('resit_reports', 'public');
        }

        // Create the resit request record
        ResitRequest::create([
            'user_id'             => Auth::id(),
            'name'                => $validated['name'],
            'department'          => $validated['department'],
            'index_number'        => $validated['index_number'],
            'level'               => $validated['level'],
            'programme'           => $validated['programme'],
            'paper_count'         => count($selectedCourses),
            'course_entries'      => $courseEntries,
            'year_entry'          => $validated['year_entry'],
            'year_completion'     => $validated['year_completion'],
            'telephone'           => $validated['telephone'],
            'exam_type'           => $validated['exam_type'],
            'status'              => $validated['exam_type'] === 'supplementary' ? 'pending' : 'approved',
            'medical_report_path' => $medicalReportPath,
            'letter_path'         => $letterPath,
        ]);

        // Auto-create registrations for the selected courses
        foreach ($selectedCourses as $course) {
            Registration::firstOrCreate([
                'user_id'       => Auth::id(),
                'course_id'     => $course->id,
                'semester'      => $course->semester ?? 'Semester 1',
                'academic_year' => '2026/2027',
            ], [
                // Resit registrations require MoMo payment; supplementary exams route directly as paid/submitted
                'status' => $validated['exam_type'] === 'supplementary'
                    ? Registration::STATUS_PAID
                    : Registration::STATUS_PENDING_PAYMENT,
            ]);
        }

        if ($validated['exam_type'] === 'supplementary') {
            return redirect()->route('student.dashboard')
                ->with('success', 'Supplementary examination application submitted successfully! Your application is in the Chief Examiner review queue.');
        }

        return redirect()->route('student.dashboard')
            ->with('status', 'Resit request submitted! Please proceed to pay your course fee with Mobile Money below.');
    }
}
