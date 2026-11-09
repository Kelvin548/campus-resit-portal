@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-4 space-y-6">

    <!-- Top Breadcrumb & Quick Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('chief-examiner.dashboard') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-800 flex items-center gap-1.5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Back to Chief Examiner Dashboard</span>
        </a>

        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-50 text-xs font-semibold text-brand-700 border border-brand-200">
            <span>Official Student Dispatch System</span>
        </div>
    </div>

    <!-- Header Banner in Rich USTED Deep Wine Gradient -->
    <div class="bg-usted-wine-gradient rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-6" style="background: linear-gradient(135deg, #440a15 0%, #640f1f 45%, #7a1326 100%) !important; color: #ffffff !important;">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold text-brand-200 backdrop-blur-sm mb-3 border border-white/10">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Instant Multi-Channel Dispatch: Student Portals &amp; Registered Emails
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Student Broadcast System</h1>
            <p class="text-brand-100 text-sm mt-1 max-w-xl">
                Publish scheduling notices, emergency postponement bulletins, or exam times. Dispatches to every student's portal and sends real-time email notifications.
            </p>
        </div>

        <div class="bg-white/10 border border-white/20 rounded-2xl px-5 py-3 text-center shrink-0 self-start sm:self-center">
            <div class="text-3xl font-black text-white">{{ $broadcasts->count() }}</div>
            <div class="text-[11px] uppercase tracking-wider text-brand-200 font-semibold">Broadcasts Dispatched</div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-xs">
            <div class="font-bold mb-1">Please correct the following errors:</div>
            <ul class="list-disc list-inside space-y-0.5 text-xs">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Broadcast Composer Form Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-900">Compose New Broadcast Notice</h2>
            <p class="text-xs text-slate-500 mt-0.5">Fill in the details below to broadcast across all student dashboards and registered student emails</p>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('chief-examiner.broadcasts.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Notice Title *
                        </label>
                        <input type="text" name="title" required placeholder="e.g. Schedule Update: ICT231 Examination Postponed to Friday, 9:00 AM" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Notice Category *
                        </label>
                        <select name="category" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600">
                            <option value="general">General Announcement</option>
                            <option value="postponement">Postponement / Reschedule Notice</option>
                            <option value="exam_time">Exam Sitting Time Notice</option>
                            <option value="urgent">Urgent Academic Alert</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Detailed Message Body *
                    </label>
                    <textarea name="message" rows="4" required placeholder="Enter the exact details of the announcement, venue changes, postponement reasons, or revised paper dates and times..."
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600"></textarea>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2 border-t border-slate-100">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="send_email" value="1" checked class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                        <span class="text-xs font-semibold text-slate-700">Dispatch instant email notification to every registered student email address</span>
                    </label>

                    <button type="submit" 
                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-brand-900 via-brand-800 to-brand-700 hover:from-brand-950 hover:to-brand-800 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span>Publish &amp; Broadcast to All Students</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Broadcast History Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Broadcast Dispatch History</h2>
                <p class="text-xs text-slate-500 mt-0.5">All active and historical announcements visible to students</p>
            </div>
            <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-xl">
                {{ $broadcasts->count() }} Dispatched
            </span>
        </div>

        @if($broadcasts->isEmpty())
            <div class="text-center py-12 px-4">
                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900">No announcements sent yet</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Use the composer above to publish exam schedules, sitting times, or postponement alerts to students.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 uppercase font-semibold text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-6">Title &amp; Message</th>
                            <th class="py-3 px-4">Category</th>
                            <th class="py-3 px-4">Emails Sent</th>
                            <th class="py-3 px-4">Dispatched On</th>
                            <th class="py-3 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($broadcasts as $bc)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-6 max-w-md">
                                <div class="font-bold text-slate-900 text-sm">{{ $bc->title }}</div>
                                <div class="text-slate-600 mt-1 text-xs leading-relaxed">{{ $bc->message }}</div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($bc->category === 'postponement')
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">Postponement</span>
                                @elseif($bc->category === 'urgent')
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">Urgent Alert</span>
                                @elseif($bc->category === 'exam_time')
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200">Sitting Time</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-brand-50 text-brand-800 border border-brand-200">General</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap font-mono text-emerald-700 font-bold text-xs">
                                {{ $bc->emails_sent_count }} emails
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-400">
                                {{ $bc->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="py-3.5 px-6 text-right whitespace-nowrap">
                                <form method="POST" action="{{ route('chief-examiner.broadcasts.destroy', $bc) }}" onsubmit="return confirm('Remove this announcement from student portals?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg border border-rose-200 text-rose-700 bg-rose-50 hover:bg-rose-100 font-bold text-xs transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
