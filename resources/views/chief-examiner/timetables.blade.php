@extends('layouts.app')

@section('content')
<div class="space-y-6 py-4">

    <!-- Top Header Banner -->
    <div class="bg-gradient-to-r from-brand-950 via-brand-900 to-brand-800 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col lg:flex-row lg:items-center justify-between gap-6 border border-brand-800/40">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold text-brand-200 backdrop-blur-sm mb-3 border border-white/10">
                <svg class="w-3.5 h-3.5 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Academic Examination Scheduler</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Examination Timetable Builder</h1>
            <p class="text-brand-100 text-sm mt-1 max-w-2xl">
                Construct examination schedules, allocate venues and seating capacities, and publish timetable slots directly to students' portals in real-time.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" 
                    onclick="openScheduleModal()"
                    class="px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold text-sm shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2 group">
                <svg class="w-5 h-5 transition-transform group-hover:rotate-90 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Schedule Exam Paper</span>
            </button>
        </div>
    </div>

    <!-- Quick Metrics Overview -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Scheduled Papers</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $timetables->total() }}</span>
                <span class="text-xs font-semibold text-slate-500">entries</span>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Live to Students</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-emerald-700">{{ $publishedCount }}</span>
                <span class="text-xs font-semibold text-emerald-600">published</span>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Draft / Unpublished</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-amber-700">{{ $draftCount }}</span>
                <span class="text-xs font-semibold text-amber-600">drafts</span>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-600">Examination Venues</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-brand-700">{{ $distinctVenuesCount }}</span>
                <span class="text-xs font-semibold text-slate-500">locations</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('chief-examiner.timetables.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div class="relative flex-1 sm:w-72">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Search course code, title, venue..." 
                       class="w-full text-xs pl-9 pr-4 py-2.5 rounded-xl border border-slate-300 text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <select name="status" class="text-xs px-3 py-2.5 rounded-xl border border-slate-300 text-slate-700 bg-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500">
                <option value="">All Statuses</option>
                <option value="published" @selected($statusFilter === 'published')>Published Only</option>
                <option value="draft" @selected($statusFilter === 'draft')>Draft Only</option>
            </select>

            <button type="submit" class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-xs transition">
                Filter
            </button>

            @if($search || $statusFilter)
                <a href="{{ route('chief-examiner.timetables.index') }}" class="px-3 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                    Reset
                </a>
            @endif
        </form>

        <div class="text-xs text-slate-500 font-medium self-end md:self-auto">
            Showing {{ $timetables->firstItem() ?? 0 }} to {{ $timetables->lastItem() ?? 0 }} of {{ $timetables->total() }} entries
        </div>
    </div>

    <!-- Master Timetable List Table -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xs overflow-hidden">
        @if($timetables->isEmpty())
            <div class="p-16 text-center">
                <div class="w-16 h-16 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mx-auto mb-4 border border-brand-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">No Examination Papers Scheduled Yet</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Construct and publish timetable papers for the resit and supplementary examination session. Once published, students will immediately view them in their timetable tab.
                </p>
                <button type="button" 
                        onclick="openScheduleModal()"
                        class="mt-5 px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-sm transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Schedule First Exam Paper</span>
                </button>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500 border-b border-slate-200 tracking-wider">
                        <tr>
                            <th class="py-4 px-6">Course</th>
                            <th class="py-4 px-4">Exam Date &amp; Day</th>
                            <th class="py-4 px-4">Time Window</th>
                            <th class="py-4 px-4">Venue &amp; Room</th>
                            <th class="py-4 px-4">Capacity</th>
                            <th class="py-4 px-4">Student Visibility</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($timetables as $tt)
                            @php
                                $startTime = \Carbon\Carbon::parse($tt->start_time);
                                $endTime = \Carbon\Carbon::parse($tt->end_time);
                                $durationMinutes = $startTime->diffInMinutes($endTime);
                                $durationHours = round($durationMinutes / 60, 1);
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- Course Info -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-brand-50 text-brand-700 border border-brand-200/80">
                                            {{ $tt->course->course_code ?? 'N/A' }}
                                        </span>
                                        <span class="font-bold text-slate-900">{{ $tt->course->course_name ?? 'Course' }}</span>
                                    </div>
                                    <div class="text-xs text-slate-400 mt-1">
                                        {{ $tt->academic_year }} &bull; {{ $tt->semester }}
                                    </div>
                                </td>

                                <!-- Exam Date -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-900">
                                        {{ \Carbon\Carbon::parse($tt->exam_date)->format('D, M d, Y') }}
                                    </div>
                                    <div class="text-xs text-slate-400">
                                        {{ \Carbon\Carbon::parse($tt->exam_date)->diffForHumans() }}
                                    </div>
                                </td>

                                <!-- Time Window -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="font-semibold text-slate-800 text-xs">
                                        {{ $startTime->format('h:i A') }} &ndash; {{ $endTime->format('h:i A') }}
                                    </div>
                                    <span class="inline-block mt-0.5 text-[11px] font-semibold text-slate-500">
                                        {{ $durationHours }} hrs ({{ $durationMinutes }} mins)
                                    </span>
                                </td>

                                <!-- Venue -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 font-bold text-slate-900">
                                        <svg class="w-4 h-4 text-brand-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span>{{ $tt->venue }}</span>
                                    </div>
                                </td>

                                <!-- Capacity -->
                                <td class="py-4 px-4 whitespace-nowrap text-xs text-slate-600">
                                    <span class="inline-flex items-center gap-1 font-semibold bg-slate-100 px-2 py-1 rounded-lg">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        {{ $tt->seating_capacity }} seats
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if($tt->isPublished())
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            <span>Published (Live)</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                            <span>Draft (Hidden)</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <!-- One-Click Toggle Status Form -->
                                        <form method="POST" action="{{ route('chief-examiner.timetables.toggle-status', $tt->id) }}" class="inline">
                                            @csrf
                                            @if($tt->isPublished())
                                                <button type="submit" 
                                                        title="Unpublish (move to draft)"
                                                        class="px-2.5 py-1.5 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100 text-xs font-semibold transition flex items-center gap-1">
                                                    <span>Unpublish</span>
                                                </button>
                                            @else
                                                <button type="submit" 
                                                        title="Publish immediately to students"
                                                        class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1 shadow-xs">
                                                    <span>Publish</span>
                                                </button>
                                            @endif
                                        </form>

                                        <!-- Edit Button -->
                                        <button type="button" 
                                                onclick="openEditTimetableModal({{ json_encode($tt) }})"
                                                class="px-2.5 py-1.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-semibold transition">
                                            Edit
                                        </button>

                                        <!-- Delete Form -->
                                        <form method="POST" action="{{ route('chief-examiner.timetables.destroy', $tt->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this timetable paper for {{ $tt->course->course_code }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1.5 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition"
                                                    title="Delete timetable paper">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($timetables->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $timetables->links() }}
                </div>
            @endif
        @endif
    </div>

</div>

<!-- Schedule New Timetable Modal -->
<div id="scheduleModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative animate-in fade-in zoom-in-95 duration-200">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Schedule Examination Paper</h3>
                <p class="text-xs text-slate-500 mt-0.5">Construct timetable slot and allocate venue for students.</p>
            </div>
            <button type="button" onclick="closeScheduleModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('chief-examiner.timetables.store') }}" class="space-y-4 mt-5">
            @csrf
            
            <!-- Course Selection -->
            <div>
                <label for="sched-course-id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Course Paper *</label>
                <select id="sched-course-id" name="course_id" required class="w-full text-xs font-semibold px-4 py-3 rounded-xl border border-slate-300 text-slate-900 focus:ring-2 focus:ring-brand-500 bg-white">
                    <option value="">-- Select Examination Course --</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" @selected(old('course_id') == $c->id)>
                            [{{ $c->course_code }}] {{ $c->course_name }} ({{ $c->credit_hours ?? 3 }} Credits)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Academic Year & Session -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="sched-year" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Academic Year *</label>
                    <input id="sched-year" type="text" name="academic_year" value="{{ old('academic_year', '2026/2027') }}" required placeholder="e.g. 2026/2027"
                           class="w-full text-xs px-4 py-2.5 rounded-xl border border-slate-300 font-medium focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="sched-semester" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Exam Session *</label>
                    <input id="sched-semester" type="text" name="semester" value="{{ old('semester', 'Supplementary & Resit Session') }}" required placeholder="e.g. Supplementary & Resit Session"
                           class="w-full text-xs px-4 py-2.5 rounded-xl border border-slate-300 font-medium focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <!-- Date & Times -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label for="sched-date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Exam Date *</label>
                    <input id="sched-date" type="date" name="exam_date" value="{{ old('exam_date', now()->addDays(10)->format('Y-m-d')) }}" required 
                           class="w-full text-xs px-3 py-2.5 rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="sched-start" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Start Time *</label>
                    <input id="sched-start" type="time" name="start_time" value="{{ old('start_time', '09:00') }}" required 
                           class="w-full text-xs px-3 py-2.5 rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="sched-end" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">End Time *</label>
                    <input id="sched-end" type="time" name="end_time" value="{{ old('end_time', '12:00') }}" required 
                           class="w-full text-xs px-3 py-2.5 rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <!-- Venue & Capacity -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-2">
                    <label for="sched-venue" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Examination Venue / Hall *</label>
                    <input id="sched-venue" type="text" name="venue" list="venue-list" value="{{ old('venue', 'ICT Lab 1 (Main Campus)') }}" required placeholder="e.g. ICT Lab 1"
                           class="w-full text-xs px-4 py-2.5 rounded-xl border border-slate-300 font-medium focus:ring-2 focus:ring-brand-500">
                    <datalist id="venue-list">
                        <option value="ICT Lab 1 (Main Campus)">
                        <option value="ICT Lab 2 (Main Campus)">
                        <option value="Great Hall Room A">
                        <option value="Great Hall Room B">
                        <option value="Assembly Pavilion Lecture Hall">
                        <option value="Engineering Block Lab 3">
                        <option value="Science Complex Hall 2">
                    </datalist>
                </div>
                <div>
                    <label for="sched-capacity" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Seating Capacity *</label>
                    <input id="sched-capacity" type="number" name="seating_capacity" value="{{ old('seating_capacity', 50) }}" min="1" max="2000" required 
                           class="w-full text-xs px-4 py-2.5 rounded-xl border border-slate-300 font-medium focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <!-- Status Choice -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Publishing Status *</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-brand-300">
                        <input type="radio" name="status" value="published" checked class="text-brand-600 focus:ring-brand-500">
                        <span class="text-xs font-bold text-emerald-800">Publish Immediately</span>
                    </label>
                    <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-brand-300">
                        <input type="radio" name="status" value="draft" class="text-brand-600 focus:ring-brand-500">
                        <span class="text-xs font-medium text-slate-600">Save as Draft</span>
                    </label>
                </div>
                <p class="text-[11px] text-slate-500 mt-2">When set to "Publish", students will immediately see this timetable on their timetable tab and exam slips.</p>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-3">
                <button type="button" onclick="closeScheduleModal()" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition">
                    Save Timetable Entry
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Timetable Modal -->
<div id="editTimetableModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative animate-in fade-in zoom-in-95 duration-200">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Edit Examination Timetable Paper</h3>
                <p class="text-xs text-slate-500 mt-0.5">Update date, time window, venue or publication state.</p>
            </div>
            <button type="button" onclick="closeEditTimetableModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="editTimetableForm" method="POST" action="" class="space-y-4 mt-5">
            @csrf
            @method('PUT')
            
            <!-- Course Selection -->
            <div>
                <label for="edit-course-id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Course Paper *</label>
                <select id="edit-course-id" name="course_id" required class="w-full text-xs font-semibold px-4 py-3 rounded-xl border border-slate-300 text-slate-900 focus:ring-2 focus:ring-brand-500 bg-white">
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}">
                            [{{ $c->course_code }}] {{ $c->course_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Academic Year & Session -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="edit-year" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Academic Year *</label>
                    <input id="edit-year" type="text" name="academic_year" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-slate-300 font-medium focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="edit-semester" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Exam Session *</label>
                    <input id="edit-semester" type="text" name="semester" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-slate-300 font-medium focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <!-- Date & Times -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label for="edit-date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Exam Date *</label>
                    <input id="edit-date" type="date" name="exam_date" required class="w-full text-xs px-3 py-2.5 rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="edit-start" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Start Time *</label>
                    <input id="edit-start" type="time" name="start_time" required class="w-full text-xs px-3 py-2.5 rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="edit-end" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">End Time *</label>
                    <input id="edit-end" type="time" name="end_time" required class="w-full text-xs px-3 py-2.5 rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <!-- Venue & Capacity -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-2">
                    <label for="edit-venue" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Examination Venue / Hall *</label>
                    <input id="edit-venue" type="text" name="venue" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-slate-300 font-medium focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="edit-capacity" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Seating Capacity *</label>
                    <input id="edit-capacity" type="number" name="seating_capacity" min="1" max="2000" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-slate-300 font-medium focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <!-- Status Choice -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Publishing Status *</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-white cursor-pointer">
                        <input id="edit-status-published" type="radio" name="status" value="published" class="text-brand-600 focus:ring-brand-500">
                        <span class="text-xs font-bold text-emerald-800">Published (Live to Students)</span>
                    </label>
                    <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-white cursor-pointer">
                        <input id="edit-status-draft" type="radio" name="status" value="draft" class="text-brand-600 focus:ring-brand-500">
                        <span class="text-xs font-medium text-slate-600">Draft (Hidden)</span>
                    </label>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-3">
                <button type="button" onclick="closeEditTimetableModal()" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition">
                    Update Timetable Slot
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openScheduleModal() {
        document.getElementById('scheduleModal').classList.remove('hidden');
    }

    function closeScheduleModal() {
        document.getElementById('scheduleModal').classList.add('hidden');
    }

    function openEditTimetableModal(tt) {
        document.getElementById('editTimetableForm').action = '/chief-examiner/timetables/' + tt.id;
        document.getElementById('edit-course-id').value = tt.course_id;
        document.getElementById('edit-year').value = tt.academic_year;
        document.getElementById('edit-semester').value = tt.semester;
        
        // Format date and time
        const dateVal = tt.exam_date ? tt.exam_date.substring(0, 10) : '';
        document.getElementById('edit-date').value = dateVal;
        
        const startTimeVal = tt.start_time ? tt.start_time.substring(0, 5) : '09:00';
        const endTimeVal = tt.end_time ? tt.end_time.substring(0, 5) : '12:00';
        document.getElementById('edit-start').value = startTimeVal;
        document.getElementById('edit-end').value = endTimeVal;

        document.getElementById('edit-venue').value = tt.venue;
        document.getElementById('edit-capacity').value = tt.seating_capacity;

        if (tt.status === 'published') {
            document.getElementById('edit-status-published').checked = true;
        } else {
            document.getElementById('edit-status-draft').checked = true;
        }

        document.getElementById('editTimetableModal').classList.remove('hidden');
    }

    function closeEditTimetableModal() {
        document.getElementById('editTimetableModal').classList.add('hidden');
    }

    // Close on backdrop click
    window.addEventListener('click', function(e) {
        const schedModal = document.getElementById('scheduleModal');
        const editModal = document.getElementById('editTimetableModal');
        if (e.target === schedModal) closeScheduleModal();
        if (e.target === editModal) closeEditTimetableModal();
    });
</script>
@endsection
