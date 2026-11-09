@extends('layouts.app')

@section('content')
<div class="space-y-8 py-2">
    
    <!-- Welcome Header Banner -->
    <div class="bg-usted-wine-gradient rounded-3xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6" style="background: linear-gradient(135deg, #440a15 0%, #640f1f 45%, #7a1326 100%) !important; color: #ffffff !important;">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-medium text-brand-100 backdrop-blur-sm mb-3">
                <span class="w-2 h-2 rounded-full {{ $studentRegistrationOpen ? 'bg-emerald-400 animate-pulse' : 'bg-amber-400' }}"></span>
                Application Window: {{ $studentRegistrationOpen ? 'Open & Active' : 'Closed & Unavailable' }}
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Welcome, {{ $student->full_name }}!</h1>
            <p class="text-brand-100 text-sm mt-1 max-w-xl">
                Student Index: <span class="font-semibold text-white font-mono">{{ $student->student_id_number ?? 'STU-PENDING' }}</span> • 
                Department: <span class="font-semibold text-white">ICT Education</span>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if($studentRegistrationOpen)
                <a href="{{ route('student.resit-form', ['type' => 'resit']) }}" 
                   class="px-4 py-2.5 rounded-xl bg-white text-brand-900 font-bold text-xs hover:bg-brand-50 shadow-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Register Resit Course</span>
                </a>
                <a href="{{ route('student.resit-form', ['type' => 'supplementary']) }}" 
                   class="px-4 py-2.5 rounded-xl bg-brand-800/80 hover:bg-brand-800 text-white font-medium text-xs border border-brand-500 transition flex items-center gap-2">
                    <span>Supplementary Request</span>
                </a>
            @else
                <span class="px-3.5 py-2 rounded-xl bg-black/20 text-brand-200 border border-white/10 text-xs font-semibold">
                    Applications Currently Closed
                </span>
            @endif
            <a href="{{ route('student.timetable') }}" 
               class="px-4 py-2.5 rounded-xl bg-brand-800/60 hover:bg-brand-800 text-white font-medium text-xs border border-brand-600 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Timetable</span>
            </a>
            <a href="{{ route('student.announcements') }}" 
               class="px-4 py-2.5 rounded-xl bg-brand-800/80 hover:bg-brand-800 text-white font-medium text-xs border border-brand-500 transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span>Notices &amp; Updates</span>
                @if(isset($announcements) && $announcements->isNotEmpty())
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                @endif
            </a>
        </div>
    </div>

    <!-- Latest Broadcast Notices from Chief Examiner -->
    @if(isset($announcements) && $announcements->isNotEmpty())
        <div class="space-y-3">
            @foreach($announcements->take(3) as $announcement)
            <div class="p-5 rounded-3xl bg-gradient-to-r from-brand-50 via-white to-brand-50 border-2 border-brand-200 shadow-xs relative overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-brand-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-600 animate-ping"></span>
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-900">
                            Official Chief Examiner Announcement
                        </span>
                        @if($announcement->category === 'postponement')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Postponement</span>
                        @elseif($announcement->category === 'urgent')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">Urgent</span>
                        @elseif($announcement->category === 'exam_time')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800">Sitting Time</span>
                        @endif
                    </div>
                    <div class="text-[11px] text-slate-500">
                        Posted {{ $announcement->created_at->diffForHumans() }}
                    </div>
                </div>

                <div class="pt-3">
                    <h3 class="text-base font-bold text-slate-900">{{ $announcement->title }}</h3>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 line-clamp-2 leading-relaxed">
                        {{ $announcement->message }}
                    </p>
                    <div class="mt-3 flex items-center justify-between pt-2 border-t border-brand-100/60">
                        <span class="text-[11px] text-slate-400">Chief Examiner: Prof. Kwame Mensah</span>
                        <a href="{{ route('student.announcements') }}" class="text-xs font-bold text-brand-700 hover:text-brand-900 hover:underline flex items-center gap-1">
                            <span>Read full notice &amp; all updates &rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    <!-- Supplementary Examination Decisions & Rejection Alerts -->
    @if(isset($rejectedSupplementary) && $rejectedSupplementary)
        <div class="p-6 rounded-3xl bg-rose-50 border-2 border-rose-300 text-rose-950 shadow-md">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-rose-200 text-rose-900 mb-1">
                            <span>Supplementary Application Rejected</span>
                        </div>
                        <h3 class="text-base font-bold text-rose-950">
                            {{ $rejectedSupplementary->rejection_reason ?? "Your doctor's medical report or justification letter is unacceptable. Please go back to register for a standard resit examination." }}
                        </h3>
                        <p class="text-xs text-rose-800 mt-1 leading-relaxed">
                            The Chief Examiner has inspected your uploaded documents. Because this supplementary application was not granted exemption, you are required to register for standard resits.
                        </p>
                    </div>
                </div>

                @if($studentRegistrationOpen)
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('student.resit-form', ['type' => 'resit']) }}" 
                       class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-xs shadow-md transition flex items-center justify-center gap-2">
                        <span>Go Back &amp; Register Resits</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
                @endif
            </div>
        </div>
    @endif

    @if(isset($approvedSupplementary) && $approvedSupplementary)
        <div class="p-5 rounded-3xl bg-emerald-50 border border-emerald-300 text-emerald-950 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <div class="font-extrabold text-sm text-emerald-900">Supplementary Examination Application Verified &amp; Approved!</div>
                    <div class="text-xs text-emerald-800 mt-0.5">
                        The Chief Examiner has verified your medical report and excuse letter. You are cleared to write your supplementary examinations without fee payment.
                    </div>
                </div>
            </div>
            <a href="{{ route('student.timetable') }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition shrink-0 self-start sm:self-auto">
                Check Exam Timetable
            </a>
        </div>
    @endif

    <!-- Closed Window Notification Notice -->
    @if(! $studentRegistrationOpen)
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-sm flex items-start gap-3 shadow-2xs">
            <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <div class="font-bold">Resit applications are currently closed. Please check back later.</div>
                <div class="text-xs text-amber-700 mt-0.5">The Chief Examiner has currently paused new submissions. You can still review past registrations, verified Mobile Money payment receipts, and print examination admission slips.</div>
            </div>
        </div>
    @endif

    <!-- Summary Stats Overview -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Registered</div>
            <div class="mt-2 text-3xl font-extrabold text-slate-900">{{ $totalRegistered }}</div>
            <div class="mt-1 text-xs text-slate-400">Total course submissions</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Pending Payment</div>
            <div class="mt-2 text-3xl font-extrabold text-amber-600">{{ $pendingPaymentCount }}</div>
            <div class="mt-1 text-xs text-slate-400">Requires Mobile Money payment</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Paid / Processing</div>
            <div class="mt-2 text-3xl font-extrabold text-blue-600">{{ $paidCount }}</div>
            <div class="mt-1 text-xs text-slate-400">In Chief Examiner Review Queue</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Approved &amp; Ready</div>
            <div class="mt-2 text-3xl font-extrabold text-emerald-600">{{ $approvedCount }}</div>
            <div class="mt-1 text-xs text-slate-400">Admission slips ready</div>
        </div>
    </div>

    <!-- Active Registered Courses List -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900">My Registered Resit Courses</h2>
                <p class="text-xs text-slate-500 mt-0.5">Track your submissions, fees, payment verifications, and approvals</p>
            </div>
            @if($studentRegistrationOpen)
            <a href="{{ route('student.resit-form') }}" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Register Another Course</span>
            </a>
            @endif
        </div>

        @if($registrations->isEmpty())
            <div class="text-center py-16 px-4">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-800">No course registrations yet</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">You have not registered for any resit courses this semester.</p>

                @if($studentRegistrationOpen)
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('student.resit-form') }}" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Apply for Resit Examination</span>
                    </a>
                </div>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-6">Course</th>
                            <th class="py-3.5 px-4">Academic Session</th>
                            <th class="py-3.5 px-4">Fee</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Payment Ref</th>
                            <th class="py-3.5 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($registrations as $reg)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $reg->course->course_code }}</div>
                                <div class="text-xs text-slate-500">{{ $reg->course->course_name }} ({{ $reg->course->credit_hours }} Credits)</div>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="font-medium text-slate-800">{{ $reg->academic_year }}</span>
                                <div class="text-xs text-slate-400">{{ $reg->semester }}</div>
                            </td>
                            <td class="py-4 px-4 font-semibold text-slate-900 whitespace-nowrap">
                                GHS {{ number_format($reg->course->registration_fee, 2) }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($reg->status === 'pending_payment')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Pending Payment
                                    </span>
                                @elseif($reg->status === 'approved' || $reg->status === 'paid')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Confirmed &amp; Cleared
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-xs">
                                @if($reg->payment)
                                    <span class="font-mono text-slate-700 font-semibold">{{ $reg->payment->transaction_reference }}</span>
                                    <div class="text-slate-400 capitalize">{{ $reg->payment->network }} • {{ $reg->payment->status }}</div>
                                @else
                                    <span class="text-slate-400 italic">Not paid</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                @if($reg->status === 'pending_payment')
                                    @if($studentRegistrationOpen)
                                        <button type="button" 
                                                onclick="openPayModal('{{ $reg->id }}', '{{ $reg->course->course_code }}', '{{ number_format($reg->course->registration_fee, 2) }}')"
                                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 shadow-xs transition">
                                            <span>Pay with MoMo</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </button>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Applications closed</span>
                                    @endif
                                @elseif($reg->status === 'approved' || $reg->status === 'paid')
                                    <div class="inline-flex items-center gap-2">
                                        @if($reg->payment)
                                            <a href="{{ route('student.payments.receipt', $reg->payment) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition">
                                                <span>Receipt</span>
                                            </a>
                                        @endif
                                        <a href="{{ route('student.examSlip', $reg->id) }}" 
                                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-brand-700 bg-brand-50 hover:bg-brand-100 border border-brand-200 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span>Print Exam Slip</span>
                                        </a>
                                    </div>
                                            <span>Exam Slip</span>
                                        </a>
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-2">
                                        <span class="text-xs text-rose-700 font-semibold">Rejected</span>
                                        @if($studentRegistrationOpen)
                                            <a href="{{ route('student.resit-form', ['type' => 'resit']) }}" 
                                               class="px-2.5 py-1 rounded-lg text-xs font-bold bg-brand-50 text-brand-700 hover:bg-brand-100 border border-brand-200">
                                                Register Resit
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Supplementary Exam Requests Section -->
    @if(isset($supplementaryRequests) && $supplementaryRequests->isNotEmpty())
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200 mb-1">
                    <span>Medical &amp; Extenuating Requests</span>
                </div>
                <h2 class="text-lg font-bold text-slate-900">My Supplementary Examination Submissions</h2>
                <p class="text-xs text-slate-500 mt-0.5">Documents submitted for exemption review by the Chief Examiner</p>
            </div>
            @if($studentRegistrationOpen)
            <a href="{{ route('student.resit-form', ['type' => 'supplementary']) }}" class="px-4 py-2 rounded-xl bg-purple-700 hover:bg-purple-800 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5 self-start sm:self-auto">
                <span>Submit Another Request</span>
            </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">Submitted Courses</th>
                        <th class="py-3.5 px-4">Doctor's Medical Report</th>
                        <th class="py-3.5 px-4">Justification Letter</th>
                        <th class="py-3.5 px-4">Review Status</th>
                        <th class="py-3.5 px-6 text-right">Date Filed</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($supplementaryRequests as $sReq)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-4 px-6">
                            @if(!empty($sReq->course_entries) && is_array($sReq->course_entries))
                                <div class="space-y-1">
                                    @foreach($sReq->course_entries as $c)
                                        <div class="font-bold text-slate-900 text-xs">{{ $c }}</div>
                                    @endforeach
                                </div>
                            @else
                                <span class="font-bold text-slate-900">{{ $sReq->paper_count }} Paper(s)</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap text-xs">
                            @if($sReq->medical_report_path)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Medical Report Attached</span>
                                </span>
                            @else
                                <span class="text-slate-400 italic">No report</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap text-xs">
                            @if($sReq->letter_path)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 font-semibold">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Formal Letter Attached</span>
                                </span>
                            @else
                                <span class="text-slate-400 italic">No letter</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            @if($sReq->status === 'approved')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Approved by Chief Examiner
                                </span>
                            @elseif($sReq->status === 'rejected')
                                <div>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Application Rejected
                                    </span>
                                    @if($sReq->rejection_reason)
                                        <p class="text-[11px] text-rose-800 mt-1 max-w-xs">{{ $sReq->rejection_reason }}</p>
                                    @endif
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Awaiting Examiner Review
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right whitespace-nowrap text-xs text-slate-400">
                            {{ $sReq->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Scheduled Examination Timetables for Registered Courses -->
    @if($upcomingTimetables->isNotEmpty())
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Published Exam Schedule for Your Courses</h2>
                <p class="text-xs text-slate-500 mt-0.5">Assigned and verified by the Chief Examiner</p>
            </div>
            <a href="{{ route('student.timetable') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">View Master Schedule &rarr;</a>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($upcomingTimetables as $tt)
            <div class="p-4 rounded-2xl border border-brand-200 bg-brand-50/40 relative">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="inline-block px-2 py-0.5 rounded text-xs font-bold bg-brand-600 text-white font-mono">{{ $tt->course->course_code }}</span>
                        <h3 class="font-bold text-slate-900 mt-2 text-sm">{{ $tt->course->course_name }}</h3>
                    </div>
                </div>
                <div class="mt-4 space-y-1.5 text-xs text-slate-600 border-t border-brand-100 pt-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-semibold text-slate-900">{{ \Carbon\Carbon::parse($tt->exam_date)->format('l, d M Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($tt->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($tt->end_time)->format('h:i A') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="font-medium text-slate-800">Venue: {{ $tt->venue }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

@if($studentRegistrationOpen)
<!-- Mobile Money Payment Modal -->
<div id="payModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl border border-slate-200">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Mobile Money Resit Payment</h3>
                <p class="text-xs text-slate-500">Official USTED MoMo Payment Gateway</p>
            </div>
            <button type="button" onclick="closePayModal()" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
        </div>

        <form id="paymentForm" method="POST" action="" class="space-y-4 mt-4">
            @csrf
            <div>
                <div class="text-xs text-slate-500">Course for Resit:</div>
                <div id="modalCourseCode" class="text-base font-bold text-slate-900 font-mono"></div>
            </div>

            <div class="p-3.5 rounded-2xl bg-brand-50 border border-brand-200 flex justify-between items-center">
                <span class="text-xs font-semibold text-brand-800">Amount Due:</span>
                <span class="text-xl font-extrabold text-brand-900">GHS <span id="modalFee">0.00</span></span>
            </div>

            <div>
                <label for="network" class="block text-xs font-bold text-slate-700 uppercase mb-1">Select Network</label>
                <select name="network" id="network" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600">
                    <option value="MTN">MTN Mobile Money</option>
                    <option value="Telecel">Telecel Cash (Vodafone)</option>
                    <option value="AT">AT Money (AirtelTigo)</option>
                </select>
            </div>

            <div>
                <label for="phone_number" class="block text-xs font-bold text-slate-700 uppercase mb-1">Mobile Money Number</label>
                <input type="tel" name="phone_number" id="phone_number" value="{{ $student->phone_number ?? '0244123456' }}" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-medium focus:ring-2 focus:ring-brand-600">
                <p class="text-[11px] text-slate-400 mt-1">Prompt will be routed to your mobile device.</p>
            </div>

            <div class="pt-2 flex items-center justify-end gap-3">
                <button type="button" onclick="closePayModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
                    Approve &amp; Pay Now
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPayModal(registrationId, courseCode, fee) {
        document.getElementById('modalCourseCode').innerText = courseCode;
        document.getElementById('modalFee').innerText = fee;
        document.getElementById('paymentForm').action = '/student/registrations/' + registrationId + '/pay';
        document.getElementById('payModal').classList.remove('hidden');
    }

    function closePayModal() {
        document.getElementById('payModal').classList.add('hidden');
    }
</script>
@endif
@endsection
