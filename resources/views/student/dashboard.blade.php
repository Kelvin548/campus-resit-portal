@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome Header Banner -->
    <div class="bg-gradient-to-r from-brand-900 via-brand-800 to-brand-700 rounded-3xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-medium text-brand-100 backdrop-blur-sm mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Active Academic Session: 2026/2027
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Welcome, {{ $student->full_name }}!</h1>
            <p class="text-brand-100 text-sm mt-1 max-w-xl">
                Student Index: <span class="font-semibold text-white">{{ $student->student_id_number ?? 'STU-PENDING' }}</span> • 
                Portal: <span class="font-semibold text-white">Student Course & Resit Examination Hub</span>
            </p>
        </div>

        <div class="flex items-center gap-3">
            @if($studentRegistrationOpen)
            <a href="{{ route('student.courses') }}" 
               class="px-4 py-2.5 rounded-xl bg-white text-brand-900 font-semibold text-sm hover:bg-brand-50 shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>Register New Course</span>
            </a>
            @endif
            <a href="{{ route('student.timetable') }}" 
               class="px-4 py-2.5 rounded-xl bg-brand-800/80 hover:bg-brand-800 text-white font-medium text-sm border border-brand-600 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Timetable</span>
            </a>
        </div>
    </div>

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
            <div class="mt-1 text-xs text-slate-400">Awaiting Chief Examiner review</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Approved & Ready</div>
            <div class="mt-2 text-3xl font-extrabold text-emerald-600">{{ $approvedCount }}</div>
            <div class="mt-1 text-xs text-slate-400">Ready for resit examination</div>
        </div>
    </div>

    <!-- Active Registered Courses List -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900">My Registered Resit Courses</h2>
                <p class="text-xs text-slate-500 mt-0.5">Track your submissions, fees, payment verifications, and approvals</p>
            </div>
            <a href="{{ route('student.courses') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                <span>Browse course catalog</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @if($registrations->isEmpty())
            <div class="text-center py-16 px-4">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-800">No course registrations yet</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">You have not registered for any resit courses this semester. Check available courses to get started.</p>
                <a href="{{ route('student.courses') }}" class="mt-5 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 text-white font-semibold text-sm hover:bg-brand-700 transition">
                    Register for a Resit Course
                </a>
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
                                @if($reg->status === 'pending_payment' && $studentRegistrationOpen)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Pending Payment
                                    </span>
                                @elseif($reg->status === 'paid')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Paid • Awaiting Examiner
                                    </span>
                                @elseif($reg->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Approved
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
                                    <span class="font-mono text-slate-700">{{ $reg->payment->transaction_reference }}</span>
                                    <div class="text-slate-400 capitalize">{{ $reg->payment->network }} • {{ $reg->payment->status }}</div>
                                @else
                                    <span class="text-slate-400 italic">Not paid</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                @if($reg->status === 'pending_payment')
                                    <button type="button" 
                                            onclick="openPayModal('{{ $reg->id }}', '{{ $reg->course->course_code }}', '{{ number_format($reg->course->registration_fee, 2) }}')"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 shadow-xs transition">
                                        <span>Pay with MoMo</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </button>
                                @elseif($reg->status === 'pending_payment')
                                    <span class="text-xs text-slate-500">Payment period closed</span>
                                @elseif($reg->status === 'approved' || $reg->status === 'paid')
                                    <a href="{{ route('student.examSlip', $reg->id) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-brand-700 bg-brand-50 hover:bg-brand-100 border border-brand-200 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span>Exam Slip</span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Scheduled Examination Timetables for Registered Courses -->
    @if($upcomingTimetables->isNotEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Published Exam Schedule for Your Courses</h2>
                <p class="text-xs text-slate-500 mt-0.5">Assigned and verified by the Chief Examiner</p>
            </div>
            <a href="{{ route('student.timetable') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">View Master Schedule &rarr;</a>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($upcomingTimetables as $tt)
            <div class="p-4 rounded-xl border border-brand-200 bg-brand-50/40 relative">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="inline-block px-2 py-0.5 rounded text-xs font-bold bg-brand-600 text-white">{{ $tt->course->course_code }}</span>
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
                        <span class="font-medium text-slate-800">Venue: {{ $tt->venue }} (Capacity: {{ $tt->seating_capacity }})</span>
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
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-200">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-900">Mobile Money Resit Payment</h3>
            <button type="button" onclick="closePayModal()" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
        </div>

        <form id="paymentForm" method="POST" action="" class="space-y-4 mt-4">
            @csrf
            <div>
                <div class="text-xs text-slate-500">Course & Registration:</div>
                <div id="modalCourseCode" class="text-lg font-bold text-slate-900"></div>
            </div>

            <div class="p-3 rounded-xl bg-brand-50 border border-brand-200 flex justify-between items-center">
                <span class="text-sm font-medium text-brand-800">Amount Due:</span>
                <span class="text-xl font-extrabold text-brand-900">GHS <span id="modalFee">0.00</span></span>
            </div>

            <div>
                <label for="network" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Select Network</label>
                <select name="network" id="network" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">
                    <option value="MTN">MTN Mobile Money</option>
                    <option value="Telecel">Telecel Cash (Vodafone)</option>
                    <option value="AT">AT Money (AirtelTigo)</option>
                </select>
            </div>

            <div>
                <label for="phone_number" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Mobile Money Number</label>
                <input type="tel" name="phone_number" id="phone_number" value="{{ $student->phone_number ?? '0244123456' }}" required
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500">
                <p class="text-xs text-slate-400 mt-1">A test authorization prompt will simulate gateway approval.</p>
            </div>

            <div class="pt-2 flex items-center justify-end gap-3">
                <button type="button" onclick="closePayModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-sm transition">
                    Approve & Pay Now
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
