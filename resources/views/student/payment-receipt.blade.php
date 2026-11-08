@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">
    
    <!-- Top Action Toolbar (Hidden during print) -->
    <div class="mb-6 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 print:hidden">
        <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Back to Dashboard</span>
        </a>

        <div class="flex flex-wrap items-center gap-2">
            <button type="button" onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition flex items-center gap-2">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Download / Print Receipt (PDF)</span>
            </button>
            <a href="{{ route('student.examSlip', $registration->id) }}" class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                <span>View Exam Slip</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- Payment Confirmed Banner -->
    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs flex items-center gap-3 shadow-xs print:hidden">
        <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div>
            <div class="font-bold text-sm text-emerald-950">Payment Confirmed &amp; Registration Cleared!</div>
            <div class="text-emerald-800 mt-0.5">Your Mobile Money payment of <strong>GHS {{ number_format($payment->amount, 2) }}</strong> has been verified. Your resit registration is complete and your examination admission slip is now available.</div>
        </div>
    </div>

    <!-- Official Printable Receipt Card -->
    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-sm relative overflow-hidden print:border-none print:shadow-none print:p-0">
        
        <!-- Watermark / Paid Stamp -->
        <div class="absolute right-6 top-8 sm:right-10 sm:top-10 rotate-[-12deg] pointer-events-none select-none">
            <div class="border-4 border-emerald-600/30 text-emerald-600/40 font-black text-2xl sm:text-3xl uppercase tracking-widest px-4 py-1.5 rounded-xl border-dashed">
                PAID &amp; CONFIRMED
            </div>
        </div>

        <!-- Receipt Header with University Logo -->
        <div class="border-b border-slate-200 pb-6 mb-6">
            <div class="flex items-center gap-3.5 mb-2">
                <div class="flex-shrink-0 bg-white p-1 rounded-xl border border-slate-200/80 shadow-xs">
                    <img src="{{ asset('images/usted-logo.jpg') }}" alt="USTED Logo" class="h-14 w-auto object-contain">
                </div>
                <div>
                    <h1 class="text-base sm:text-lg font-black text-slate-900 tracking-tight uppercase leading-snug">
                        University of Skills Training and Entrepreneurial Development
                    </h1>
                    <p class="text-xs font-bold text-brand-700 uppercase tracking-wider mt-0.5">
                        Campus Resit &amp; Supplementary Examination Portal &bull; Official Payment Receipt
                    </p>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap justify-between items-end gap-2 text-xs">
                <div>
                    <span class="text-slate-400">Receipt No:</span>
                    <span class="font-mono font-bold text-slate-800 ml-1">RCP-{{ $payment->id }}-{{ date('Ymd', strtotime($payment->paid_at ?? now())) }}</span>
                </div>
                <div>
                    <span class="text-slate-400">Date Issued:</span>
                    <span class="font-semibold text-slate-800 ml-1">{{ $payment->paid_at ? $payment->paid_at->format('M d, Y • h:i A') : now()->format('M d, Y • h:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Student & Payer Information -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-100 text-xs">
            <div>
                <span class="text-slate-400 block uppercase font-semibold text-[10px]">Student Details</span>
                <div class="font-bold text-slate-900 text-sm mt-0.5">{{ $student->full_name }}</div>
                <div class="text-slate-600 mt-0.5">Index Number: <span class="font-semibold text-slate-800">{{ $student->student_id_number ?? 'N/A' }}</span></div>
                <div class="text-slate-600">{{ $student->email }}</div>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-semibold text-[10px]">Payment Source</span>
                <div class="font-bold text-slate-900 text-sm mt-0.5">{{ $payment->network }} Mobile Money</div>
                <div class="text-slate-600 mt-0.5">Authorized Number: <span class="font-semibold text-slate-800">{{ $payment->phone_number }}</span></div>
                <div class="text-slate-600">Channel: <span class="font-semibold uppercase text-slate-800">{{ $payment->payment_method }}</span></div>
            </div>
        </div>

        <!-- Transaction Breakdown Table -->
        <div class="mb-6">
            <h2 class="text-xs uppercase font-bold text-slate-400 tracking-wider mb-2">Item Description</h2>
            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-100 text-slate-600 font-bold border-b border-slate-200">
                        <tr>
                            <th class="py-2.5 px-4">Course Code & Title</th>
                            <th class="py-2.5 px-3 text-center">Academic Term</th>
                            <th class="py-2.5 px-4 text-right">Fee (GHS)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-800">
                        <tr>
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-900">{{ $course->course_code }}</span> - {{ $course->course_name }}
                                <div class="text-[11px] text-slate-400">{{ $course->credit_hours }} Credit Hours • Level {{ $course->level }}</div>
                            </td>
                            <td class="py-3 px-3 text-center">
                                {{ $registration->academic_year }}<br>
                                <span class="text-[11px] text-slate-400">{{ $registration->semester }}</span>
                            </td>
                            <td class="py-3 px-4 text-right font-bold text-slate-900">
                                {{ number_format($payment->amount, 2) }}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-slate-50 font-bold border-t border-slate-200 text-slate-900">
                        <tr>
                            <td colspan="2" class="py-3 px-4 text-right text-xs uppercase">Total Paid Amount:</td>
                            <td class="py-3 px-4 text-right text-base text-emerald-700">
                                GHS {{ number_format($payment->amount, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Technical Gateway Verification Metadata -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-[11px] space-y-1.5 font-mono text-slate-600 mb-6">
            <div class="flex justify-between">
                <span>Transaction Reference:</span>
                <span class="font-bold text-slate-900">{{ $payment->transaction_reference }}</span>
            </div>
            <div class="flex justify-between">
                <span>Gateway Reference:</span>
                <span>{{ $payment->gateway_reference ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between">
                <span>Network Auth Code:</span>
                <span>{{ $payment->gateway_response['authorization_code'] ?? 'MOMO-AUTH-OK' }}</span>
            </div>
            <div class="flex justify-between">
                <span>Verification Status:</span>
                <span class="text-emerald-700 font-bold uppercase">Success (Captured)</span>
            </div>
        </div>

        <!-- Receipt Footer Note -->
        <div class="border-t border-slate-200 pt-4 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-400 gap-2">
            <div>
                This is a computer-generated receipt confirming Mobile Money settlement.
            </div>
            <div class="font-semibold text-slate-600">
                Member 3: Mobile Money Integration
            </div>
        </div>
    </div>
</div>
@endsection
