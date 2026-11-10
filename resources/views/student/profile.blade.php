@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-4xl space-y-6 py-4">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-brand-600">Student account</p>
            <h1 class="mt-1 text-3xl font-bold text-slate-900">My Profile</h1>
            <p class="mt-2 text-slate-500">View and update your contact details and sign-in email.</p>
        </div>
        <a href="{{ route('student.dashboard') }}" class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Back to dashboard</a>
    </div>

    @if (session('status'))
        <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">{{ session('error') }}</div>
    @endif

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="px-6 py-6 text-white sm:px-8" style="background: linear-gradient(135deg, #440a15 0%, #640f1f 45%, #7a1326 100%) !important;">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-brand-100">Student</p>
                    <h2 class="mt-1 text-2xl font-bold">{{ $student->full_name }}</h2>
                    <p class="mt-1 text-sm text-brand-100">Index number: {{ $student->student_id_number ?? 'Not provided' }}</p>
                </div>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-sm font-semibold ring-1 ring-white/20">
                    <span class="h-2 w-2 rounded-full {{ $student->is_active ? 'bg-emerald-400' : 'bg-slate-300' }}"></span>
                    {{ $student->is_active ? 'Active account' : 'Inactive account' }}
                </span>
            </div>
        </div>

        <div class="space-y-7 p-6 sm:p-8">
            <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-5">
                @csrf
                @method('PATCH')
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Personal details</h3>
                    <p class="mt-1 text-sm text-slate-500">Update your name and phone number. Your index number, role, and account status are read-only.</p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="student-full-name" class="mb-1.5 block text-sm font-semibold text-slate-700">Full name</label>
                        <input id="student-full-name" name="full_name" type="text" value="{{ old('full_name', $student->full_name) }}" required maxlength="255" autocomplete="name" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                        @error('full_name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="student-phone-number" class="mb-1.5 block text-sm font-semibold text-slate-700">Phone number</label>
                        <input id="student-phone-number" name="phone_number" type="tel" value="{{ old('phone_number', $student->phone_number) }}" maxlength="30" autocomplete="tel" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                        @error('phone_number')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid gap-4 border-t border-slate-100 pt-5 sm:grid-cols-3">
                    <div><span class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Index number</span><p class="mt-1 text-sm font-semibold text-slate-900">{{ $student->student_id_number ?: 'Not provided' }}</p></div>
                    <div><span class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Role</span><p class="mt-1 text-sm font-semibold text-slate-900">Student</p></div>
                    <div><span class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Account status</span><p class="mt-1 text-sm font-semibold {{ $student->is_active ? 'text-emerald-700' : 'text-slate-600' }}">{{ $student->is_active ? 'Active' : 'Inactive' }}</p></div>
                </div>

                <div class="flex justify-end border-t border-slate-100 pt-5">
                    <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">Save personal details</button>
                </div>
            </form>

            <form method="POST" action="{{ route('student.profile.email.request') }}" class="border-t border-slate-100 pt-6">
                @csrf
                @method('PATCH')
                <div class="max-w-xl">
                    <h3 class="text-base font-bold text-slate-900">Change email address</h3>
                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        @if ($student->pending_email)
                            A PIN is pending for this address. Submit the same address to get a fresh PIN, or enter a different address. Your current email stays active until confirmation.
                        @else
                            We’ll send a six-digit confirmation PIN to the new address. Your current email remains active until you confirm the PIN.
                        @endif
                    </p>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Current email</label>
                            <p class="rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-700">{{ $student->email }}</p>
                        </div>
                        <div>
                            <label for="student-new-email" class="mb-1.5 block text-sm font-semibold text-slate-700">New email address</label>
                            <input id="student-new-email" name="new_email" type="email" value="{{ old('new_email', $student->pending_email) }}" required maxlength="255" autocomplete="email" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                            @error('new_email')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <button type="submit" class="mt-4 rounded-xl border border-brand-200 bg-brand-50 px-4 py-2.5 text-sm font-semibold text-brand-700 transition hover:bg-brand-100 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">{{ $student->pending_email ? 'Send a new PIN' : 'Send confirmation PIN' }}</button>
                </div>
            </form>

            @if ($student->pending_email)
                <form method="POST" action="{{ route('student.profile.email.confirm') }}" class="rounded-2xl border border-brand-100 bg-brand-50/60 p-5 sm:p-6">
                    @csrf
                    @method('PATCH')
                    <div class="max-w-xl">
                        <h3 class="text-base font-bold text-slate-900">Confirm your new email</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-600">Enter the six-digit PIN sent to <span class="font-semibold text-slate-800">{{ $student->pending_email }}</span>. The PIN expires after 10 minutes.</p>
                        <div class="mt-4">
                            <label for="student-email-pin" class="mb-1.5 block text-sm font-semibold text-slate-700">Confirmation PIN</label>
                            <input id="student-email-pin" name="pin" type="text" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" minlength="6" maxlength="6" required class="w-full max-w-xs rounded-xl border-slate-300 text-sm tracking-[0.3em] shadow-sm focus:border-brand-500 focus:ring-brand-500">
                            @error('pin')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="mt-4 rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">Confirm email address</button>
                    </div>
                </form>
            @endif
        </div>
    </section>
</div>
@endsection
