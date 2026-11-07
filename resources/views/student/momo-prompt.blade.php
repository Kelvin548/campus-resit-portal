@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8 px-4">
    
    <!-- Top Progress Breadcrumb -->
    <div class="mb-6 flex items-center justify-between text-xs text-slate-500 font-medium">
        <div class="flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold">1</span>
            <span>Course Selected</span>
        </div>
        <span class="text-slate-300">───</span>
        <div class="flex items-center gap-2 text-brand-600 font-semibold">
            <span class="w-6 h-6 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold">2</span>
            <span>MoMo PIN Authorization</span>
        </div>
        <span class="text-slate-300">───</span>
        <div class="flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center font-bold">3</span>
            <span>Official Receipt</span>
        </div>
    </div>

    <!-- Smartphone Simulator Card -->
    <div class="bg-slate-900 rounded-[2.5rem] p-4 sm:p-6 shadow-2xl border-4 border-slate-800 text-white relative overflow-hidden">
        
        <!-- Phone Speaker & Notch -->
        <div class="w-28 h-4 bg-slate-800 rounded-full mx-auto mb-6 flex items-center justify-center">
            <div class="w-3 h-3 rounded-full bg-slate-900 mr-2"></div>
            <div class="w-8 h-1.5 rounded-full bg-slate-700"></div>
        </div>

        <!-- Phone Header / Carrier Info -->
        <div class="flex items-center justify-between text-xs text-slate-400 mb-4 px-2">
            <div class="flex items-center gap-1.5 font-bold tracking-wider">
                @if($payment->network === 'MTN')
                    <span class="px-2 py-0.5 rounded bg-yellow-400 text-slate-900 font-extrabold text-[10px]">MTN</span>
                    <span>MTN MoMo Push</span>
                @elseif($payment->network === 'Telecel')
                    <span class="px-2 py-0.5 rounded bg-red-600 text-white font-extrabold text-[10px]">TELECEL</span>
                    <span>Telecel Cash Push</span>
                @else
                    <span class="px-2 py-0.5 rounded bg-blue-500 text-white font-extrabold text-[10px]">AT</span>
                    <span>AT Money Push</span>
                @endif
            </div>
            <div class="flex items-center gap-1">
                <span id="countdown" class="font-mono text-amber-400 font-bold">60s</span>
                <span class="text-[10px] text-slate-500">remaining</span>
            </div>
        </div>

        <!-- USSD Dialog Box Screen -->
        <div class="bg-white rounded-2xl p-6 text-slate-900 shadow-inner">
            
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-lg">
                    📲
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Authorize Payment Request</h2>
                    <p class="text-xs text-slate-500">From: Campus Resit Portal</p>
                </div>
            </div>

            <!-- Transaction Summary -->
            <div class="space-y-2 mb-5 text-sm bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-500">Course / Item:</span>
                    <span class="font-semibold text-slate-800">{{ $course->course_code }} Resit Exam</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-500">Recipient:</span>
                    <span class="font-semibold text-slate-800">University Exams Bureau</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-500">Reference:</span>
                    <span class="font-mono text-slate-600">{{ $payment->transaction_reference }}</span>
                </div>
                <div class="pt-2 border-t border-slate-200 flex justify-between items-center">
                    <span class="font-bold text-slate-700">Total Deduction:</span>
                    <span class="text-lg font-extrabold text-emerald-700">GHS {{ number_format($payment->amount, 2) }}</span>
                </div>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                    @foreach ($errors->all() as $error)
                        <p class="font-semibold">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Prompt Form -->
            <form id="momoAuthForm" method="POST" action="{{ route('student.payments.process', $payment) }}" class="space-y-4">
                @csrf
                
                <div>
                    <label for="momo_pin" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 text-center">
                        Enter 4-Digit Mobile Money PIN
                    </label>
                    <div class="max-w-[200px] mx-auto">
                        <input type="password" 
                               id="momo_pin" 
                               name="momo_pin" 
                               maxlength="4" 
                               pattern="\d{4}" 
                               inputmode="numeric" 
                               required 
                               autofocus 
                               placeholder="••••"
                               class="w-full tracking-[1em] text-center font-bold text-2xl py-2.5 px-3 rounded-xl border-2 border-brand-500 text-slate-900 focus:outline-none focus:ring-4 focus:ring-brand-200 transition">
                    </div>
                    <p class="text-[11px] text-slate-400 text-center mt-1">
                        Test Mode: Enter any 4 numbers (e.g. <span class="font-mono text-slate-600">1234</span>)
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="submit" 
                            name="action" 
                            value="decline"
                            formnovalidate
                            class="w-full py-2.5 px-3 rounded-xl border border-slate-300 font-semibold text-slate-700 text-xs hover:bg-slate-100 transition text-center">
                        Decline / Cancel
                    </button>
                    <button type="submit" 
                            name="action" 
                            value="approve"
                            class="w-full py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition text-center flex items-center justify-center gap-1.5">
                        <span>Approve Payment</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Phone Bottom Bar -->
        <div class="w-32 h-1 bg-slate-700 rounded-full mx-auto mt-6"></div>
    </div>

    <!-- Security Information Footer -->
    <div class="mt-6 p-4 rounded-2xl bg-white border border-slate-200 text-xs text-slate-500 flex items-start gap-3 shadow-xs">
        <svg class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
        <div>
            <span class="font-bold text-slate-800">Member 3 Payment Security Protocol:</span>
            Transactions are processed through telecom-grade test sandbox simulations. Never disclose your actual PIN to third parties. Once approved, the payment is cryptographically verified and recorded immediately.
        </div>
    </div>
</div>

<script>
    // 60-Second USSD Session Timeout Simulation
    let timeLeft = 60;
    const countdownEl = document.getElementById('countdown');
    const timer = setInterval(function() {
        timeLeft--;
        if (countdownEl) {
            countdownEl.textContent = timeLeft + 's';
        }
        if (timeLeft <= 0) {
            clearInterval(timer);
            clearInterval(checkInterval);
            alert('Mobile Money authorization prompt timed out. Redirecting back to dashboard.');
            window.location.href = "{{ route('student.dashboard') }}";
        }
    }, 1000);

    // Automatic Live Poll to check if student authorized on their phone via Paystack
    const verifyUrl = "{{ route('student.payments.verify', $payment) }}";
    const checkInterval = setInterval(function() {
        fetch(verifyUrl, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success' && data.redirect) {
                clearInterval(checkInterval);
                clearInterval(timer);
                window.location.href = data.redirect;
            }
        })
        .catch(() => {});
    }, 3000);
</script>
@endsection
