<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ResitRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ResitExamController extends Controller
{
    /**
     * Show the resit / supplementary exam request form.
     */
    public function showForm()
    {
        $examType = request()->query('type', 'resit');
        return view('student.resit_form', ['examType' => $examType]);
    }

    /**
     * Process the submitted form.
     */
    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'department'         => 'required|string|max:255',
            'index_number'       => 'required|string|max:50',
            'level'              => 'required|string|max:20',
            'programme'          => 'required|string|max:255',
            'paper_count'        => 'required|integer|min:1',
            'course_entries'     => 'required|array|min:1',
            'course_entries.*'   => 'required|string|max:255',
            'year_entry'         => 'required|integer',
            'year_completion'    => 'required|integer',
            'telephone'          => 'required|string|max:30',
            'exam_type'          => ['required', Rule::in(['resit', 'supplementary'])],
            // Only required for supplementary exams
            'medical_report'     => 'required_if:exam_type,supplementary|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'letter'             => 'required_if:exam_type,supplementary|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Store uploaded files (if any)
        if ($request->hasFile('medical_report')) {
            $validated['medical_report_path'] = $request->file('medical_report')->store('resit_reports');
        }
        if ($request->hasFile('letter')) {
            $validated['letter_path'] = $request->file('letter')->store('resit_reports');
        }

        $validated['user_id'] = Auth::id();
        $validated['status']   = $validated['exam_type'] === 'supplementary' ? 'pending' : 'approved';

        $resitRequest = ResitRequest::create($validated);

        // Auto-create a pending course registration so it immediately enters the payment & approval pipeline
        if (!empty($validated['course_entries'])) {
            foreach ($validated['course_entries'] as $entry) {
                // Try to find course by matching course code (e.g., CS101)
                $matchedCourse = null;
                $activeCourses = \App\Models\Course::where('is_active', true)->get();
                foreach ($activeCourses as $course) {
                    if (stripos($entry, $course->course_code) !== false) {
                        $matchedCourse = $course;
                        break;
                    }
                }

                // If not matched by code, fall back to first active course
                if (!$matchedCourse) {
                    $matchedCourse = $activeCourses->first();
                }

                if ($matchedCourse) {
                    \App\Models\Registration::firstOrCreate([
                        'user_id' => Auth::id(),
                        'course_id' => $matchedCourse->id,
                        'semester' => 'First Semester',
                        'academic_year' => '2026/2027',
                    ], [
                        'status' => \App\Models\Registration::STATUS_PENDING_PAYMENT,
                    ]);
                }
            }
        }

        // If supplementary, show pending view; otherwise redirect to student dashboard to pay
        if ($validated['exam_type'] === 'supplementary') {
            return view('student.resit_pending');
        }
        return redirect()->route('student.dashboard')->with('status', 'Resit request submitted! Please proceed to pay your course fee with Mobile Money below.');
    }
}
?>