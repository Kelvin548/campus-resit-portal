@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-4xl space-y-6 py-4">
    <div>
        <p class="text-sm font-semibold uppercase tracking-wider text-brand-600">Administrator account</p>
        <h1 class="mt-1 text-3xl font-bold text-slate-900">My Profile</h1>
        <p class="mt-2 text-slate-500">View and update the personal details for your Administrator account.</p>
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
                    <p class="text-sm font-medium text-brand-100">System Administrator</p>
                    <h2 class="mt-1 text-2xl font-bold">{{ $admin->full_name }}</h2>
                    <p class="mt-1 text-sm text-brand-100">Manage your own contact details.</p>
                </div>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-sm font-semibold ring-1 ring-white/20">
                    <span class="h-2 w-2 rounded-full {{ $admin->is_active ? 'bg-emerald-400' : 'bg-slate-300' }}"></span>
                    {{ $admin->is_active ? 'Active account' : 'Inactive account' }}
                </span>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-900">Personal details</h3>
                <p class="mt-1 text-sm text-slate-500">Your role and account status are managed by the portal and can’t be changed here.</p>
            </div>

            <form method="POST" action="{{ url('/admin/profile') }}" class="space-y-5">
                @csrf
                @method('PATCH')

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="admin-full-name" class="mb-1.5 block text-sm font-semibold text-slate-700">Full name</label>
                        <input id="admin-full-name" name="full_name" type="text" value="{{ old('full_name', $admin->full_name) }}" required maxlength="255" autocomplete="name"
                            class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                        @error('full_name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="admin-phone-number" class="mb-1.5 block text-sm font-semibold text-slate-700">Phone number</label>
                        <input id="admin-phone-number" name="phone_number" type="tel" value="{{ old('phone_number', $admin->phone_number) }}" maxlength="30" autocomplete="tel"
                            class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                        @error('phone_number')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid gap-5 border-t border-slate-100 pt-5 sm:grid-cols-2">
                    <div>
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Email address</span>
                        <p class="rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-700">{{ $admin->email }}</p>
                        <p class="mt-1.5 text-xs text-slate-500">This remains your sign-in email until you confirm a new address.</p>
                    </div>
                    <div>
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Account role</span>
                        <p class="rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-700">Administrator</p>
                    </div>
                </div>

                <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-5">
                    <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">Save changes</button>
                </div>
            </form>

            <form method="POST" action="{{ url('/admin/profile/email') }}" class="mt-7 border-t border-slate-100 pt-6">
                @csrf
                @method('PATCH')
                <div class="max-w-xl">
                    <h3 class="text-base font-bold text-slate-900">Change email address</h3>
                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        @if ($admin->pending_email)
                            A PIN is pending for this address. Submit the same address to get a fresh PIN, or enter a different address. Your current email stays active until confirmation.
                        @else
                            We’ll send a six-digit confirmation PIN to the new address. Your current email remains active until you confirm the PIN.
                        @endif
                    </p>

                    <div class="mt-4">
                        <label for="admin-new-email" class="mb-1.5 block text-sm font-semibold text-slate-700">New email address</label>
                        <input id="admin-new-email" name="new_email" type="email" value="{{ old('new_email', $admin->pending_email) }}" required maxlength="255" autocomplete="email"
                            class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                        @error('new_email')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="mt-4 rounded-xl border border-brand-200 bg-brand-50 px-4 py-2.5 text-sm font-semibold text-brand-700 transition hover:bg-brand-100 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">{{ $admin->pending_email ? 'Send a new PIN' : 'Send confirmation PIN' }}</button>
                </div>
            </form>

            @if ($admin->pending_email)
                <form method="POST" action="{{ route('admin.profile.email.confirm') }}" class="mt-6 rounded-2xl border border-brand-100 bg-brand-50/60 p-5 sm:p-6">
                    @csrf
                    @method('PATCH')
                    <div class="max-w-xl">
                        <h3 class="text-base font-bold text-slate-900">Confirm your new email</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-600">Enter the six-digit PIN sent to <span class="font-semibold text-slate-800">{{ $admin->pending_email }}</span>. The PIN expires after 10 minutes.</p>

                        <div class="mt-4">
                            <label for="admin-email-pin" class="mb-1.5 block text-sm font-semibold text-slate-700">Confirmation PIN</label>
                            <input id="admin-email-pin" name="pin" type="text" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" minlength="6" maxlength="6" required
                                class="w-full max-w-xs rounded-xl border-slate-300 text-sm tracking-[0.3em] shadow-sm focus:border-brand-500 focus:ring-brand-500">
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
