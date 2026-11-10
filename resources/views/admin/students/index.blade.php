@extends('layouts.app')

@section('content')
<div class="space-y-6 py-4">
    <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-800">← Administrator dashboard</a>

    <div>
        <p class="text-sm font-semibold uppercase tracking-wider text-brand-600">Administrator</p>
        <h1 class="mt-1 text-3xl font-bold text-slate-900">Student accounts</h1>
        <p class="mt-2 text-slate-500">Search and filter student accounts by identity and registration details.</p>
    </div>

    <section class="rounded-2xl border bg-white p-5">
        <form method="GET" action="{{ route('admin.students.index') }}" class="flex flex-wrap gap-3">
            <label for="student_search" class="sr-only">Search students by name, email, or index number</label>
            <input type="search" name="student_search" id="student_search" value="{{ $studentSearch }}"
                placeholder="Name, email, or index number"
                class="min-w-56 flex-1 rounded-lg border-slate-300 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">

            <label for="academic_year" class="sr-only">Academic year</label>
            <select name="academic_year" id="academic_year" class="rounded-lg border-slate-300 text-sm shadow-sm">
                <option value="">All years</option>
                @foreach ($academicYears as $year)
                    <option value="{{ $year }}" @selected((string) $academicYear === (string) $year)>{{ $year }}</option>
                @endforeach
            </select>

            <label for="level" class="sr-only">Level</label>
            <select name="level" id="level" class="rounded-lg border-slate-300 text-sm shadow-sm">
                <option value="">All levels</option>
                @foreach ($levels as $availableLevel)
                    <option value="{{ $availableLevel }}" @selected((string) $level === (string) $availableLevel)>Level {{ $availableLevel }}</option>
                @endforeach
            </select>

            <label for="semester" class="sr-only">Semester</label>
            <select name="semester" id="semester" class="rounded-lg border-slate-300 text-sm shadow-sm">
                <option value="">All semesters</option>
                @foreach ($semesters as $availableSemester)
                    <option value="{{ $availableSemester }}" @selected($semester === $availableSemester)>{{ $availableSemester }}</option>
                @endforeach
            </select>

            <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">Search / Filter</button>
            @if ($studentSearch !== '' || $academicYear !== '' || $level !== '' || $semester !== '')
                <a href="{{ route('admin.students.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Clear filters</a>
            @endif
        </form>
        <p class="mt-4 text-sm text-slate-500">{{ $students->total() }} matching student accounts</p>
    </section>

    <div class="overflow-x-auto rounded-2xl border bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Email address</th>
                    <th class="px-6 py-3">Index number</th>
                    <th class="px-6 py-3">Account status</th>
                    <th class="px-6 py-3">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($students as $user)
                    <tr>
                        <td class="px-6 py-4 font-medium">{{ $user->full_name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">{{ $user->student_id_number }}</td>
                        <td class="px-6 py-4">
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button type="button" class="student-details-trigger text-sm font-semibold text-brand-600 hover:text-brand-800 hover:underline"
                                data-student-details="{{ json_encode($user->only(['full_name', 'email', 'phone_number', 'student_id_number', 'is_active']) + ['id' => $user->id, 'status_history' => $user->accountStatusHistory->map(fn ($event) => ['old_status' => $event->previous_status === null ? null : ($event->previous_status ? 'Active' : 'Inactive'), 'new_status' => $event->new_status ? 'Active' : 'Inactive', 'actor' => $event->changedBy?->full_name ?? 'System', 'changed_at' => $event->created_at?->format('M j, Y g:i A')])]) }}">
                                View details
                            </button>
                            <form id="student-status-form-{{ $user->id }}" method="POST" action="{{ url('/admin/students/'.$user->id.'/status') }}" class="hidden">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="is_active" value="{{ $user->is_active ? 0 : 1 }}">
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-4 text-slate-500">No student accounts match these search and filter choices.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $students->links('vendor.pagination.admin') }}</div>

    @include('admin.partials.student-details-modal')
</div>
@endsection
