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
                                <span>⚠️ Postponement / Reschedule Notice</span>
                            </span>
                        @elseif($announcement->category === 'urgent')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-900 border border-rose-300">
                                <span>🚨 Urgent Academic Alert</span>
                            </span>
                        @elseif($announcement->category === 'exam_time')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-900 border border-blue-300">
                                <span>⏰ Exam Sitting Time Notice</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-800 border border-brand-200">
                                <span>📢 General Directorate Update</span>
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

</div>
@endsection
