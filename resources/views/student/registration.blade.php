@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[70vh] py-8 px-4 sm:px-6">
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200 p-8 max-w-md w-full text-center">
        
        <div class="w-14 h-14 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mx-auto mb-4 border border-brand-200">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Exam Registration Hub</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1 mb-6">Select the category of academic examination you wish to apply for.</p>

        @if(\App\Models\SupplementaryCycle::registrationIsOpenNow())
            <div class="space-y-3 text-left">
                <a href="{{ route('student.resit-form', ['type' => 'resit']) }}"
                   class="block p-4 rounded-2xl border-2 border-brand-200 bg-brand-50/50 hover:bg-brand-50 hover:border-brand-600 transition group shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-900 group-hover:text-brand-700 text-sm">Resit Examination</span>
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-brand-100 text-brand-800">Fee Applies</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">For failed, trailing, or incomplete courses. Mobile Money payment required.</p>
                </a>

                <a href="{{ route('student.resit-form', ['type' => 'supplementary']) }}"
                   class="block p-4 rounded-2xl border-2 border-slate-200 bg-white hover:bg-slate-50 hover:border-blue-500 transition group shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-900 group-hover:text-blue-700 text-sm">Supplementary Exam / Quiz</span>
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800">Free / Exempt</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">For missed exams due to documented medical or extenuating grounds. Uploads required.</p>
                </a>
            </div>
        @else
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs mb-4">
                <div class="font-bold text-sm mb-1">Applications Closed</div>
                <p>Resit applications are currently closed. Please check back later.</p>
            </div>
            <a href="{{ route('student.dashboard') }}" class="text-xs font-bold text-brand-600 hover:underline">
                Return to Dashboard
            </a>
        @endif
    </div>
</div>
@endsection
