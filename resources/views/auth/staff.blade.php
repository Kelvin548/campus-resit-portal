@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl py-8">
    <div class="grid overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-200/50 lg:grid-cols-[0.9fr_1.1fr]">
        <aside class="bg-gradient-to-br from-brand-950 via-brand-900 to-brand-800 p-8 text-white sm:p-10 flex flex-col justify-between">
            <div>
                <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-wider text-brand-200 hover:text-white flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Back to Student Portal</span>
                </a>
                
                <div class="mt-10 inline-block bg-white p-2.5 rounded-2xl shadow-md border border-white/20">
                    <img src="{{ asset('images/usted-logo.jpg') }}" alt="USTED Logo" class="h-16 w-auto object-contain">
                </div>

                <p class="mt-6 text-xs font-bold uppercase tracking-[0.2em] text-brand-200">Staff & Examiner Workspace</p>
                <h1 class="mt-2 text-2xl sm:text-3xl font-extrabold tracking-tight">USTED Examination Management</h1>
                <p class="mt-3 text-sm leading-relaxed text-brand-100">Sign in with the authorized institutional staff account issued to you.</p>

                <div class="mt-8 space-y-3 text-xs text-brand-100 bg-black/20 p-4 rounded-2xl border border-white/10">
                    <div class="flex items-start gap-2">
                        <span class="font-bold text-white">Chief Examiner:</span>
                        <span>Manage courses, registration deadlines, approval reviews and examination timetables.</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-bold text-white">Administrator:</span>
                        <span>User accounts, staff invitations, access permissions and portal auditing.</span>
                    </div>
                </div>
            </div>

            <p class="mt-8 text-[11px] text-brand-200 font-medium">
                University of Skills Training and Entrepreneurial Development
            </p>
        </aside>

        <section class="p-6 sm:p-10 flex flex-col justify-center">
            <div class="mb-6">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-50 text-[11px] font-bold text-brand-700 tracking-wide uppercase border border-brand-200">
                    Authorized Personnel Only
                </span>
                <h2 class="mt-3 text-2xl font-bold text-slate-900 tracking-tight">Staff Sign In</h2>
                <p class="mt-1 text-sm text-slate-500">Enter your official staff credentials to access your academic workspace.</p>
            </div>

            <form method="POST" action="{{ route('staff.authenticate') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="staff-email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Staff Email Address</label>
                    <input id="staff-email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required placeholder="examiner@campus.edu" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-600 focus:border-brand-600 sm:text-sm font-medium transition shadow-xs">
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="staff-password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Password</label>
                        <button type="button" onclick="showStaffNote('Password recovery instructions will be transmitted to the verified institutional email linked to your staff profile.')" class="text-xs font-semibold text-brand-600 hover:text-brand-800 hover:underline">Forgot password?</button>
                    </div>
                    <input id="staff-password" name="password" type="password" autocomplete="current-password" required placeholder="Enter your staff password" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-600 focus:border-brand-600 sm:text-sm transition shadow-xs">
                </div>
                <div class="flex items-center justify-between text-sm pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" value="1" class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                        <span class="text-xs font-medium text-slate-600">Keep me signed in</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 px-4 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-700 active:bg-brand-800 shadow-md shadow-brand-600/20 transition flex items-center justify-center gap-2 group">
                        <span>Sign In to Staff Workspace</span>
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
                <p id="staff-note" class="hidden rounded-xl bg-brand-50 border border-brand-200 p-3 text-xs text-brand-900" role="status" aria-live="polite"></p>
            </form>

            <div class="mt-8 border-t border-slate-100 pt-5 text-center text-xs text-slate-600">
                Have a staff invitation token? <a href="{{ route('staff.invitation') }}" class="font-bold text-brand-600 hover:text-brand-800 hover:underline">Accept invitation & set up account</a>
            </div>
        </section>
    </div>
</div>
<script>
    function showStaffNote(message) {
        const note = document.getElementById('staff-note');
        note.textContent = message;
        note.classList.remove('hidden');
    }
</script>
@endsection
