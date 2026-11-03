@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl py-8">
    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-violet-700 hover:underline">← Administrator dashboard</a>
    <section class="mt-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <p class="text-sm font-semibold uppercase tracking-wider text-violet-600">Account access</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-900">Invite a staff member</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">The invitation code will be emailed to the address below. It can be used once before it expires.</p>

        @if (in_array(config('mail.default'), ['log', 'array', 'failover'], true))
            <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900" role="status">Real email delivery is not configured. Set up the project mail account before sending an invitation.</div>
        @endif

        @if (session('status'))
            <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.staff-invitations.store') }}" class="mt-6 space-y-5">
            @csrf
            <div>
                <label for="invite-email" class="mb-1 block text-sm font-medium text-slate-700">Staff email address</label>
                <input id="invite-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required maxlength="255" class="w-full rounded-xl border-slate-300">
                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="invite-role" class="mb-1 block text-sm font-medium text-slate-700">Staff role</label>
                <select id="invite-role" name="role" required class="w-full rounded-xl border-slate-300">
                    <option value="">Choose a role</option>
                    <option value="chief_examiner" @selected(old('role') === 'chief_examiner')>Chief Examiner</option>
                    <option value="admin" @selected(old('role') === 'admin')>System Administrator</option>
                </select>
                @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="invite-expiry" class="mb-1 block text-sm font-medium text-slate-700">Invitation validity</label>
                <select id="invite-expiry" name="expires_in_hours" required class="w-full rounded-xl border-slate-300">
                    @foreach ([1, 6, 12, 24, 48, 72] as $hours)
                        <option value="{{ $hours }}" @selected((int) old('expires_in_hours', 24) === $hours)>{{ $hours }} {{ $hours === 1 ? 'hour' : 'hours' }}</option>
                    @endforeach
                </select>
                @error('expires_in_hours')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="w-full rounded-xl bg-violet-600 px-4 py-3 font-semibold text-white hover:bg-violet-700">Send staff invitation</button>
        </form>
    </section>
</div>
@endsection
