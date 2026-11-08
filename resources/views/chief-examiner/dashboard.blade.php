@extends('layouts.app')

@section('content')
<div class="space-y-8 py-4">
    
    <!-- Top Welcome Banner in Rich USTED Institutional Wine Gradient -->
    <div class="bg-usted-wine-gradient rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col lg:flex-row lg:items-center justify-between gap-6 border border-brand-800/40" style="background: linear-gradient(135deg, #440a15 0%, #640f1f 45%, #7a1326 100%) !important; color: #ffffff !important;">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold text-brand-200 backdrop-blur-sm mb-3 border border-white/10">
                <span class="w-2.5 h-2.5 rounded-full {{ $registrationOpen ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400' }}"></span>
                Global Application Window: {{ $registrationOpen ? 'OPEN & Active' : 'CLOSED & Unavailable' }}
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Chief Examination Officer</h1>
            <p class="text-brand-100 text-sm mt-1 max-w-xl">
                {{ auth()->user()->full_name }} • Manage curriculum courses, broadcast announcements, review student payments, and grant examination approvals.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <!-- Global Applications Availability Toggle Form -->
            <form method="POST" action="{{ route('chief-examiner.toggle-applications') }}" class="inline">
                @csrf
                @if($registrationOpen)
                    <input type="hidden" name="target_state" value="closed">
                    <button type="submit" 
                            onclick="return confirm('Disable student applications? When set to Unavailable, student registration forms will be intercepted.')"
                            class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        <span>Set to Unavailable (Close Applications)</span>
                    </button>
                @else
                    <input type="hidden" name="target_state" value="open">
                    <button type="submit" 
                            class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Set to Available (Open Applications)</span>
                    </button>
                @endif
            </form>

            <a href="{{ route('chief-examiner.broadcasts.index') }}" 
               class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/20 shadow-sm transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-brand-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                <span>Broadcast System</span>
            </a>

            <a href="{{ route('chief-examiner.cycle-settings.edit') }}" 
               class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/20 shadow-sm transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-brand-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Cycle Dates</span>
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

    <!-- 5 Stats Overview Cards: Resit & Supplementary Applications Visibility -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Active Resit Courses</div>
            <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $activeCoursesCount }}</div>
            <div class="mt-1 text-xs text-slate-400">Total in system: {{ $courses->count() }}</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-amber-200 bg-amber-50/20 shadow-xs">
            <div class="text-[11px] font-semibold text-amber-700 uppercase tracking-wider">Paid Resits Pending</div>
            <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-amber-600">{{ $pendingApprovalCount }}</div>
            <div class="mt-1 text-xs text-amber-700">MoMo verified queue</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-purple-200 bg-purple-50/20 shadow-xs">
            <div class="text-[11px] font-semibold text-purple-700 uppercase tracking-wider">Supplementary Pending</div>
            <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-purple-700">{{ $pendingSupplementary->count() }}</div>
            <div class="mt-1 text-xs text-purple-700">Doctor reports &amp; letters</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-emerald-200 bg-emerald-50/20 shadow-xs">
            <div class="text-[11px] font-semibold text-emerald-700 uppercase tracking-wider">Approved Registrations</div>
            <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-emerald-600">{{ $approvedCount }}</div>
            <div class="mt-1 text-xs text-emerald-700">Exam slips printable</div>
        </div>

        <div class="col-span-2 lg:col-span-1 bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total MoMo Fees</div>
            <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-slate-900">GHS {{ number_format($totalFeesCollected, 2) }}</div>
            <div class="mt-1 text-xs text-slate-400">Mobile Money revenue</div>
        </div>
    </div>

    <!-- Student Applications Review Hub (Split into 2 Dedicated Queues: Paid Resit vs. Supplementary) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200 mb-1">
                    <span>Chief Examination Officer Review Center</span>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Student Examination Applications Queue</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Select between paid resit registrations and supplementary requests (doctor's reports &amp; extenuating letters) to grant clearance or reject.
                </p>
            </div>

            <!-- The Two Tab Buttons -->
            <div class="flex items-center gap-2 p-1.5 bg-slate-100/90 rounded-2xl w-full sm:w-auto overflow-x-auto">
                <button type="button" 
                        id="tabResitBtn" 
                        onclick="switchQueueTab('resit')" 
                        class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-brand-600 text-white shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Paid Resits</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] bg-white/20 text-white font-mono">{{ $pendingApprovals->count() }}</span>
                </button>

                <button type="button" 
                        id="tabSuppBtn" 
                        onclick="switchQueueTab('supplementary')" 
                        class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-white/60 transition flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Supplementary Applications</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] bg-purple-100 text-purple-800 font-bold font-mono">{{ $pendingSupplementary->count() }}</span>
                </button>
            </div>
        </div>

        <!-- Tab 1: Paid Resit Applications Queue -->
        <div id="resitQueueContainer">
            @if($pendingApprovals->isEmpty())
                <div class="text-center py-12 px-4">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">All paid resit registrations are up to date!</h3>
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
                                <th class="py-3.5 px-4">Submission Time</th>
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
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                            Supplementary / Exempted
                                        </span>
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
                                                    class="px-4 py-1.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
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

        <!-- Tab 2: Supplementary Applications Queue (Doctor's Reports & Letters) -->
        <div id="supplementaryQueueContainer" class="hidden">
            @if($pendingSupplementary->isEmpty())
                <div class="text-center py-12 px-4">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">No supplementary applications pending review!</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        All student medical reports and extenuating excuse letters have been inspected and decided.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="py-3.5 px-6">Student Details</th>
                                <th class="py-3.5 px-4">Courses Requested</th>
                                <th class="py-3.5 px-4">Doctor's Medical Report</th>
                                <th class="py-3.5 px-4">Official Excuse Letter</th>
                                <th class="py-3.5 px-4">Date Submitted</th>
                                <th class="py-3.5 px-6 text-right">Examiner Verification Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($pendingSupplementary as $supp)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">{{ $supp->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">Index: {{ $supp->index_number }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $supp->programme }} • Level {{ $supp->level }}</div>
                                    <div class="text-[11px] text-slate-400">Tel: {{ $supp->telephone }}</div>
                                </td>
                                <td class="py-4 px-4">
                                    @if(!empty($supp->course_entries) && is_array($supp->course_entries))
                                        <div class="space-y-1">
                                            @foreach($supp->course_entries as $cEntry)
                                                <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                                    {{ $cEntry }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-500">{{ $supp->paper_count }} Paper(s)</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap text-xs">
                                    @if($supp->medical_report_path)
                                        <a href="{{ route('chief-examiner.resit-requests.document', [$supp, 'medical_report']) }}" 
                                           target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 font-semibold transition">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>View Doctor's Report</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 text-xs italic">No document attached</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap text-xs">
                                    @if($supp->letter_path)
                                        <a href="{{ route('chief-examiner.resit-requests.document', [$supp, 'letter']) }}" 
                                           target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 font-semibold transition">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span>View Justification Letter</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 text-xs italic">No letter attached</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap text-xs text-slate-500">
                                    {{ $supp->created_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <button type="button"
                                                onclick="openRejectSupplementaryModal({{ $supp->id }}, '{{ addslashes($supp->name) }}')"
                                                class="px-3 py-1.5 rounded-xl border border-rose-200 text-rose-700 bg-rose-50 hover:bg-rose-100 text-xs font-semibold transition">
                                            Reject
                                        </button>

                                        <form method="POST" action="{{ route('chief-examiner.resit-requests.approve', $supp) }}">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Confirm approval for {{ addslashes($supp->name) }}? This grants full supplementary examination clearance.')"
                                                    class="px-4 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span>Approve Supplementary</span>
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
    </div>

    <!-- COURSE MANAGEMENT SECTION (Level & Semester removed as requested) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200 mb-1">
                    <span>Curriculum Administration</span>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Chief Examination Officer Course Management</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Direct Create, Read, Update, and Delete (CRUD) operations on courses. Any course can be entered and taken anytime without rigid level or semester restrictions.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" 
                        onclick="openAddCourseModal()"
                        class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-brand-900 via-brand-800 to-brand-700 hover:from-brand-950 hover:to-brand-800 text-white font-bold text-xs shadow-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add New Course</span>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-6">Course Code & Name</th>
                        <th class="py-3 px-4">Credit Hours</th>
                        <th class="py-3 px-4">Resit Fee</th>
                        <th class="py-3 px-4">Active Status</th>
                        <th class="py-3 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($courses as $course)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-6">
                            <div class="font-bold text-slate-900 flex items-center gap-2">
                                <span class="font-mono px-2.5 py-1 rounded-lg bg-brand-50 text-brand-700 border border-brand-200 text-xs font-bold">
                                    {{ $course->course_code }}
                                </span>
                                <span class="text-sm font-semibold text-slate-900">{{ $course->course_name }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 font-medium text-slate-700">
                            {{ $course->credit_hours }} Credit(s)
                        </td>
                        <td class="py-3.5 px-4 font-bold text-emerald-700 whitespace-nowrap">
                            GHS {{ number_format($course->registration_fee, 2) }}
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            @if($course->is_active)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-2">
                                <button type="button" 
                                        onclick="openEditCourseModal({{ json_encode($course) }})"
                                        class="px-3 py-1.5 rounded-lg border border-slate-200 hover:border-brand-300 text-slate-700 hover:text-brand-700 bg-white hover:bg-brand-50 text-xs font-semibold transition">
                                    Edit
                                </button>

                                <form method="POST" action="{{ route('chief-examiner.courses.destroy', $course) }}" onsubmit="return confirm('Are you sure you want to permanently delete {{ $course->course_code }} ({{ $course->course_name }})? It will immediately disappear from student registration forms.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-1.5 rounded-lg border border-rose-200 text-rose-700 bg-rose-50 hover:bg-rose-100 text-xs font-semibold transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">
                            No courses currently configured in the database.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recently Approved Registrations & Decisions -->
    @if($processedRegistrations->isNotEmpty() || $processedSupplementary->isNotEmpty())
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-900">Recently Processed Student Decisions</h2>
            <p class="text-xs text-slate-500 mt-0.5">Historical log of registrations and supplementary requests decided by the Chief Examination Officer</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 uppercase font-semibold text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-6">Student</th>
                        <th class="py-3 px-4">Application Type &amp; Course</th>
                        <th class="py-3 px-4">Status &amp; Feedback</th>
                        <th class="py-3 px-4">Evidence / Reference</th>
                        <th class="py-3 px-6 text-right">Decided On</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($processedRegistrations as $item)
                    <tr>
                        <td class="py-3.5 px-6 font-semibold text-slate-800">
                            {{ $item->user->full_name }} ({{ $item->user->student_id_number }})
                        </td>
                        <td class="py-3.5 px-4 font-mono">
                            <span class="font-bold text-slate-900">{{ $item->course->course_code }}</span>
                            <span class="text-slate-400 block text-[10px]">Standard Paid Resit</span>
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

                    @foreach($processedSupplementary as $suppItem)
                    <tr class="bg-purple-50/20">
                        <td class="py-3.5 px-6 font-semibold text-slate-800">
                            {{ $suppItem->name }} ({{ $suppItem->index_number }})
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="font-bold text-purple-900 block text-xs">Supplementary Application</span>
                            <span class="text-slate-500 text-[11px]">{{ is_array($suppItem->course_entries) ? implode(', ', $suppItem->course_entries) : $suppItem->paper_count . ' paper(s)' }}</span>
                        </td>
                        <td class="py-3.5 px-4">
                            @if($suppItem->status === 'approved')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Approved
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Rejected
                                </span>
                                @if($suppItem->rejection_reason)
                                    <div class="text-[11px] text-rose-800 max-w-xs mt-1 leading-tight font-medium">{{ $suppItem->rejection_reason }}</div>
                                @endif
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-xs whitespace-nowrap">
                            @if($suppItem->medical_report_path)
                                <a href="{{ route('chief-examiner.resit-requests.document', [$suppItem, 'medical_report']) }}" target="_blank" class="text-emerald-700 font-semibold hover:underline">Report</a>
                            @endif
                            @if($suppItem->letter_path)
                                <span class="text-slate-300">•</span>
                                <a href="{{ route('chief-examiner.resit-requests.document', [$suppItem, 'letter']) }}" target="_blank" class="text-blue-700 font-semibold hover:underline">Letter</a>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 text-right text-slate-400">
                            {{ $suppItem->updated_at->format('M d, Y h:i A') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

<!-- Modal 1: Add New Course Modal (Level & Semester removed as requested) -->
<div id="addCourseModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 animate-in fade-in zoom-in duration-150">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-usted-wine-gradient text-white" style="background: linear-gradient(135deg, #440a15 0%, #640f1f 45%, #7a1326 100%) !important; color: #ffffff !important;">
            <div>
                <h3 class="text-base font-bold">Add New Academic Course</h3>
                <p class="text-xs text-brand-200">Will immediately update the database and appear on student forms</p>
            </div>
            <button type="button" onclick="closeAddCourseModal()" class="text-brand-200 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('chief-examiner.courses.store') }}" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Course Code *</label>
                    <input type="text" name="course_code" required placeholder="e.g. ICT112"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-mono text-sm uppercase focus:ring-2 focus:ring-brand-600 focus:border-brand-600">
                </div>
                <div class="col-span-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Credit Hours *</label>
                    <input type="number" name="credit_hours" required min="1" max="10" value="3"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Course Name / Title *</label>
                <input type="text" name="course_name" required placeholder="e.g. Principles of Programming"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Resit Registration Fee (GHS) *</label>
                <input type="number" step="0.01" name="registration_fee" required value="150.00"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold text-emerald-800 focus:ring-2 focus:ring-brand-600 focus:border-brand-600">
            </div>

            <div class="pt-2 flex items-center gap-2">
                <input type="checkbox" name="is_active" id="add_is_active" value="1" checked class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                <label for="add_is_active" class="text-xs font-medium text-slate-700">Course is Active &amp; Offered Immediately</label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeAddCourseModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-semibold">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-brand-900 to-brand-700 hover:from-brand-950 hover:to-brand-800 text-white font-bold text-xs shadow-md transition">
                    Save Course to Database
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Edit Course Modal (Level & Semester removed as requested) -->
<div id="editCourseModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 animate-in fade-in zoom-in duration-150">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-usted-wine-gradient text-white" style="background: linear-gradient(135deg, #440a15 0%, #640f1f 45%, #7a1326 100%) !important; color: #ffffff !important;">
            <div>
                <h3 class="text-base font-bold">Edit Academic Course</h3>
                <p class="text-xs text-brand-200">Modifications immediately reflect across all student forms</p>
            </div>
            <button type="button" onclick="closeEditCourseModal()" class="text-brand-200 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="editCourseForm" method="POST" action="" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Course Code *</label>
                    <input type="text" id="edit_course_code" name="course_code" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-mono text-sm uppercase focus:ring-2 focus:ring-brand-600 focus:border-brand-600">
                </div>
                <div class="col-span-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Credit Hours *</label>
                    <input type="number" id="edit_credit_hours" name="credit_hours" required min="1" max="10"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Course Name / Title *</label>
                <input type="text" id="edit_course_name" name="course_name" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Resit Registration Fee (GHS) *</label>
                <input type="number" step="0.01" id="edit_registration_fee" name="registration_fee" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold text-emerald-800 focus:ring-2 focus:ring-brand-600 focus:border-brand-600">
            </div>

            <div class="pt-2 flex items-center gap-2">
                <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                <label for="edit_is_active" class="text-xs font-medium text-slate-700">Course is Active &amp; Offered</label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeEditCourseModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-semibold">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-brand-900 to-brand-700 hover:from-brand-950 hover:to-brand-800 text-white font-bold text-xs shadow-md transition">
                    Update Course
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 3: Reject Supplementary Application Modal -->
<div id="rejectSupplementaryModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 animate-in fade-in zoom-in duration-150">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-usted-wine-gradient text-white" style="background: linear-gradient(135deg, #440a15 0%, #640f1f 45%, #7a1326 100%) !important; color: #ffffff !important;">
            <div>
                <h3 class="text-base font-bold">Reject Supplementary Application</h3>
                <p class="text-xs text-brand-200" id="rejectModalStudentSubtitle">Candidate examination decision</p>
            </div>
            <button type="button" onclick="closeRejectSupplementaryModal()" class="text-brand-200 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="rejectSupplementaryForm" method="POST" action="" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Rejection Reason &amp; Instructions to Student *
                </label>
                <textarea name="rejection_reason" id="rejection_reason_text" rows="4" required
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500">Your doctor's medical report or justification letter is unacceptable. Please go back to register for a standard resit examination.</textarea>
                <p class="text-[11px] text-slate-500 mt-1">
                    This notice will be immediately posted on the student's dashboard along with a direct button leading them to register for a regular resit paper.
                </p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeRejectSupplementaryModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-semibold">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md transition">
                    Confirm Rejection &amp; Notify Student
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Tab switching between Paid Resits and Supplementary Applications
    function switchQueueTab(tab) {
        const resitBtn = document.getElementById('tabResitBtn');
        const suppBtn = document.getElementById('tabSuppBtn');
        const resitContainer = document.getElementById('resitQueueContainer');
        const suppContainer = document.getElementById('supplementaryQueueContainer');

        if (tab === 'resit') {
            resitContainer.classList.remove('hidden');
            suppContainer.classList.add('hidden');

            resitBtn.className = 'px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-brand-600 text-white shadow-xs';
            suppBtn.className = 'px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-white/60 transition flex items-center gap-2';
        } else {
            resitContainer.classList.add('hidden');
            suppContainer.classList.remove('hidden');

            suppBtn.className = 'px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-purple-700 text-white shadow-xs';
            resitBtn.className = 'px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-white/60 transition flex items-center gap-2';
        }
    }

    function openRejectSupplementaryModal(id, studentName) {
        document.getElementById('rejectSupplementaryForm').action = '/chief-examiner/resit-requests/' + id + '/reject';
        document.getElementById('rejectModalStudentSubtitle').innerText = 'Student: ' + studentName;
        document.getElementById('rejectSupplementaryModal').classList.remove('hidden');
    }

    function closeRejectSupplementaryModal() {
        document.getElementById('rejectSupplementaryModal').classList.add('hidden');
    }

    function openAddCourseModal() {
        document.getElementById('addCourseModal').classList.remove('hidden');
    }

    function closeAddCourseModal() {
        document.getElementById('addCourseModal').classList.add('hidden');
    }

    function openEditCourseModal(course) {
        document.getElementById('editCourseForm').action = '/chief-examiner/courses/' + course.id;
        document.getElementById('edit_course_code').value = course.course_code;
        document.getElementById('edit_course_name').value = course.course_name;
        document.getElementById('edit_credit_hours').value = course.credit_hours;
        document.getElementById('edit_registration_fee').value = parseFloat(course.registration_fee).toFixed(2);
        document.getElementById('edit_is_active').checked = !!course.is_active;
        document.getElementById('editCourseModal').classList.remove('hidden');
    }

    function closeEditCourseModal() {
        document.getElementById('editCourseModal').classList.add('hidden');
    }

    // Close modals on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAddCourseModal();
            closeEditCourseModal();
            closeRejectSupplementaryModal();
        }
    });
</script>
@endsection
