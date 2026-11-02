@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6">

    <!-- Top Action Bar (hidden when printing) -->
    <div class="mb-6 flex items-center justify-between print:hidden">
        <a href="{{ route('student.dashboard') }}" class="text-xs font-semibold text-brand-600 hover:underline flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Back to Dashboard</span>
        </a>

        <button onclick="window.print()" 
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white hover:bg-slate-800 text-xs font-bold shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            <span>Print Examination Slip</span>
        </button>
    </div>

    <!-- Printable Slip Document -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 sm:p-12 print:border-none print:shadow-none print:p-0">
        
        <!-- Header / Letterhead -->
        <div class="border-b-2 border-slate-900 pb-6 mb-8 flex items-start justify-between">
            <div>
                <div class="text-xs uppercase tracking-widest font-extrabold text-brand-700">Official Academic Document</div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-1">RESIT EXAMINATION ADMISSION SLIP</h1>
                <p class="text-xs text-slate-500 mt-0.5">Academic Session: {{ $registration->academic_year }} • {{ $registration->semester }}</p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-brand-600 text-white flex items-center justify-center font-bold text-2xl shadow-sm print:border">
                CR
            </div>
        </div>

        <!-- Student Identification -->
        <div class="mb-8">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">1. Student Information</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100 text-xs">
                <div>
                    <span class="text-slate-400 block">Full Name:</span>
                    <span class="font-bold text-slate-900 text-sm">{{ $student->full_name }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block">Student Index ID:</span>
                    <span class="font-bold text-slate-900 text-sm">{{ $student->student_id_number ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block">Email Address:</span>
                    <span class="font-medium text-slate-800">{{ $student->email }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block">Phone Number:</span>
                    <span class="font-medium text-slate-800">{{ $student->phone_number ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Registered Course Details -->
        <div class="mb-8">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">2. Examination Course Details</h2>
            <div class="border border-slate-200 rounded-2xl overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-100 text-slate-600 uppercase font-semibold">
                        <tr>
                            <th class="py-3 px-4">Course Code</th>
                            <th class="py-3 px-4">Course Title</th>
                            <th class="py-3 px-4">Level</th>
                            <th class="py-3 px-4">Credits</th>
                            <th class="py-3 px-4 text-right">Fee Paid</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="py-3.5 px-4 font-bold text-slate-900">{{ $registration->course->course_code }}</td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $registration->course->course_name }}</td>
                            <td class="py-3.5 px-4">Level {{ $registration->course->level }}</td>
                            <td class="py-3.5 px-4">{{ $registration->course->credit_hours }} Credits</td>
                            <td class="py-3.5 px-4 text-right font-bold text-slate-900">GHS {{ number_format($registration->course->registration_fee, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Examination Schedule & Venue -->
        <div class="mb-8">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">3. Scheduled Date & Venue</h2>
            @if($timetable)
                <div class="p-5 rounded-2xl bg-emerald-50/70 border border-emerald-200 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <span class="text-emerald-700 block font-semibold">Exam Date:</span>
                        <span class="font-black text-slate-900 text-sm">{{ \Carbon\Carbon::parse($timetable->exam_date)->format('l, d F Y') }}</span>
                    </div>
                    <div>
                        <span class="text-emerald-700 block font-semibold">Time Window:</span>
                        <span class="font-bold text-slate-900 text-sm">{{ \Carbon\Carbon::parse($timetable->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($timetable->end_time)->format('h:i A') }}</span>
                    </div>
                    <div>
                        <span class="text-emerald-700 block font-semibold">Allocated Venue:</span>
                        <span class="font-black text-slate-900 text-sm">{{ $timetable->venue }}</span>
                    </div>
                </div>
            @else
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-500">
                    Timetable schedule for this course is currently being arranged by the Chief Examiner and will appear here upon publication.
                </div>
            @endif
        </div>

        <!-- Payment & Verification Receipt -->
        <div class="mb-8">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">4. Mobile Money Payment Verification</h2>
            @if($registration->payment)
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs">
                    <div>
                        <span class="text-slate-400 block">Transaction Ref:</span>
                        <span class="font-mono font-bold text-slate-900">{{ $registration->payment->transaction_reference }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Payment Method:</span>
                        <span class="font-semibold text-slate-800 uppercase">{{ $registration->payment->network }} {{ $registration->payment->payment_method }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Payment Status:</span>
                        <span class="font-bold text-emerald-600 uppercase">{{ $registration->payment->status }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Date Paid:</span>
                        <span class="font-medium text-slate-800">{{ $registration->payment->paid_at ? $registration->payment->paid_at->format('M d, Y h:i A') : 'N/A' }}</span>
                    </div>
                </div>
            @else
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800">
                    Payment pending. Please complete Mobile Money checkout on your dashboard.
                </div>
            @endif
        </div>

        <!-- Verification Signatures & Stamp Area -->
        <div class="pt-8 border-t border-slate-200 grid grid-cols-2 gap-8 text-xs text-slate-500">
            <div>
                <div class="border-b border-dashed border-slate-400 pb-12 mb-2"></div>
                <div class="font-bold text-slate-900">Candidate's Signature</div>
                <div>Date: ________________________</div>
            </div>
            <div>
                <div class="border-b border-dashed border-slate-400 pb-12 mb-2"></div>
                <div class="font-bold text-slate-900">Chief Examiner / Board Seal</div>
                <div>Date: {{ now()->format('M d, Y') }}</div>
            </div>
        </div>

        <!-- Instructions for candidate -->
        <div class="mt-8 pt-4 border-t border-slate-100 text-[11px] text-slate-400 space-y-1">
            <p><strong>Important Notice:</strong> Candidates must present this signed slip alongside a valid Student ID card at the examination hall 30 minutes before the scheduled start time.</p>
        </div>

    </div>

</div>
@endsection
