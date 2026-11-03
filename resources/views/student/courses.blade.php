@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header & Search Toolbar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Available Resit Courses</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Explore courses offered for resit examination, verify fees and registration deadlines</p>
        </div>

        <!-- Filter & Search Form -->
        <form method="GET" action="{{ route('student.courses') }}" class="flex flex-wrap items-center gap-2">
            <select name="level" onchange="this.form.submit()" 
                    class="text-xs px-3 py-2 rounded-xl border border-slate-300 text-slate-700 bg-white focus:ring-2 focus:ring-brand-500">
                <option value="">All Levels</option>
                <option value="100" {{ $selectedLevel == '100' ? 'selected' : '' }}>Level 100</option>
                <option value="200" {{ $selectedLevel == '200' ? 'selected' : '' }}>Level 200</option>
                <option value="300" {{ $selectedLevel == '300' ? 'selected' : '' }}>Level 300</option>
                <option value="400" {{ $selectedLevel == '400' ? 'selected' : '' }}>Level 400</option>
            </select>

            <div class="relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search code or name..."
                       class="text-xs pl-8 pr-3 py-2 rounded-xl border border-slate-300 text-slate-700 bg-white focus:ring-2 focus:ring-brand-500 w-48 sm:w-64">
                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <button type="submit" class="px-3 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-xs">
                Filter
            </button>
            @if($selectedLevel || $search)
                <a href="{{ route('student.courses') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Courses Grid -->
    @if($courses->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800">No courses match your query</h3>
            <p class="text-xs text-slate-500 mt-1">Try resetting filters or searching with different keywords.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
                @php
                    $isOpen = $studentRegistrationOpen && $course->is_active;
                    $isRegistered = in_array($course->id, $registeredCourseIds);
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between hover:border-brand-300 transition overflow-hidden">
                    
                    <div class="p-6">
                        <!-- Top Badges -->
                        <div class="flex items-start justify-between gap-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200">
                                {{ $course->course_code }}
                            </span>

                            @if($isRegistered)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Registered
                                </span>
                            @elseif(!$isOpen)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                    Registration closed
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Open
                                </span>
                            @endif
                        </div>

                        <!-- Course Title -->
                        <h2 class="text-base font-bold text-slate-900 mt-3 line-clamp-2">
                            {{ $course->course_name }}
                        </h2>

                        <!-- Details Info Grid -->
                        <div class="mt-4 grid grid-cols-2 gap-2 text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <div>
                                <span class="text-slate-400 block">Level:</span>
                                <span class="font-semibold text-slate-800">Level {{ $course->level }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Credits:</span>
                                <span class="font-semibold text-slate-800">{{ $course->credit_hours }} Credit Hours</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Semester:</span>
                                <span class="font-semibold text-slate-800">{{ $course->semester }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Registration Fee:</span>
                                <span class="font-bold text-brand-700">GHS {{ number_format($course->registration_fee, 2) }}</span>
                            </div>
                        </div>

                        <!-- Global registration period notice -->
                        <div class="mt-4 text-xs text-slate-500 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            @if($registrationClosesAt)
                                <span>Registration closes: <strong class="text-slate-800">{{ $registrationClosesAt->format('M d, Y g:i A') }} Ghana time</strong></span>
                            @else
                                <span>Registration dates have not been scheduled</span>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="p-4 bg-slate-50/70 border-t border-slate-100">
                        @if($isRegistered)
                            <a href="{{ route('student.dashboard') }}" 
                               class="w-full py-2 px-3 rounded-xl bg-slate-200 text-slate-700 font-semibold text-xs flex items-center justify-center gap-1">
                                <span>View Status on Dashboard</span>
                            </a>
                        @elseif($isOpen)
                            <a href="{{ route('student.registerCourseForm', $course->id) }}" 
                               class="w-full py-2 px-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs shadow-xs transition flex items-center justify-center gap-1.5">
                                <span>Register for Supplementary Exam (GHS {{ number_format($course->registration_fee, 2) }})</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        @else
                            <button disabled 
                                    class="w-full py-2 px-3 rounded-xl bg-slate-100 text-slate-400 font-semibold text-xs cursor-not-allowed">
                                Registration Closed
                            </button>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
