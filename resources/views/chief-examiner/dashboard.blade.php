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

        <div class="bg-white p-5 rounded-2xl border border-emerald-200 bg-emerald-50/20 shadow-xs">
            <div class="text-[11px] font-semibold text-emerald-700 uppercase tracking-wider">Confirmed Resits</div>
            <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-emerald-600">{{ $pendingApprovalCount }}</div>
            <div class="mt-1 text-xs text-emerald-700">Auto-cleared on payment</div>
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

    <!-- Student Applications Review Hub (Split into 2 Dedicated Queues: Confirmed Resit vs. Supplementary) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200 mb-1">
                    <span>Chief Examination Officer Review Center</span>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Student Examination Applications Queue</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Confirmed resit registrations are auto-cleared on payment. Only supplementary applications (doctor's reports &amp; extenuating letters) require manual review.
                </p>
            </div>

            <!-- The Two Tab Buttons -->
            <div class="flex items-center gap-2 p-1.5 bg-slate-100/90 rounded-2xl w-full sm:w-auto overflow-x-auto">
                <button type="button" 
                        id="tabResitBtn" 
                        onclick="switchQueueTab('resit')" 
                        class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-emerald-600 text-white shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Confirmed Resits</span>
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

        <!-- Tab 1: Confirmed Resit Registrations (Read-only — auto-approved on payment) -->
        <div id="resitQueueContainer">
            @if($pendingApprovals->isEmpty())
                <div class="text-center py-12 px-4">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">No confirmed resit registrations yet.</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        Resit registrations are automatically confirmed once a student completes their MoMo payment.
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
                                <th class="py-3.5 px-6 text-right">Status</th>
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
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Confirmed &amp; Cleared
                                    </span>
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
    <div id="courses-section" class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
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
                        class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-brand-900 via-brand-800 to-brand-700 hover:from-brand-950 hover:to-brand-800 text-white font-bold text-xs shadow-sm transition flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add New Course</span>
                </button>
            </div>
        </div>

        <!-- Course Search & Controls Bar -->
        <div class="px-6 py-4 bg-slate-50/80 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       id="courseSearchInput" 
                       oninput="filterCoursesTable()"
                       placeholder="Search by course code or course name (e.g. AI201, Database)..."
                       class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-xs transition">
                <button type="button" 
                        id="courseSearchClearBtn" 
                        onclick="clearCourseSearch()" 
                        class="hidden absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition"
                        title="Clear search">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="flex items-center gap-3 text-xs text-slate-500 justify-between md:justify-end">
                <span id="courseCountBadge" class="font-semibold text-slate-600 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-2xs">
                    Showing {{ min(15, $courses->count()) }} of {{ $courses->count() }} courses
                </span>

                @if($courses->count() > 15)
                <button type="button" 
                        id="toggleViewAllCoursesBtnTop"
                        onclick="toggleViewAllCourses()"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-brand-700 bg-brand-50 hover:bg-brand-100 border border-brand-200/80 transition">
                    <span id="toggleViewAllTextTop">View All ({{ $courses->count() }})</span>
                    <svg id="toggleViewAllIconTop" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                @endif
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
                <tbody id="coursesTableBody" class="divide-y divide-slate-100">
                    @forelse($courses as $course)
                    <tr class="course-table-row hover:bg-slate-50/70 transition {{ $loop->index >= 15 ? 'course-row-overflow hidden' : '' }}"
                        data-code="{{ strtolower($course->course_code) }}"
                        data-name="{{ strtolower($course->course_name) }}"
                        data-index="{{ $loop->index }}">
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

                    <tr id="courseSearchNoResults" class="hidden">
                        <td colspan="5" class="py-10 text-center">
                            <div class="max-w-xs mx-auto text-slate-500 space-y-2">
                                <svg class="w-8 h-8 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <p class="text-sm font-semibold text-slate-700">No matching courses found</p>
                                <p class="text-xs text-slate-400">No course code or name matched your search query.</p>
                                <button type="button" onclick="clearCourseSearch()" class="mt-2 px-3 py-1.5 text-xs font-semibold text-brand-600 hover:text-brand-800 bg-brand-50 rounded-lg border border-brand-200 transition">
                                    Clear search query
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($courses->count() > 15)
        <div id="courseOverflowFooter" class="p-4 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
            <div>
                <span id="courseFooterStatusText">Showing first 15 courses. {{ $courses->count() - 15 }} more courses available.</span>
            </div>
            <button type="button" 
                    id="toggleViewAllCoursesBtnBottom"
                    onclick="toggleViewAllCourses()"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-xs transition">
                <span id="toggleViewAllTextBottom">View All {{ $courses->count() }} Courses</span>
                <svg id="toggleViewAllIconBottom" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>
        @endif
    </div>

    <!-- TIMETABLE BUILDER SECTION (Construct, Insert & Publish Examination Timetables) -->
    <div id="timetable-section" class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200 mb-1">
                    <svg class="w-3.5 h-3.5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Academic Examination Timetables</span>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Examination Timetable Builder &amp; Master Schedule</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Construct and schedule exam papers, allocate venues and times, and publish timetable entries directly to the student portal.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('chief-examiner.timetables.index') }}" 
                   class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                    <span>Full Timetable View</span>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <button type="button" 
                        onclick="openDashboardScheduleModal()"
                        class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-brand-900 via-brand-800 to-brand-700 hover:from-brand-950 hover:to-brand-800 text-white font-bold text-xs shadow-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Schedule Exam Paper</span>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 uppercase font-bold text-slate-500 border-b border-slate-200 tracking-wider">
                    <tr>
                        <th class="py-3 px-6">Course</th>
                        <th class="py-3 px-4">Exam Date &amp; Day</th>
                        <th class="py-3 px-4">Time Window</th>
                        <th class="py-3 px-4">Venue &amp; Room</th>
                        <th class="py-3 px-4">Capacity</th>
                        <th class="py-3 px-4">Status (Visibility)</th>
                        <th class="py-3 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($timetables as $tt)
                        @php
                            $startTime = \Carbon\Carbon::parse($tt->start_time);
                            $endTime = \Carbon\Carbon::parse($tt->end_time);
                            $durationMinutes = $startTime->diffInMinutes($endTime);
                            $durationHours = round($durationMinutes / 60, 1);
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-6">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-black bg-brand-50 text-brand-700 border border-brand-200">
                                        {{ $tt->course->course_code ?? 'N/A' }}
                                    </span>
                                    <span class="font-bold text-slate-900">{{ $tt->course->course_name ?? 'Course' }}</span>
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $tt->academic_year }} &bull; {{ $tt->semester }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($tt->exam_date)->format('D, M d, Y') }}</span>
                                <div class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($tt->exam_date)->diffForHumans() }}</div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-semibold text-slate-800">{{ $startTime->format('h:i A') }} &ndash; {{ $endTime->format('h:i A') }}</div>
                                <div class="text-[10px] text-slate-400">{{ $durationHours }} hrs ({{ $durationMinutes }} mins)</div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 font-bold text-slate-900">
                                    <svg class="w-3.5 h-3.5 text-brand-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $tt->venue }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-[11px] font-semibold">
                                    {{ $tt->seating_capacity }} seats
                                </span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($tt->isPublished())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Published</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span>Draft</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Toggle Publish Status -->
                                    <form method="POST" action="{{ route('chief-examiner.timetables.toggle-status', $tt->id) }}" class="inline">
                                        @csrf
                                        @if($tt->isPublished())
                                            <button type="submit" 
                                                    title="Unpublish (move to draft)"
                                                    class="px-2 py-1 rounded border border-slate-300 text-slate-600 hover:bg-slate-100 text-[11px] font-semibold transition">
                                                Unpublish
                                            </button>
                                        @else
                                            <button type="submit" 
                                                    title="Publish immediately to student timetable"
                                                    class="px-2 py-1 rounded bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold transition shadow-xs">
                                                Publish
                                            </button>
                                        @endif
                                    </form>

                                    <!-- Edit Timetable Button -->
                                    <button type="button" 
                                            onclick="openDashboardEditTimetableModal({{ json_encode($tt) }})"
                                            class="px-2 py-1 rounded border border-slate-300 text-slate-700 hover:bg-slate-100 text-[11px] font-semibold transition">
                                        Edit
                                    </button>

                                    <!-- Delete Timetable Button -->
                                    <form method="POST" action="{{ route('chief-examiner.timetables.destroy', $tt->id) }}" class="inline" onsubmit="return confirm('Delete timetable paper for {{ $tt->course->course_code }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition" title="Delete timetable">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="font-bold text-slate-700">No examination papers scheduled yet.</div>
                                <div class="text-xs text-slate-500 mt-0.5">Click "Schedule Exam Paper" above to construct timetable papers for students.</div>
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

    // Course Search & 15-Item Overflow Management
    let coursesAreExpanded = false;
    const totalCourseCount = {{ $courses->count() }};
    const courseInitialLimit = 15;

    function filterCoursesTable() {
        const input = document.getElementById('courseSearchInput');
        const clearBtn = document.getElementById('courseSearchClearBtn');
        const query = input ? input.value.trim().toLowerCase() : '';
        const rows = document.querySelectorAll('.course-table-row');
        const noResultsRow = document.getElementById('courseSearchNoResults');
        const badge = document.getElementById('courseCountBadge');
        const overflowFooter = document.getElementById('courseOverflowFooter');
        const topToggleBtn = document.getElementById('toggleViewAllCoursesBtnTop');

        if (clearBtn) {
            if (query.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        }

        let matchCount = 0;

        if (query.length > 0) {
            // Search mode: match against code and name across ALL courses
            rows.forEach(function(row) {
                const code = row.getAttribute('data-code') || '';
                const name = row.getAttribute('data-name') || '';
                if (code.indexOf(query) !== -1 || name.indexOf(query) !== -1) {
                    row.classList.remove('hidden');
                    matchCount++;
                } else {
                    row.classList.add('hidden');
                }
            });

            if (badge) {
                badge.textContent = `Found ${matchCount} matching ${matchCount === 1 ? 'course' : 'courses'}`;
            }

            if (overflowFooter) overflowFooter.classList.add('hidden');
            if (topToggleBtn) topToggleBtn.classList.add('hidden');
        } else {
            // Non-search mode: respect the 15-item limit unless expanded
            rows.forEach(function(row, idx) {
                if (coursesAreExpanded || idx < courseInitialLimit) {
                    row.classList.remove('hidden');
                    matchCount++;
                } else {
                    row.classList.add('hidden');
                }
            });

            if (badge) {
                badge.textContent = coursesAreExpanded 
                    ? `Showing all ${totalCourseCount} courses` 
                    : `Showing ${Math.min(courseInitialLimit, totalCourseCount)} of ${totalCourseCount} courses`;
            }

            if (overflowFooter) overflowFooter.classList.remove('hidden');
            if (topToggleBtn) topToggleBtn.classList.remove('hidden');
        }

        if (noResultsRow) {
            if (query.length > 0 && matchCount === 0) {
                noResultsRow.classList.remove('hidden');
            } else {
                noResultsRow.classList.add('hidden');
            }
        }
    }

    function clearCourseSearch() {
        const input = document.getElementById('courseSearchInput');
        if (input) {
            input.value = '';
            input.focus();
        }
        filterCoursesTable();
    }

    function toggleViewAllCourses() {
        coursesAreExpanded = !coursesAreExpanded;
        const overflowRows = document.querySelectorAll('.course-row-overflow');
        const textTop = document.getElementById('toggleViewAllTextTop');
        const iconTop = document.getElementById('toggleViewAllIconTop');
        const textBottom = document.getElementById('toggleViewAllTextBottom');
        const iconBottom = document.getElementById('toggleViewAllIconBottom');
        const footerStatusText = document.getElementById('courseFooterStatusText');
        const badge = document.getElementById('courseCountBadge');

        if (coursesAreExpanded) {
            overflowRows.forEach(function(row) {
                row.classList.remove('hidden');
            });
            if (textTop) textTop.textContent = 'Show Less (First 15)';
            if (iconTop) iconTop.style.transform = 'rotate(180deg)';
            if (textBottom) textBottom.textContent = 'Show Less (First 15 Courses)';
            if (iconBottom) iconBottom.style.transform = 'rotate(180deg)';
            if (footerStatusText) footerStatusText.textContent = `All ${totalCourseCount} courses are currently displayed.`;
            if (badge) badge.textContent = `Showing all ${totalCourseCount} courses`;
        } else {
            overflowRows.forEach(function(row) {
                row.classList.add('hidden');
            });
            if (textTop) textTop.textContent = `View All (${totalCourseCount})`;
            if (iconTop) iconTop.style.transform = 'rotate(0deg)';
            if (textBottom) textBottom.textContent = `View All ${totalCourseCount} Courses`;
            if (iconBottom) iconBottom.style.transform = 'rotate(0deg)';
            if (footerStatusText) footerStatusText.textContent = `Showing first 15 courses. ${totalCourseCount - courseInitialLimit} more courses available.`;
            if (badge) badge.textContent = `Showing ${Math.min(courseInitialLimit, totalCourseCount)} of ${totalCourseCount} courses`;

            const section = document.getElementById('courses-section');
            if (section) {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        const input = document.getElementById('courseSearchInput');
        if (input && input.value.trim().length > 0) {
            filterCoursesTable();
        }
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

    // Dashboard Timetable Modal Functions
    function openDashboardScheduleModal() {
        document.getElementById('dashScheduleModal').classList.remove('hidden');
    }

    function closeDashboardScheduleModal() {
        document.getElementById('dashScheduleModal').classList.add('hidden');
    }

    function openDashboardEditTimetableModal(tt) {
        document.getElementById('dashEditTimetableForm').action = '/chief-examiner/timetables/' + tt.id;
        document.getElementById('dash-edit-course-id').value = tt.course_id;
        document.getElementById('dash-edit-year').value = tt.academic_year;
        document.getElementById('dash-edit-semester').value = tt.semester;
        
        const dateVal = tt.exam_date ? tt.exam_date.substring(0, 10) : '';
        document.getElementById('dash-edit-date').value = dateVal;
        
        const startTimeVal = tt.start_time ? tt.start_time.substring(0, 5) : '09:00';
        const endTimeVal = tt.end_time ? tt.end_time.substring(0, 5) : '12:00';
        document.getElementById('dash-edit-start').value = startTimeVal;
        document.getElementById('dash-edit-end').value = endTimeVal;

        document.getElementById('dash-edit-venue').value = tt.venue;
        document.getElementById('dash-edit-capacity').value = tt.seating_capacity;

        if (tt.status === 'published') {
            document.getElementById('dash-edit-status-published').checked = true;
        } else {
            document.getElementById('dash-edit-status-draft').checked = true;
        }

        document.getElementById('dashEditTimetableModal').classList.remove('hidden');
    }

    function closeDashboardEditTimetableModal() {
        document.getElementById('dashEditTimetableModal').classList.add('hidden');
    }

    // Close modals on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAddCourseModal();
            closeEditCourseModal();
            closeRejectSupplementaryModal();
            closeDashboardScheduleModal();
            closeDashboardEditTimetableModal();
        }
    });
</script>

<!-- Dashboard Schedule Timetable Modal -->
<div id="dashScheduleModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative animate-in fade-in zoom-in-95 duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Schedule Examination Paper</h3>
                <p class="text-xs text-slate-500 mt-0.5">Construct timetable slot and allocate venue for students.</p>
            </div>
            <button type="button" onclick="closeDashboardScheduleModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('chief-examiner.timetables.store') }}" class="space-y-4 mt-5">
            @csrf
            <div>
                <label for="dash-sched-course" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Course Paper *</label>
                <select id="dash-sched-course" name="course_id" required class="w-full text-xs font-semibold px-4 py-3 rounded-xl border border-slate-300 text-slate-900 focus:ring-2 focus:ring-brand-500 bg-white">
                    <option value="">-- Select Examination Course --</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}">
                            [{{ $c->course_code }}] {{ $c->course_name }} ({{ $c->credit_hours ?? 3 }} Credits)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="dash-sched-year" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Academic Year *</label>
                    <input id="dash-sched-year" type="text" name="academic_year" value="2026/2027" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-slate-300 font-medium focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="dash-sched-semester" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Exam Session *</label>
                    <input id="dash-sched-semester" type="text" name="semester" value="Supplementary & Resit Session" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-slate-300 font-medium focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label for="dash-sched-date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Exam Date *</label>
                    <input id="dash-sched-date" type="date" name="exam_date" value="{{ now()->addDays(10)->format('Y-m-d') }}" required class="w-full text-xs px-3 py-2.5 rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="dash-sched-start" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Start Time *</label>
                    <input id="dash-sched-start" type="time" name="start_time" value="09:00" required class="w-full text-xs px-3 py-2.5 rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="dash-sched-end" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">End Time *</label>
                    <input id="dash-sched-end" type="time" name="end_time" value="12:00" required class="w-full text-xs px-3 py-2.5 rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-2">
                    <label for="dash-sched-venue" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Venue / Hall *</label>
                    <input id="dash-sched-venue" type="text" name="venue" list="dash-venue-list" value="ICT Lab 1 (Main Campus)" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-slate-300 font-medium focus:ring-2 focus:ring-brand-500">
                    <datalist id="dash-venue-list">
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
                    <label for="dash-sched-cap" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Capacity *</label>
                    <input id="dash-sched-cap" type="number" name="seating_capacity" value="50" min="1" max="2000" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-slate-300 font-medium focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

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
            </div>

            <div class="flex items-center justify-end gap-3 pt-3">
                <button type="button" onclick="closeDashboardScheduleModal()" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition">
                    Save Timetable Entry
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Dashboard Edit Timetable Modal -->
<div id="dashEditTimetableModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative animate-in fade-in zoom-in-95 duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Edit Examination Timetable Paper</h3>
                <p class="text-xs text-slate-500 mt-0.5">Update date, time window, venue or publication state.</p>
            </div>
            <button type="button" onclick="closeDashboardEditTimetableModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="dashEditTimetableForm" method="POST" action="" class="space-y-4 mt-5">
            @csrf
            @method('PUT')
            <div>
                <label for="dash-edit-course-id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Course Paper *</label>
                <select id="dash-edit-course-id" name="course_id" required class="w-full text-xs font-semibold px-4 py-3 rounded-xl border border-slate-300 text-slate-900 focus:ring-2 focus:ring-brand-500 bg-white">
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}">
                            [{{ $c->course_code }}] {{ $c->course_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="dash-edit-year" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Academic Year *</label>
                    <input id="dash-edit-year" type="text" name="academic_year" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-slate-300 font-medium focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="dash-edit-semester" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Exam Session *</label>
                    <input id="dash-edit-semester" type="text" name="semester" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-slate-300 font-medium focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label for="dash-edit-date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Exam Date *</label>
                    <input id="dash-edit-date" type="date" name="exam_date" required class="w-full text-xs px-3 py-2.5 rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="dash-edit-start" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Start Time *</label>
                    <input id="dash-edit-start" type="time" name="start_time" required class="w-full text-xs px-3 py-2.5 rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="dash-edit-end" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">End Time *</label>
                    <input id="dash-edit-end" type="time" name="end_time" required class="w-full text-xs px-3 py-2.5 rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-2">
                    <label for="dash-edit-venue" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Venue / Hall *</label>
                    <input id="dash-edit-venue" type="text" name="venue" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-slate-300 font-medium focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="dash-edit-capacity" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Capacity *</label>
                    <input id="dash-edit-capacity" type="number" name="seating_capacity" min="1" max="2000" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-slate-300 font-medium focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Publishing Status *</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-white cursor-pointer">
                        <input id="dash-edit-status-published" type="radio" name="status" value="published" class="text-brand-600 focus:ring-brand-500">
                        <span class="text-xs font-bold text-emerald-800">Published (Live to Students)</span>
                    </label>
                    <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-white cursor-pointer">
                        <input id="dash-edit-status-draft" type="radio" name="status" value="draft" class="text-brand-600 focus:ring-brand-500">
                        <span class="text-xs font-medium text-slate-600">Draft (Hidden)</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3">
                <button type="button" onclick="closeDashboardEditTimetableModal()" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition">
                    Update Timetable Slot
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
