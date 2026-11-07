@extends('layouts.app')

@section('content')
<div class="space-y-8 py-4">
    
    <!-- Top Welcome Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-brand-950 to-brand-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold text-brand-200 backdrop-blur-sm mb-3">
                <span class="w-2 h-2 rounded-full {{ $registrationOpen ? 'bg-emerald-400 animate-pulse' : 'bg-amber-400' }}"></span>
                Registration Window: {{ $registrationOpen ? 'Open & Active' : 'Closed' }}
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Chief Examiner Academic Hub</h1>
            <p class="text-brand-100 text-sm mt-1 max-w-xl">
                {{ auth()->user()->full_name }} • Manage course registrations, review verified Mobile Money payments, and grant examination approvals.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('chief-examiner.cycle-settings.edit') }}" 
               class="px-4 py-2.5 rounded-xl bg-white text-slate-900 font-bold text-xs hover:bg-brand-50 shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Cycle Dates & Settings</span>
            </a>
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

    @if(session('info'))
        <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-800 text-sm flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-semibold">{{ session('info') }}</span>
        </div>
    @endif

    <!-- 4 Stats Overview Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Resit Courses</div>
            <div class="mt-2 text-3xl font-extrabold text-slate-900">{{ $activeCoursesCount }}</div>
            <div class="mt-1 text-xs text-slate-400">Offered this supplementary cycle</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-amber-200 bg-amber-50/20 shadow-xs">
            <div class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Paid • Awaiting Approval</div>
            <div class="mt-2 text-3xl font-extrabold text-amber-600">{{ $pendingApprovalCount }}</div>
            <div class="mt-1 text-xs text-amber-700">Requires Chief Examiner review</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-emerald-200 bg-emerald-50/20 shadow-xs">
            <div class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Approved Registrations</div>
            <div class="mt-2 text-3xl font-extrabold text-emerald-600">{{ $approvedCount }}</div>
            <div class="mt-1 text-xs text-emerald-700">Admission slips generated</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total MoMo Fees Verified</div>
            <div class="mt-2 text-3xl font-extrabold text-slate-900">GHS {{ number_format($totalFeesCollected, 2) }}</div>
            <div class="mt-1 text-xs text-slate-400">Credited via Mobile Money</div>
        </div>
    </div>

    <!-- Student Registration Approval Queue (The Main Examiner Task) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 mb-1">
                    <span>Action Required</span>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Student Resit Registrations Awaiting Approval</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Students in this list have completed their Mobile Money payment. Review their course details and grant academic clearance.
                </p>
            </div>
            <div class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-xl">
                {{ $pendingApprovals->count() }} Pending Applications
            </div>
        </div>

        @if($pendingApprovals->isEmpty())
            <div class="text-center py-16 px-4">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">All student registrations are up to date!</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    There are currently no new paid student resit applications waiting for approval.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-6">Student Details</th>
                            <th class="py-3.5 px-4">Course Applied</th>
                            <th class="py-3.5 px-4">Fee Paid</th>
                            <th class="py-3.5 px-4">MoMo Transaction</th>
                            <th class="py-3.5 px-4">Payment Time</th>
                            <th class="py-3.5 px-6 text-right">Approval Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($pendingApprovals as $reg)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $reg->user->full_name }}</div>
                                <div class="text-xs text-slate-500 font-mono">Index: {{ $reg->user->student_id_number ?? 'N/A' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $reg->user->email }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-block px-2 py-0.5 rounded text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200 mb-1">
                                    {{ $reg->course->course_code }}
                                </span>
                                <div class="font-medium text-slate-800 text-xs">{{ $reg->course->course_name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $reg->academic_year }} • {{ $reg->semester }}</div>
                            </td>
                            <td class="py-4 px-4 font-bold text-emerald-700 whitespace-nowrap">
                                GHS {{ number_format($reg->course->registration_fee, 2) }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-xs">
                                @if($reg->payment)
                                    <div class="font-mono font-semibold text-slate-800">{{ $reg->payment->transaction_reference }}</div>
                                    <div class="text-[11px] text-slate-500 uppercase">{{ $reg->payment->network }} • {{ $reg->payment->phone_number }}</div>
                                @else
                                    <span class="text-slate-400 italic">No record</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-xs text-slate-500">
                                {{ $reg->updated_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <form method="POST" action="{{ route('chief-examiner.registrations.reject', $reg) }}">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to reject this registration?')"
                                                class="px-3 py-1.5 rounded-xl border border-rose-200 text-rose-700 bg-rose-50 hover:bg-rose-100 text-xs font-semibold transition">
                                            Reject
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('chief-examiner.registrations.approve', $reg) }}">
                                        @csrf
                                        <button type="submit" 
                                                class="px-4 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span>Approve Registration</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Recently Approved Registrations -->
    @if($processedRegistrations->isNotEmpty())
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-900">Recently Processed Student Decisions</h2>
            <p class="text-xs text-slate-500 mt-0.5">Historical log of registrations approved or rejected by the Chief Examiner</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 uppercase font-semibold text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-6">Student</th>
                        <th class="py-3 px-4">Course</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Payment Ref</th>
                        <th class="py-3 px-6 text-right">Processed On</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($processedRegistrations as $item)
                    <tr>
                        <td class="py-3.5 px-6 font-semibold text-slate-800">
                            {{ $item->user->full_name }} ({{ $item->user->student_id_number }})
                        </td>
                        <td class="py-3.5 px-4 font-mono">
                            {{ $item->course->course_code }}
                        </td>
                        <td class="py-3.5 px-4">
                            @if($item->status === 'approved')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Approved
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Rejected
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 font-mono text-slate-500">
                            {{ $item->payment->transaction_reference ?? 'N/A' }}
                        </td>
                        <td class="py-3.5 px-6 text-right text-slate-400">
                            {{ $item->updated_at->format('M d, Y h:i A') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Supplementary Course Catalogue Reference -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Supplementary Course Catalogue</h2>
                <p class="text-xs text-slate-500 mt-0.5">Approved resit course listings and scheduled registration fees</p>
            </div>
            <span class="text-xs font-semibold text-brand-600 bg-brand-50 px-3 py-1 rounded-full border border-brand-200">
                {{ $courses->count() }} Offered Courses
            </span>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($courses as $c)
            <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:border-brand-300 transition shadow-xs">
                <div class="flex items-start justify-between">
                    <span class="px-2 py-0.5 rounded text-xs font-bold bg-brand-600 text-white font-mono">{{ $c->course_code }}</span>
                    <span class="text-xs font-semibold text-slate-500">{{ $c->credit_hours }} Credits</span>
                </div>
                <h3 class="font-bold text-slate-900 mt-2 text-sm line-clamp-1">{{ $c->course_name }}</h3>
                <div class="mt-3 pt-2.5 border-t border-slate-200 flex justify-between items-center text-xs">
                    <span class="text-slate-500">Resit Fee:</span>
                    <span class="font-extrabold text-emerald-700">GHS {{ number_format($c->registration_fee, 2) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
