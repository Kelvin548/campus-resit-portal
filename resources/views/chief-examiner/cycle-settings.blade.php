@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl space-y-6 py-6">
    <!-- Header Band in USTED Wine Gradient -->
    <div class="bg-usted-wine-gradient rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4" style="background: linear-gradient(135deg, #440a15 0%, #640f1f 45%, #7a1326 100%) !important; color: #ffffff !important;">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold text-brand-200 backdrop-blur-sm mb-2">
                <span>Academic Session Controls</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Supplementary Cycle Dates &amp; Settings</h1>
            <p class="text-brand-100 text-xs sm:text-sm mt-1 max-w-xl">
                Configure student registration start and closing dates, and subsequent exam access periods.
            </p>
        </div>

        <a href="{{ route('chief-examiner.dashboard') }}" 
           class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/20 transition self-start sm:self-center">
            &larr; Back to Dashboard
        </a>
    </div>

    @if (session('status'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 font-semibold shadow-xs" role="status">
            {{ session('status') }}
        </div>
    @endif

    @if ($cycle)
        <div class="rounded-2xl border border-brand-200 bg-brand-50/60 p-4 text-xs sm:text-sm text-brand-950 shadow-xs">
            Current cycle status: <strong class="uppercase text-brand-800">{{ str_replace('_', ' ', ucfirst($phase)) }}</strong>.
            Saving while this cycle is open updates its dates. Saving after it closes archives this cycle and schedules a new one.
        </div>
    @endif

    <form method="POST" action="{{ route('chief-examiner.cycle-settings.save') }}" class="space-y-5 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs">
        @csrf
        <div>
            <label for="name" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Cycle Name</label>
            <input id="name" name="name" type="text" maxlength="120" required value="{{ old('name', $cycle?->name) }}" placeholder="Supplementary Examinations 2026/2027" 
                   class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-200">
            @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="registration_starts_at" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Student Registration Opens</label>
                <input id="registration_starts_at" name="registration_starts_at" type="datetime-local" required value="{{ old('registration_starts_at', $cycle?->registration_starts_at?->format('Y-m-d\TH:i')) }}" 
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-200">
                @error('registration_starts_at') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="registration_ends_at" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Student Registration Closes (Ghana time)</label>
                <input id="registration_ends_at" name="registration_ends_at" type="datetime-local" required value="{{ old('registration_ends_at', $cycle?->registration_ends_at?->format('Y-m-d\TH:i')) }}" 
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-200">
                <p class="mt-1 text-[11px] text-slate-400">Closing time is exclusive (23:59 or midnight).</p>
                @error('registration_ends_at') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="student_access_ends_at" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Student Access Closes (After Exams &amp; Results)</label>
            <input id="student_access_ends_at" name="student_access_ends_at" type="datetime-local" required value="{{ old('student_access_ends_at', $cycle?->student_access_ends_at?->format('Y-m-d\TH:i')) }}" 
                   class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-200">
            @error('student_access_ends_at') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('chief-examiner.dashboard') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                Cancel
            </a>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-brand-900 to-brand-700 hover:from-brand-950 hover:to-brand-800 px-6 py-2.5 text-xs font-bold text-white shadow-md transition">
                Save Examination Cycle Dates
            </button>
        </div>
    </form>
</div>
@endsection
