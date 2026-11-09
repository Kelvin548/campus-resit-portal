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

    <!-- Help & Support Section -->
    <section class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-brand-50 border border-brand-100 text-brand-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Administrator Help &amp; Support</h2>
                    <p class="text-xs text-slate-500">Guidelines, operational procedures, and support channels for portal administrators.</p>
                </div>
            </div>
            <a href="mailto:support@usted.edu.gh" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700 transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Contact Academic IT Helpdesk</span>
            </a>
        </div>

        <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-100 space-y-2">
                <div class="flex items-center gap-2 text-brand-700 font-bold text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span>Staff Invitations</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Issue single-use secure invitation tokens to newly appointed Chief Examination Officers or Administrators via email. The recipient uses the link to establish their password and activate access.
                </p>
                <div class="pt-2">
                    <a href="{{ route('admin.staff-invitations.create') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-800 hover:underline">Go to invitation form &rarr;</a>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-100 space-y-2">
                <div class="flex items-center gap-2 text-brand-700 font-bold text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Student Records</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Filter and inspect enrolled candidates by index number, academic year, course level, or semester. Review registered courses and payment statuses connected to the active supplementary examination cycle.
                </p>
                <div class="pt-2">
                    <a href="{{ route('admin.students.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-800 hover:underline">View student catalog &rarr;</a>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-100 space-y-2">
                <div class="flex items-center gap-2 text-brand-700 font-bold text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Role Boundaries</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Administrators manage accounts, staff access, and credentials. Exam schedules, timetable publishing, registration approvals, and supplementary dates are managed by the Chief Examination Officer.
                </p>
            </div>
        </div>

        <div class="mt-6 p-4 rounded-2xl bg-brand-50 border border-brand-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-brand-900">
            <div class="flex items-center gap-2">
                <span class="font-bold">Need emergency support?</span>
                <span class="text-brand-800">Academic Affairs Examination Support Desk: +233 24 000 0001 / support@usted.edu.gh</span>
            </div>
            <span class="text-[11px] text-brand-700 font-medium">Operating hours: Mon – Fri, 8:00 AM – 5:00 PM GMT</span>
        </div>
    </section>

    <section class="rounded-2xl border bg-white p-6">
        <p class="text-sm font-semibold text-slate-700">Account administration</p>
        <p class="mt-2 text-sm leading-6 text-slate-500">Account creation, activation controls, and password-reset support will be connected in later steps. Course and timetable management is handled by the Chief Examiner.</p>
    </section>
</div>
@endsection
