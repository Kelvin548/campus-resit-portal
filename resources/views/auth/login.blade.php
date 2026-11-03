@extends('layouts.app')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-10 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-xl bg-brand-600 text-white flex items-center justify-center font-bold text-2xl mx-auto mb-3 shadow-sm">
                CR
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Sign in to your account</h1>
            <p class="text-sm text-slate-500 mt-1">Course Registration & Resit Examination Management</p>
        </div>

        @if ($studentRegistrationPhase === 'registration')
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900">
                <p class="font-semibold">Supplementary registration is open</p>
                <p class="mt-1 text-emerald-800">Create your account or register for supplementary courses before the registration period closes.</p>
            </div>
        @elseif ($studentRegistrationPhase === 'not_started')
            <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900" role="status">
                <p class="font-semibold">Supplementary registration has not started</p>
                <p class="mt-1 text-amber-800">Student access will open when the next supplementary cycle begins.</p>
            </div>
        @elseif ($studentRegistrationPhase === 'exam_access')
            <div class="mb-6 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900" role="status">
                <p class="font-semibold">Supplementary registration is closed</p>
                <p class="mt-1 text-blue-800">Sign in to view your examination information, including your timetable.</p>
            </div>
        @elseif ($studentRegistrationPhase === 'not_scheduled')
            <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900" role="status">
                <p class="font-semibold">The next supplementary cycle has not been scheduled</p>
                <p class="mt-1 text-amber-800">Please check again when the registration dates are announced.</p>
            </div>
        @else
            <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900" role="status">
                <p class="font-semibold">The supplementary examination cycle has ended</p>
                <p class="mt-1 text-amber-800">Student access is closed until the next cycle begins.</p>
            </div>
        @endif

        <!-- Login Form -->
        @if ($studentLoginAllowed)
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="login_identifier" class="block text-sm font-medium text-slate-700 mb-1">Email Address or 10-digit Student Index</label>
                <input type="text" id="login_identifier" name="login_identifier" value="{{ old('login_identifier', 'student1@campus.edu') }}" required autofocus autocomplete="username"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                </div>
                <input type="password" id="password" name="password" value="password123" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm">
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                    <span class="text-slate-600">Remember me</span>
                </label>
            </div>

            <button type="submit" 
                    class="w-full py-2.5 px-4 rounded-xl font-semibold text-white bg-brand-600 hover:bg-brand-700 shadow-sm transition flex items-center justify-center gap-2">
                <span>Sign In</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>

        <!-- Quick Fill Credentials Box -->
        <div class="mt-6 pt-6 border-t border-slate-100">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">One-Click Test Accounts (Demo)</p>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <button type="button" onclick="fillCreds('student1@campus.edu', 'password123')" class="p-2 rounded-lg bg-slate-50 hover:bg-brand-50 hover:text-brand-700 border border-slate-200 text-left transition font-medium">🎓 Student 1</button>
                <button type="button" onclick="fillCreds('student2@campus.edu', 'password123')" class="p-2 rounded-lg bg-slate-50 hover:bg-brand-50 hover:text-brand-700 border border-slate-200 text-left transition font-medium">🎓 Student 2</button>
                <button type="button" onclick="fillCreds('examiner@campus.edu', 'password123')" class="p-2 rounded-lg bg-slate-50 hover:bg-purple-50 hover:text-purple-700 border border-slate-200 text-left transition font-medium">👨‍🏫 Chief Examiner</button>
                <button type="button" onclick="fillCreds('admin@campus.edu', 'password123')" class="p-2 rounded-lg bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 border border-slate-200 text-left transition font-medium">🛡️ Administrator</button>
            </div>
        </div>
        @endif

        @if ($studentRegistrationOpen)
            <p class="mt-6 text-center text-xs text-slate-500">
                Don't have a student account?
                <a href="{{ route('register') }}" class="font-medium text-brand-600 hover:underline">Register here</a>
            </p>
        @endif
    </div>
</div>
<script>
    @if ($studentLoginAllowed)
        function fillCreds(identifier, password) {
            document.getElementById('login_identifier').value = identifier;
            document.getElementById('password').value = password;
        }
    @endif
</script>
@endsection
