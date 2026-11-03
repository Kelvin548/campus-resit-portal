@extends('layouts.app')

@section('content')
<div class="relative isolate min-h-[78vh] overflow-hidden rounded-3xl">
    <div class="pointer-events-none absolute inset-0 select-none blur-sm" aria-hidden="true">
        <div class="mx-auto max-w-5xl py-8 opacity-80">
            <div class="grid overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm lg:grid-cols-[0.9fr_1.1fr]">
                <aside class="bg-slate-900 p-10 text-white"><p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-300">Staff portal</p><h2 class="mt-4 text-3xl font-bold">Resit and Supplementary Examination Management</h2><p class="mt-4 text-slate-300">Secure access for authorized staff.</p></aside>
                <section class="p-10"><p class="text-sm font-semibold text-blue-600">STAFF SIGN IN</p><h2 class="mt-2 text-2xl font-bold">Welcome back</h2><div class="mt-8 space-y-4"><div class="h-11 rounded-xl bg-slate-100"></div><div class="h-11 rounded-xl bg-slate-100"></div><div class="h-12 rounded-xl bg-blue-200"></div></div></section>
            </div>
        </div>
    </div>
    <div class="absolute inset-0 bg-slate-900/30 backdrop-blur-[3px]"></div>
    <div class="relative z-10 flex min-h-[78vh] items-center justify-center p-4 sm:p-8">
        <section class="w-full max-w-xl rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-9" aria-labelledby="invite-title">
            <div class="flex items-start justify-between gap-4">
                <div><a href="{{ route('staff.login') }}" class="text-sm font-medium text-blue-700 hover:underline">← Back to staff sign in</a><p class="mt-6 text-sm font-semibold uppercase tracking-wider text-blue-600">Staff account setup</p><h1 id="invite-title" class="mt-2 text-2xl font-bold text-slate-900">Accept your invitation</h1></div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">One-time setup</span>
            </div>
            <p class="mt-3 text-sm leading-6 text-slate-600">Enter the email address that received your invitation and the code provided by your Administrator.</p>

            @if (! $invitation)
                <p class="mt-3 text-xs text-slate-500">Use the email address and one-time code sent to you by your Administrator.</p>
                <form method="POST" action="{{ route('staff.invitation.verify') }}" class="mt-7 space-y-4">
                    @csrf
                    <div><label for="invite-email" class="mb-1 block text-sm font-medium text-slate-700">Invited email address</label><input id="invite-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required placeholder="name@example.com" class="w-full rounded-xl border-slate-300"></div>
                    <div><label for="invite-code" class="mb-1 block text-sm font-medium text-slate-700">Invitation code</label><input id="invite-code" name="invitation_code" type="text" autocomplete="one-time-code" required placeholder="Enter your invitation code" class="w-full rounded-xl border-slate-300"></div>
                    <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700">Verify invitation</button>
                </form>
            @else
                <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900" role="status">
                    <p class="font-semibold">Invitation verified</p>
                    <p class="mt-1">This invitation is for a {{ $invitation->role === 'admin' ? 'System Administrator' : 'Chief Examiner' }} account. Complete the form to set up your sign-in.</p>
                </div>
                <form method="POST" action="{{ route('staff.invitation.complete') }}" class="mt-6 space-y-4">
                    @csrf
                    <div><label for="account-name" class="mb-1 block text-sm font-medium text-slate-700">Full name</label><input id="account-name" name="full_name" type="text" value="{{ old('full_name') }}" autocomplete="name" required maxlength="255" placeholder="Enter your full name" class="w-full rounded-xl border-slate-300"></div>
                    <div><label for="account-email" class="mb-1 block text-sm font-medium text-slate-700">Email address</label><input id="account-email" type="email" value="{{ $invitation->email }}" readonly class="w-full rounded-xl border-slate-300 bg-slate-50"></div>
                    <div><label for="account-password" class="mb-1 block text-sm font-medium text-slate-700">Create password</label><input id="account-password" name="password" type="password" autocomplete="new-password" required minlength="8" placeholder="Create a password" class="w-full rounded-xl border-slate-300"></div>
                    <div><label for="account-password-confirmation" class="mb-1 block text-sm font-medium text-slate-700">Confirm password</label><input id="account-password-confirmation" name="password_confirmation" type="password" autocomplete="new-password" required minlength="8" placeholder="Enter the password again" class="w-full rounded-xl border-slate-300"></div>
                    <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700">Create staff account</button>
                </form>
            @endif
        </section>
    </div>
</div>
@endsection
