<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(Request $request): View
    {
        $studentCount = User::where('role', 'student')->count();
        $chiefExaminerCount = User::where('role', 'chief_examiner')->count();
        $adminCount = User::where('role', 'admin')->count();

        $chiefExaminerSearch = trim($request->input('chief_examiner_search', ''));
        $chiefExaminersQuery = User::where('role', 'chief_examiner');

        if ($chiefExaminerSearch !== '') {
            $chiefExaminersQuery->where(function ($query) use ($chiefExaminerSearch) {
                $query->where('full_name', 'like', "%{$chiefExaminerSearch}%")
                    ->orWhere('email', 'like', "%{$chiefExaminerSearch}%");
            });
        }

        $chiefExaminers = $chiefExaminersQuery
            ->orderBy('full_name')
            ->paginate(10, ['*'], 'chief_examiners_page')
            ->withQueryString();

        $adminSearch = trim($request->input('admin_search', ''));
        $administratorsQuery = User::where('role', 'admin');

        if ($adminSearch !== '') {
            $administratorsQuery->where(function ($query) use ($adminSearch) {
                $query->where('full_name', 'like', "%{$adminSearch}%")
                    ->orWhere('email', 'like', "%{$adminSearch}%");
            });
        }

        $administrators = $administratorsQuery
            ->orderBy('full_name')
            ->paginate(10, ['*'], 'administrators_page')
            ->withQueryString();

        return view('preview.admin', compact(
            'studentCount',
            'chiefExaminerCount',
            'adminCount',
            'chiefExaminerSearch',
            'chiefExaminers',
            'adminSearch',
            'administrators'
        ));
    }

    public function students(Request $request): View
    {
        $studentSearch = trim($request->input('student_search', ''));
        $academicYear = $request->input('academic_year', '');
        $semester = $request->input('semester', '');
        $level = $request->input('level', '');

        $academicYears = Registration::query()
            ->select('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $semesters = Registration::query()
            ->select('semester')
            ->distinct()
            ->orderBy('semester')
            ->pluck('semester');

        $levels = Registration::query()
            ->join('courses', 'registrations.course_id', '=', 'courses.id')
            ->select('courses.level')
            ->distinct()
            ->orderBy('courses.level')
            ->pluck('courses.level');

        $studentsQuery = User::where('role', 'student');

        if ($studentSearch !== '') {
            $studentsQuery->where(function ($query) use ($studentSearch) {
                $query->where('full_name', 'like', "%{$studentSearch}%")
                    ->orWhere('email', 'like', "%{$studentSearch}%")
                    ->orWhere('student_id_number', 'like', "%{$studentSearch}%");
            });
        }

        if ($academicYear !== '' || $semester !== '' || $level !== '') {
            $studentsQuery->whereHas('registrations', function ($query) use ($academicYear, $semester, $level) {
                if ($academicYear !== '') {
                    $query->where('academic_year', $academicYear);
                }

                if ($semester !== '') {
                    $query->where('semester', $semester);
                }

                if ($level !== '') {
                    $query->whereHas('course', function ($courseQuery) use ($level) {
                        $courseQuery->where('level', $level);
                    });
                }
            });
        }

        $students = $studentsQuery
            ->orderBy('full_name')
            ->paginate(10, ['*'], 'students_page')
            ->withQueryString();

        return view('admin.students.index', compact(
            'studentSearch',
            'academicYear',
            'academicYears',
            'semester',
            'semesters',
            'level',
            'levels',
            'students'
        ));
    }
}
