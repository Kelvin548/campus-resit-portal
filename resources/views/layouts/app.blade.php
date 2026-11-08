<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Campus Resit Portal' }} - Academic Examination System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Figtree', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#fdf3f4',
                            100: '#fbe6e9',
                            200: '#f7d0d7',
                            300: '#f0abb8',
                            400: '#e57b8e',
                            500: '#a31d36',
                            600: '#7a1326', // Official USTED deep wine
                            700: '#640f1f',
                            800: '#520c19',
                            900: '#440a15',
                        },
                        wine: {
                            DEFAULT: '#7a1326',
                            50: '#fdf3f4',
                            100: '#fbe6e9',
                            600: '#7a1326',
                            700: '#640f1f',
                            800: '#520c19',
                            900: '#440a15',
                        },
                        gold: {
                            DEFAULT: '#d4af37',
                            50: '#fefdf7',
                            100: '#fdf9e6',
                            500: '#d4af37',
                            600: '#b89222',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full flex flex-col text-slate-800 antialiased font-sans">

    @php($isStaffUser = auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isChiefExaminer()))

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left: Logo & Links -->
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <img src="{{ asset('images/usted-logo.jpg') }}" alt="USTED Logo" class="h-11 w-auto object-contain">
                        <a href="{{ auth()->check() ? ($isStaffUser ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('chief-examiner.dashboard')) : route('student.dashboard')) : (request()->routeIs('staff.*') ? route('staff.login') : route('login')) }}" class="flex flex-col">
                            <span class="font-extrabold text-base sm:text-lg text-brand-600 leading-tight tracking-tight uppercase">USTED</span>
                            <span class="text-xs text-slate-500 font-medium hidden sm:inline">Campus Resit & Supplementary Portal</span>
                        </a>
                    </div>
                    @auth
                    @if(!$isStaffUser)
                    <nav class="hidden sm:ml-8 sm:flex sm:space-x-4 items-center">
                        <a href="{{ route('student.dashboard') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('student.dashboard') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('student.registration') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('student.registration') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Registration
                        </a>
                        <a href="{{ route('student.timetable') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('student.timetable') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Exam Timetable
                        </a>
                    </nav>
                    @endif
                    @if(auth()->check() && auth()->user()->isAdmin())
                    <nav class="hidden sm:ml-8 sm:flex sm:items-center">
                        <a href="{{ route('admin.staff-invitations.create') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.staff-invitations.*') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Create Invitation
                        </a>
                    </nav>
                    @endif
                    @endauth
                </div>

                <!-- Right: Profile & Auth Actions -->
                <div class="flex items-center gap-3">
                    @auth
                        <div class="text-right hidden sm:block">
                            <div class="text-sm font-semibold text-slate-900">{{ Auth::user()->full_name }}</div>
                            <div class="text-xs text-slate-500 capitalize">{{ Auth::user()->role }} • {{ Auth::user()->student_id_number ?? 'Staff' }}</div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 border border-slate-200 hover:border-red-200 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ request()->routeIs('staff.*') ? route('staff.login') : route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-slate-900">Log In</a>
                        @if(!request()->routeIs('staff.*'))
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-lg shadow-sm transition">Sign Up</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Global Notification Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if (session('status'))
            <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-start gap-3 shadow-xs">
                <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div class="font-medium">{{ session('status') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-start gap-3 shadow-xs">
                <svg class="w-5 h-5 text-rose-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if (session('warning'))
            <div class="mb-4 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-start gap-3 shadow-xs">
                <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="font-medium">{{ session('warning') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-xs">
                <div class="font-semibold mb-1">Please review the following issues:</div>
                <ul class="list-disc list-inside text-rose-700 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} University of Skills Training and Entrepreneurial Development (USTED). Campus Resit & Supplementary Examination Portal.</p>
        </div>
    </footer>

</body>
</html>
