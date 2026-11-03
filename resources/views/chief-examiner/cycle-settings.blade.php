@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl space-y-6 py-8">
    <div>
        <p class="text-sm font-semibold uppercase tracking-wider text-blue-600">Chief Examiner</p>
        <h1 class="mt-1 text-3xl font-bold text-slate-900">Supplementary cycle dates</h1>
        <p class="mt-2 text-slate-600">Set the student registration period and the date student access closes after results are complete.</p>
    </div>

    @if (session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">{{ session('status') }}</div>
    @endif

    @if ($cycle)
        <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900">
            Current cycle status: <strong>{{ str_replace('_', ' ', ucfirst($phase)) }}</strong>.
            Saving while this cycle is open updates its dates. Saving after it closes starts a new cycle and archives this one.
        </div>
    @endif

    <form method="POST" action="{{ route('chief-examiner.cycle-settings.save') }}" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        <div>
            <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Cycle name</label>
            <input id="name" name="name" type="text" maxlength="120" required value="{{ old('name', $cycle?->name) }}" placeholder="Supplementary Examinations 2026/2027" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="registration_starts_at" class="mb-1 block text-sm font-medium text-slate-700">Student registration opens</label>
            <input id="registration_starts_at" name="registration_starts_at" type="datetime-local" required value="{{ old('registration_starts_at', $cycle?->registration_starts_at?->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            @error('registration_starts_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="registration_ends_at" class="mb-1 block text-sm font-medium text-slate-700">Student registration closes (Ghana time)</label>
            <input id="registration_ends_at" name="registration_ends_at" type="datetime-local" required value="{{ old('registration_ends_at', $cycle?->registration_ends_at?->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            <p class="mt-1 text-xs text-slate-500">The closing time is exclusive. To include a full final date, set the close time to midnight at the start of the following day.</p>
            @error('registration_ends_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="student_access_ends_at" class="mb-1 block text-sm font-medium text-slate-700">Student access closes after exams and results</label>
            <input id="student_access_ends_at" name="student_access_ends_at" type="datetime-local" required value="{{ old('student_access_ends_at', $cycle?->student_access_ends_at?->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            @error('student_access_ends_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Save cycle dates</button>
    </form>
</div>
@endsection
