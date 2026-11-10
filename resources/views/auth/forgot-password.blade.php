@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-md py-12 px-4 sm:px-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/50">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-brand-50 text-brand-600 mb-3 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Forgot Password?</h1>
            <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">
                Enter the email address registered to your student or staff account. If an account matches, we’ll email a one-time reset code.
            </p>
        </div>

        <!-- Success Status Notice -->
        @if (session('status'))
            <div class="mb-5 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-xs text-emerald-800">
                <div class="flex items-start gap-2.5">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <span class="font-bold">Email Sent!</span> {{ session('status') }}
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Notification -->
        @if ($errors->any())
            <div class="mb-5 rounded-2xl bg-rose-50 border border-rose-200 p-4 text-xs text-rose-800">
                @foreach ($errors->all() as $error)
                    <p class="font-semibold">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Request Form -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Account Email Address
                </label>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       placeholder="you@example.com"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-600 focus:border-brand-600 sm:text-sm font-medium transition shadow-xs">
            </div>

            <button type="submit" 
                    class="w-full py-3 px-4 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-md shadow-brand-600/20 transition flex items-center justify-center gap-2">
                <span>Send Reset Code</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>

        <!-- Back to Login -->
        <div class="mt-6 text-center border-t border-slate-100 pt-5">
            <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-600 hover:text-brand-600 flex items-center justify-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back to Sign In</span>
            </a>
        </div>
    </div>
</div>
@endsection
