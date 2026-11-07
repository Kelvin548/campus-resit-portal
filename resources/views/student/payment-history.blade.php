@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs text-brand-700 font-semibold bg-brand-50 px-2.5 py-1 rounded-full mb-2 border border-brand-200">
                <span>Member 3 • Mobile Money Module</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Payment History & Receipts</h1>
            <p class="text-slate-500 text-sm mt-1">Review all your Mobile Money resit fee payments and view printable receipts.</p>
        </div>
        <a href="{{ route('student.dashboard') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-xs shadow-xs transition flex items-center gap-1.5 self-start">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Back to Dashboard</span>
        </a>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        @if($payments->isEmpty())
            <div class="p-12 text-center">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-xl">
                    💳
                </div>
                <h3 class="text-sm font-bold text-slate-800">No payment records found</h3>
                <p class="text-xs text-slate-500 mt-1">You have not initiated any Mobile Money payments yet.</p>
                <a href="{{ route('student.courses') }}" class="mt-4 inline-block px-4 py-2 rounded-xl bg-brand-600 text-white font-semibold text-xs hover:bg-brand-700 transition">
                    Browse Courses to Register
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-6">Transaction Ref</th>
                            <th class="py-3 px-4">Course</th>
                            <th class="py-3 px-4">Network / Mobile</th>
                            <th class="py-3 px-4">Amount</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Date Authorized</th>
                            <th class="py-3 px-6 text-right">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($payments as $p)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 font-mono font-bold text-xs text-slate-900">
                                {{ $p->transaction_reference }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="font-bold text-slate-800">{{ $p->registration->course->course_code ?? 'Course' }}</span>
                                <div class="text-xs text-slate-400">{{ $p->registration->course->course_name ?? '' }}</div>
                            </td>
                            <td class="py-4 px-4 text-xs">
                                <span class="font-semibold text-slate-800">{{ $p->network }}</span>
                                <div class="text-slate-400 font-mono">{{ $p->phone_number }}</div>
                            </td>
                            <td class="py-4 px-4 font-bold text-slate-900 whitespace-nowrap">
                                GHS {{ number_format($p->amount, 2) }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($p->isSuccessful())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Successful
                                    </span>
                                @elseif($p->isPending())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Failed
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $p->paid_at ? $p->paid_at->format('M d, Y • h:i A') : $p->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                @if($p->isSuccessful())
                                    <a href="{{ route('student.payments.receipt', $p) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-brand-700 bg-brand-50 hover:bg-brand-100 border border-brand-200 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span>Receipt</span>
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 italic">No receipt</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="p-4 border-t border-slate-200">
                    {{ $payments->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
