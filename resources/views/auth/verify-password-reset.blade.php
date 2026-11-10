@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-md py-12 px-4 sm:px-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/50">
        <div class="mb-6 text-center">
            <div class="mb-3 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-brand-600 shadow-inner">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Enter Reset Code</h1>
            <p class="mt-1 text-xs text-slate-500">Enter the six-digit code from your email. It expires after 10 minutes.</p>
        </div>

        @if (session('status'))
            <div role="status" class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-xs font-medium text-emerald-800">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div role="alert" class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs text-rose-800">
                @foreach ($errors->all() as $error)<p class="font-semibold">{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.verify') }}" class="space-y-4">
            @csrf
            <div>
                <label for="reset-email" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700">Account email</label>
                <input id="reset-email" type="email" name="email" value="{{ old('email', $email) }}" required readonly class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600">
            </div>
            <div>
                <label for="reset-pin" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700">Six-digit code</label>
                <input id="reset-pin" type="text" name="pin" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" minlength="6" maxlength="6" required autofocus class="w-full rounded-xl border border-slate-300 px-4 py-3 text-center text-lg font-bold tracking-[0.5em] text-slate-900 focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600">
                @error('pin')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="w-full rounded-xl bg-brand-600 px-4 py-3 text-sm font-bold text-white shadow-md shadow-brand-600/20 transition hover:bg-brand-700">Verify Code</button>
        </form>

        <div class="mt-6 border-t border-slate-100 pt-5 text-center">
            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-slate-600 transition hover:text-brand-600">Request a new code</a>
        </div>
    </div>
</div>
@endsection
