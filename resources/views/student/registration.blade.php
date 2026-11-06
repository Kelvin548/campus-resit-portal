@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-slate-50 py-8">
    <div class="bg-white rounded-xl shadow-xl p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold text-wine-700 mb-4 text-center">Exam Registration Hub</h2>
        <p class="text-slate-600 mb-6 text-center">Choose the type of exam you wish to apply for.</p>
        <div class="space-y-4">
            <a href="{{ route('student.resit-form', ['type' => 'resit']) }}"
               class="block w-full text-center px-4 py-2 rounded-xl bg-wine-600 hover:bg-wine-700 text-white font-medium transition">
                Register for a Resit Exam
            </a>
            <a href="{{ route('student.resit-form', ['type' => 'supplementary']) }}"
               class="block w-full text-center px-4 py-2 rounded-xl bg-wine-600 hover:bg-wine-700 text-white font-medium transition opacity-95 hover:opacity-100">
                Register for a Supplementary Exam (Free)
            </a>
        </div>
    </div>
</div>
@endsection