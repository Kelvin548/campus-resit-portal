@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-6">

    <!-- Breadcrumb -->
    <div class="mb-4">
        <a href="{{ route('student.courses') }}" class="text-xs font-semibold text-brand-600 hover:underline flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Back to Course Catalog</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        
        <!-- Header -->
        <div class="border-b border-slate-100 pb-5 mb-6">
            <span class="inline-block px-2.5 py-1 rounded-md text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200 mb-2">
                Resit Application Form
            </span>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Register for {{ $course->course_code }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $course->course_name }} • Level {{ $course->level }}</p>
        </div>

        <!-- Course Summary Fee Card -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="text-xs text-slate-500">Student Applicant:</div>
                <div class="font-bold text-slate-900">{{ $student->full_name }} ({{ $student->student_id_number }})</div>
                <div class="text-xs text-slate-400">{{ $student->email }}</div>
            </div>
            <div class="sm:text-right">
                <div class="text-xs text-slate-500">Standard Resit Fee:</div>
                <div class="text-2xl font-extrabold text-brand-700">GHS {{ number_format($course->registration_fee, 2) }}</div>
            </div>
        </div>

        <!-- Application Form -->
        <form method="POST" action="{{ route('student.storeRegistration') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="academic_year" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Academic Year</label>
                    <select name="academic_year" id="academic_year" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">
                        <option value="2026/2027" selected>2026/2027</option>
                        <option value="2025/2026">2025/2026</option>
                    </select>
                </div>

                <div>
                    <label for="semester" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Academic Semester</label>
                    <select name="semester" id="semester" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">
                        <option value="Semester 1" {{ $course->semester == 'Semester 1' ? 'selected' : '' }}>Semester 1</option>
                        <option value="Semester 2" {{ $course->semester == 'Semester 2' ? 'selected' : '' }}>Semester 2</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="resit_reason" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Reason for Resit (Optional)</label>
                <textarea name="resit_reason" id="resit_reason" rows="3"
                          placeholder="e.g. Previous grade improvement, missed initial examination due to medical reasons, etc."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500"></textarea>
            </div>

            <!-- Policy & Terms Notice -->
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800 space-y-1">
                <div class="font-semibold flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Registration Policy & Duplicate Prevention
                </div>
                <p>Once submitted, this course registration will be marked as <strong class="font-medium text-amber-900">Pending Payment</strong>. You must complete Mobile Money authorization before the registration deadline to secure examination eligibility.</p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-3">
                <a href="{{ route('student.courses') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow-sm transition flex items-center gap-2">
                    <span>Submit & Proceed to Payment</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
