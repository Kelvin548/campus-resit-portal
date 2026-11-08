@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4 sm:px-6">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 p-8 sm:p-10 text-center">
        <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4 border border-blue-200">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Supplementary Application Submitted</h1>
        <p class="text-sm text-slate-600 mt-2 max-w-md mx-auto">
            Your supplementary examination request and accompanying documentation have been submitted and placed in the Chief Examiner review queue.
        </p>

        <div class="mt-6 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 text-left max-w-md mx-auto space-y-2">
            <div class="font-bold text-slate-800">What happens next?</div>
            <div class="flex items-start gap-2">
                <span class="w-4 h-4 rounded-full bg-brand-100 text-brand-700 font-bold flex items-center justify-center text-[10px] shrink-0 mt-0.5">1</span>
                <span>The Chief Examiner will verify your uploaded medical report and formal justification.</span>
            </div>
            <div class="flex items-start gap-2">
                <span class="w-4 h-4 rounded-full bg-brand-100 text-brand-700 font-bold flex items-center justify-center text-[10px] shrink-0 mt-0.5">2</span>
                <span>Once approved, your course registration will appear as Approved on your dashboard.</span>
            </div>
            <div class="flex items-start gap-2">
                <span class="w-4 h-4 rounded-full bg-brand-100 text-brand-700 font-bold flex items-center justify-center text-[10px] shrink-0 mt-0.5">3</span>
                <span>You will then be able to print your official examination admission slip.</span>
            </div>
        </div>

        <div class="mt-8 flex justify-center">
            <a href="{{ route('student.dashboard') }}" 
               class="px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-md transition">
                Return to Student Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
