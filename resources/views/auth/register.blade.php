@extends('layouts.app')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-10 px-4">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Student Account Registration</h1>
            <p class="text-sm text-slate-500 mt-1">Register to view courses and apply for resit examinations</p>
        </div>

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label for="full_name" class="block text-sm font-medium text-slate-700 mb-1">Full Legal Name</label>
                <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required autofocus
                       placeholder="e.g. John K. Mensah"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="student_id_number" class="block text-sm font-medium text-slate-700 mb-1">Student Index / ID</label>
                    <input type="text" id="student_id_number" name="student_id_number" value="{{ old('student_id_number') }}" required
                           placeholder="e.g. STU-2026-042"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm">
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-slate-700 mb-1">Mobile Money Number</label>
                    <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') }}"
                           placeholder="e.g. 0244123456"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm">
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">University / Personal Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       placeholder="student@campus.edu"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required minlength="6"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="6"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm">
                </div>
            </div>

            <button type="submit" 
                    class="w-full py-2.5 px-4 rounded-xl font-semibold text-white bg-brand-600 hover:bg-brand-700 shadow-sm transition flex items-center justify-center gap-2 mt-2">
                <span>Create Student Account</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>

        <p class="mt-6 text-center text-xs text-slate-500">
            Already registered? 
            <a href="{{ route('login') }}" class="font-medium text-brand-600 hover:underline">Log in here</a>
        </p>
    </div>
</div>
@endsection
