@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl py-8">
    <div class="grid overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm lg:grid-cols-[0.9fr_1.1fr]">
        <aside class="bg-slate-900 p-8 text-white sm:p-10">
            <a href="{{ route('login') }}" class="text-sm font-semibold text-blue-200 hover:text-white">← Student sign in</a>
            <div class="mt-16 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-500 text-2xl font-bold">CR</div>
            <p class="mt-8 text-sm font-semibold uppercase tracking-[0.2em] text-blue-300">Staff portal</p>
            <h1 class="mt-3 text-3xl font-bold">Resit and Supplementary Examination Management</h1>
            <p class="mt-4 leading-7 text-slate-300">Sign in with the staff account issued to you by the institution.</p>
            <div class="mt-10 space-y-3 text-sm text-slate-300">
                <p>Chief Examiner · resit and supplementary courses, fees, registration dates and timetables</p>
                <p>Administrator · user accounts, access roles and account support</p>
            </div>
        </aside>
        <section class="p-6 sm:p-10">
            <p class="mb-5 text-xs text-slate-500">Demonstration screen · sign-in and account actions are not connected yet.</p>
            <div class="mt-8">
                <h2 class="mt-1 text-2xl font-bold text-slate-900">Staff sign in</h2>
                <p class="mt-2 text-sm text-slate-500">Sign in with your authorized staff account. You will be taken to the workspace assigned to your role.</p>
            </div>
            <form method="POST" action="{{ route('staff.authenticate') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="staff-email" class="mb-1 block text-sm font-medium text-slate-700">Email address</label>
                    <input id="staff-email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required placeholder="name@example.com" class="w-full rounded-xl border-slate-300">
                </div>
                <div>
                    <label for="staff-password" class="mb-1 block text-sm font-medium text-slate-700">Password</label>
                    <input id="staff-password" name="password" type="password" autocomplete="current-password" required placeholder="Enter your password" class="w-full rounded-xl border-slate-300">
                </div>
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-slate-600"><input type="checkbox" name="remember" value="1" class="rounded border-slate-300"> Remember me</label>
                    <button type="button" onclick="showStaffNote('Password recovery will be available through the verified email address on your account.')" class="font-medium text-blue-700 hover:underline">Forgot password?</button>
                </div>
                <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700">Sign in</button>
                <p id="staff-note" class="hidden rounded-xl bg-blue-50 p-3 text-sm text-blue-800" role="status" aria-live="polite"></p>
            </form>
            <div class="mt-6 border-t border-slate-100 pt-5 text-center text-sm text-slate-600">
                Have an invitation? <a href="{{ route('staff.invitation') }}" class="font-semibold text-blue-700 hover:underline">Accept invitation and set up your account</a>
            </div>
        </section>
    </div>
</div>
<script>
    function showStaffNote(message) {
        const note = document.getElementById('staff-note');
        note.textContent = message;
        note.classList.remove('hidden');
    }
</script>
@endsection
