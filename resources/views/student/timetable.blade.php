@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header & Search -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Official Examination Timetable</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Published schedules and venue allocations confirmed by the Chief Examiner</p>
        </div>

        <form method="GET" action="{{ route('student.timetable') }}" class="flex items-center gap-2">
            <div class="relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search course, venue..."
                       class="text-xs pl-8 pr-3 py-2 rounded-xl border border-slate-300 text-slate-700 bg-white focus:ring-2 focus:ring-brand-500 w-48 sm:w-64">
                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <button type="submit" class="px-3 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-xs">
                Search
            </button>
            @if($search)
                <a href="{{ route('student.timetable') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Timetable List / Table -->
    @if($timetables->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800">No published examination timetables</h3>
            <p class="text-xs text-slate-500 mt-1">The Chief Examiner has not published timetable schedules for this session yet. Please check back soon.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-6">Course</th>
                            <th class="py-3.5 px-4">Exam Date</th>
                            <th class="py-3.5 px-4">Time Window</th>
                            <th class="py-3.5 px-4">Venue & Room</th>
                            <th class="py-3.5 px-4">Seating</th>
                            <th class="py-3.5 px-6 text-right">My Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($timetables as $tt)
                            @php
                                $isMyCourse = in_array($tt->course_id, $registeredCourseIds);
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition {{ $isMyCourse ? 'bg-brand-50/20' : '' }}">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-brand-100 text-brand-800">{{ $tt->course->course_code }}</span>
                                        <span class="font-bold text-slate-900">{{ $tt->course->course_name }}</span>
                                    </div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $tt->academic_year }} • {{ $tt->semester }}</div>
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap font-semibold text-slate-900">
                                    {{ \Carbon\Carbon::parse($tt->exam_date)->format('D, M d, Y') }}
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap text-xs text-slate-700">
                                    {{ \Carbon\Carbon::parse($tt->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($tt->end_time)->format('h:i A') }}
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 font-medium text-slate-900">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $tt->venue }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap text-xs text-slate-500">
                                    {{ $tt->seating_capacity }} seats
                                </td>
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    @if($isMyCourse)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            ✓ Registered
                                        </span>
                                    @else
                                        @if($studentRegistrationOpen)
                                            <a href="{{ route('student.registerCourseForm', $tt->course_id) }}"
                                               class="text-xs font-semibold text-brand-600 hover:text-brand-700 hover:underline">
                                                Register for Course
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-500">Not registered</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
