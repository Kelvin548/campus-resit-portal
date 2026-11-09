@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-4 space-y-6">

    <!-- Top Breadcrumb -->
    <div class="flex items-center justify-between">
        <a href="{{ route('student.dashboard') }}" class="text-xs font-semibold text-brand-600 hover:underline flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Back to Dashboard</span>
        </a>
        <div class="text-xs text-slate-500">
            Candidate: <span class="font-bold text-slate-900">{{ $student->full_name }}</span> ({{ $student->student_id_number ?? 'N/A' }})
        </div>
    </div>

    <!-- Header Banner in Institutional Wine Gradient -->
    <div class="bg-usted-wine-gradient rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4" style="background: linear-gradient(135deg, #440a15 0%, #640f1f 45%, #7a1326 100%) !important; color: #ffffff !important;">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold text-brand-200 backdrop-blur-sm mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Official Notice Board</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Chief Examiner Broadcasts &amp; Updates</h1>
            <p class="text-brand-100 text-xs sm:text-sm mt-1 max-w-xl">
                Real-time official examination schedule notices, paper dates, timetable revisions, and postponement bulletins.
            </p>
        </div>

        <div class="bg-white/10 border border-white/20 rounded-2xl px-4 py-3 text-center self-start sm:self-center">
            <div class="text-2xl font-black text-white">{{ $announcements->count() }}</div>
            <div class="text-[11px] uppercase tracking-wider text-brand-200 font-semibold">Active Notices</div>
        </div>
    </div>

    <!-- Announcements Stream -->
    @if($announcements->isEmpty())
        <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-xs">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-900">No active broadcast notices</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">There are currently no new scheduling bulletins or urgent updates published by the Chief Examiner.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($announcements as $announcement)
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xs hover:shadow-md transition">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                    <div class="flex items-center gap-2">
                        @if($announcement->category === 'postponement')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                <span>Postponement / Reschedule Notice</span>
                            </span>
                        @elseif($announcement->category === 'urgent')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-900 border border-rose-300">
                                <span>Urgent Academic Alert</span>
                            </span>
                        @elseif($announcement->category === 'exam_time')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-900 border border-blue-300">
                                <span>Exam Sitting Time Notice</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-800 border border-brand-200">
                                <span>General Directorate Update</span>
                            </span>
                        @endif

                        <span class="text-xs text-slate-400">•</span>
                        <span class="text-xs text-slate-500 font-medium">By Prof. Kwame Mensah (Chief Examiner)</span>
                    </div>

                    <span class="text-xs text-slate-400 font-mono">
                        {{ $announcement->created_at->format('M d, Y • h:i A') }} ({{ $announcement->created_at->diffForHumans() }})
                    </span>
                </div>

                <h2 class="text-lg font-bold text-slate-900 mb-2">
                    {{ $announcement->title }}
                </h2>

                <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
                    {{ $announcement->message }}
                </div>
            </div>
            @endforeach
        </div>
    @endif

    <!-- Inquiries & Student Help Section -->
    <div class="mt-8 bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-brand-50 border border-brand-100 text-brand-700 flex items-center justify-center font-bold flex-shrink-0">
                    <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Need Help or Clarification Regarding Examination Notices?</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-xl leading-relaxed">
                        If you have questions about a published paper sitting time, venue change, or postponement notification, please reach out directly to the Chief Examination Officer’s Directorate or the Academic Affairs Examination Helpdesk.
                    </p>
                    <div class="mt-3 flex flex-wrap items-center gap-4 text-xs font-semibold text-brand-700">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>examinations@usted.edu.gh</span>
                        </span>
                        <span class="text-slate-300">•</span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>+233 24 000 0000</span>
                        </span>
                    </div>
                </div>
            </div>
            <a href="{{ route('student.timetable') }}" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs shadow-xs transition flex-shrink-0 text-center">
                Check Exam Timetable &rarr;
            </a>
        </div>
    </div>

</div>
@endsection
