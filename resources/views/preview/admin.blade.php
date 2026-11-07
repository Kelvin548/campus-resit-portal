@extends('layouts.app')

@section('content')
<div class="space-y-6 py-4">
    <p class="rounded-xl border border-brand-100 bg-brand-50 px-4 py-3 text-sm text-brand-800">
        Account totals and lists are loaded from the current database.
    </p>

    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-brand-600">Administrator</p>
            <h1 class="mt-1 text-3xl font-bold text-slate-900">User and access management</h1>
            <p class="mt-2 text-slate-500">Review student and staff accounts.</p>
        </div>
        <a href="{{ route('admin.students.index') }}" class="inline-flex items-center rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
            View students
        </a>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border bg-white p-5">
            <p class="text-sm text-slate-500">Student accounts</p>
            <p class="mt-2 text-3xl font-bold">{{ $studentCount }}</p>
        </div>
        <div class="rounded-2xl border bg-white p-5">
            <p class="text-sm text-slate-500">Staff accounts</p>
            <p class="mt-2 text-3xl font-bold">{{ $chiefExaminerCount + $adminCount }}</p>
        </div>
        <div class="rounded-2xl border bg-white p-5">
            <p class="text-sm text-slate-500">Pending support</p>
            <p class="mt-2 text-3xl font-bold">03</p>
        </div>
    </div>

    <section>
        <div class="mb-3 flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold">Chief Examiner accounts</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $chiefExaminers->total() }} matching Chief Examiner accounts</p>
            </div>
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex w-full max-w-lg gap-2">
                <label for="chief_examiner_search" class="sr-only">Search Chief Examiners by name or email</label>
                <input type="search" name="chief_examiner_search" id="chief_examiner_search" value="{{ $chiefExaminerSearch }}"
                    placeholder="Search name or email" class="min-w-0 flex-1 rounded-lg border-slate-300 text-sm shadow-sm">
                <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">Search</button>
                @if ($chiefExaminerSearch !== '')
                    <a href="{{ route('admin.dashboard') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Clear</a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto rounded-2xl border bg-white">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500"><tr><th class="px-6 py-3">Name</th><th class="px-6 py-3">Email address</th><th class="px-6 py-3">Role</th><th class="px-6 py-3">Account status</th></tr></thead>
                <tbody class="divide-y">
                    @forelse ($chiefExaminers as $user)
                        <tr><td class="px-6 py-4 font-medium">{{ $user->full_name }}</td><td class="px-6 py-4">{{ $user->email }}</td><td class="px-6 py-4">Chief Examiner</td><td class="px-6 py-4 text-slate-500">Not tracked yet</td></tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-4 text-slate-500">No Chief Examiner accounts match this search.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $chiefExaminers->links('vendor.pagination.admin') }}</div>
    </section>

    <section>
        <div class="mb-3 flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold">Administrator accounts</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $administrators->total() }} matching Administrator accounts</p>
            </div>
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex w-full max-w-lg gap-2">
                <label for="admin_search" class="sr-only">Search Administrators by name or email</label>
                <input type="search" name="admin_search" id="admin_search" value="{{ $adminSearch }}"
                    placeholder="Search name or email" class="min-w-0 flex-1 rounded-lg border-slate-300 text-sm shadow-sm">
                <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">Search</button>
                @if ($adminSearch !== '')
                    <a href="{{ route('admin.dashboard') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Clear</a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto rounded-2xl border bg-white">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500"><tr><th class="px-6 py-3">Name</th><th class="px-6 py-3">Email address</th><th class="px-6 py-3">Role</th><th class="px-6 py-3">Account status</th></tr></thead>
                <tbody class="divide-y">
                    @forelse ($administrators as $user)
                        <tr><td class="px-6 py-4 font-medium">{{ $user->full_name }}</td><td class="px-6 py-4">{{ $user->email }}</td><td class="px-6 py-4">Administrator</td><td class="px-6 py-4 text-slate-500">Not tracked yet</td></tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-4 text-slate-500">No Administrator accounts match this search.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $administrators->links('vendor.pagination.admin') }}</div>
    </section>

    <section class="rounded-2xl border bg-white p-6">
        <p class="text-sm font-semibold text-slate-700">Account administration</p>
        <p class="mt-2 text-sm leading-6 text-slate-500">Account creation, activation controls, and password-reset support will be connected in later steps. Course and timetable management is handled by the Chief Examiner.</p>
    </section>
</div>
@endsection
