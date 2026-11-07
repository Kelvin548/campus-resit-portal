@extends('layouts.app')

@section('content')
<div class="min-h-[78vh] flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        {{-- Card Container --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80 overflow-hidden">

            {{-- Institutional Wine Header Band --}}
            <div class="bg-gradient-to-r from-brand-900 via-brand-600 to-brand-700 px-8 pt-8 pb-6 text-center text-white relative">
                <div class="inline-block bg-white p-2.5 rounded-2xl shadow-md mb-3 border border-white/20">
                    <img src="{{ asset('images/ammusted-logo.jpg') }}" alt="AMMUSTED Logo" class="h-20 w-auto object-contain mx-auto">
                </div>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight uppercase">AMMUSTED</h1>
                <p class="text-xs text-brand-100 font-medium leading-tight max-w-xs mx-auto mt-1">
                    Akenten Appiah-Menka University of Skills Training and Entrepreneurial Development
                </p>
                <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/20 text-[11px] font-semibold text-brand-100 tracking-wide uppercase border border-white/10">
                    Resit &amp; Supplementary Portal
                </div>
            </div>

            {{-- Body Content --}}
            <div class="p-8 sm:p-9">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Sign in to your account</h2>
                    <p class="text-xs text-slate-500 mt-1">Enter your credentials to access your candidate dashboard</p>
                </div>

                {{-- Streamlined Registration Status Banner --}}
                @if ($studentRegistrationPhase === 'registration')
                    <div class="mb-6 flex items-center gap-2.5 rounded-xl border border-emerald-200 bg-emerald-50/80 px-3.5 py-2.5 text-xs text-emerald-800">
                        <span class="flex h-2 w-2 relative flex-shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="font-medium">Supplementary registration is currently <strong>OPEN</strong>.</span>
                    </div>
                @elseif ($studentRegistrationPhase === 'exam_access')
                    <div class="mb-6 flex items-center gap-2.5 rounded-xl border border-blue-200 bg-blue-50/80 px-3.5 py-2.5 text-xs text-blue-800" role="status">
                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 01-1-1V9a1 1 0 112 0v5a1 1 0 01-1 1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">Registration closed. Sign in to view your <strong>exam timetable</strong>.</span>
                    </div>
                @else
                    <div class="mb-6 flex items-center gap-2.5 rounded-xl border border-amber-200 bg-amber-50/80 px-3.5 py-2.5 text-xs text-amber-800" role="status">
                        <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">Supplementary registration is currently <strong>CLOSED</strong>.</span>
                    </div>
                @endif

                {{-- Error messages --}}
                @if ($errors->any())
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-3 text-xs text-red-800">
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-xs text-emerald-800 font-medium">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Clean Login Form (always shown — cycle gate enforced server-side) --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="login_identifier" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Student Email or Index Number
                        </label>
                        <div class="relative">
                            <input type="text"
                                   id="login_identifier"
                                   name="login_identifier"
                                   value="{{ old('login_identifier') }}"
                                   required
                                   autofocus
                                   autocomplete="username"
                                   placeholder="e.g. name@campus.edu or 10-digit index"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-600 focus:border-brand-600 sm:text-sm font-medium transition">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Password
                            </label>
                            <a href="{{ route('password.request') }}"
                               class="text-xs font-semibold text-brand-600 hover:text-brand-800 hover:underline transition">
                                Forgot password?
                            </a>
                        </div>
                        <div class="relative">
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="Enter your account password"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-600 focus:border-brand-600 sm:text-sm transition">
                        </div>
                    </div>

                    {{-- Password Reset Assistance Notice (hidden by default) --}}
                    <div id="forgot-password-box" class="hidden rounded-xl bg-slate-50 border border-slate-200 p-3 text-xs text-slate-600">
                        <p class="font-semibold text-slate-800 mb-1">Need Password Assistance?</p>
                        <p>Please contact the University ICT Directorate or Academic Affairs Examination Office with your student ID card to request a secure password reset.</p>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                            <span class="text-xs font-medium text-slate-600">Keep me signed in</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full py-3 px-4 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-700 active:bg-brand-800 shadow-md shadow-brand-600/20 transition duration-150 ease-in-out flex items-center justify-center gap-2 group">
                            <span>Sign In to Portal</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Navigation Action Links --}}
                <div class="mt-6 pt-6 border-t border-slate-100 flex flex-col gap-3 text-center text-xs">
                    @if ($studentRegistrationOpen)
                        <p class="text-slate-600">
                            First time here?
                            <a href="{{ route('register') }}" class="font-bold text-brand-600 hover:text-brand-800 hover:underline">
                                Register as a Student
                            </a>
                        </p>
                    @endif

                    <div class="pt-2 border-t border-slate-100">
                        <a href="{{ route('staff.login') }}" class="inline-flex items-center gap-1.5 font-semibold text-slate-600 hover:text-brand-600 transition">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>Chief Examiner &amp; Staff Sign In &rarr;</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- Bottom Institutional Note --}}
        <p class="mt-6 text-center text-xs text-slate-500">
            Official Academic Examination Portal &bull; AMMUSTED
        </p>

    </div>
</div>

<script>
    function toggleForgotPasswordNotice() {
        const box = document.getElementById('forgot-password-box');
        box.classList.toggle('hidden');
    }
</script>
@endsection
