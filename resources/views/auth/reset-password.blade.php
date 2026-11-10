@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-md py-12 px-4 sm:px-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/50">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 mb-3 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Set New Password</h1>
            <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">
                Your reset code is verified. Choose a new password to finish.
            </p>
        </div>

        <!-- Error Notifications -->
        @if ($errors->any())
            <div class="mb-5 rounded-2xl bg-rose-50 border border-rose-200 p-4 text-xs text-rose-800">
                @foreach ($errors->all() as $error)
                    <p class="font-semibold">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Password Reset Form -->
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Account Email
                </label>
                <input id="email" 
                       type="email" 
                       value="{{ $email }}"
                       readonly
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 sm:text-sm font-medium transition cursor-not-allowed">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    New Password
                </label>
                <input id="password" 
                       type="password" 
                       name="password" 
                       required 
                       autofocus 
                       placeholder="••••••••"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-600 focus:border-brand-600 sm:text-sm font-medium transition shadow-xs">
                    <p class="text-[11px] text-slate-400 mt-1">Must be at least 8 characters long.</p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Confirm New Password
                </label>
                <input id="password_confirmation" 
                       type="password" 
                       name="password_confirmation" 
                       required 
                       placeholder="••••••••"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-600 focus:border-brand-600 sm:text-sm font-medium transition shadow-xs">
            </div>

            <button type="submit" 
                    class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-600/20 transition flex items-center justify-center gap-2">
                <span>Reset Password</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </button>
        </form>
    </div>
</div>
@endsection
