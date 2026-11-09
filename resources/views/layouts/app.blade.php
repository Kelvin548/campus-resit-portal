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
                            950: '#2b060d',
                        },
                        wine: {
                            DEFAULT: '#7a1326',
                            50: '#fdf3f4',
                            100: '#fbe6e9',
                            200: '#f7d0d7',
                            600: '#7a1326',
                            700: '#640f1f',
                            800: '#520c19',
                            900: '#440a15',
                            950: '#2b060d',
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
    <style>
        /* Official USTED Deep Wine Theme Utilities */
        .bg-usted-wine-gradient {
            background: #7a1326 linear-gradient(135deg, #440a15 0%, #640f1f 45%, #7a1326 100%) !important;
            color: #ffffff !important;
        }
        .bg-usted-wine {
            background-color: #7a1326 !important;
        }
        .bg-usted-wine-dark {
            background-color: #440a15 !important;
        }
        .text-usted-wine {
            color: #7a1326 !important;
        }
        .border-usted-wine {
            border-color: #7a1326 !important;
        }
        /* ── Navbar Professional Bottom Accent ─────────────────────────── */
        header.site-header {
            position: relative;
            border-bottom: none !important;
        }
        header.site-header::after {
            content: '';
            display: block;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #440a15 0%, #7a1326 40%, #d4af37 70%, #7a1326 100%);
            background-size: 200% auto;
            animation: navbarShimmer 4s linear infinite;
            z-index: 1;
        }
        @keyframes navbarShimmer {
            0%   { background-position: 0% center; }
            100% { background-position: 200% center; }
        }
        /* Active nav link — wine underline ONLY on selected tab */
        header nav a {
            position: relative;
        }
        header nav a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 0%;
            height: 2px;
            background: #7a1326;
            border-radius: 2px 2px 0 0;
            transition: width 0.2s ease;
        }
        header nav a[class*="bg-brand-50"]::after {
            width: 60%;
        }
        /* Inactive tabs must never show the underline */
        header nav a:not([class*="bg-brand-50"])::after {
            display: none !important;
        }
        /* Navbar link hover lift effect */
        header nav a {
            transition: all 0.18s ease;
        }
        header nav a:hover {
            transform: translateY(-1px);
        }

        /* Global Transparent USTED Background Watermark */
        .portal-watermark {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: -1;
            background-image: url("{{ asset('images/usted-logo.png') }}");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: min(500px, 82vw) auto;
            opacity: 0.065;
            filter: contrast(110%);
        }
    </style>
</head>
<body class="h-full flex flex-col text-slate-800 antialiased font-sans relative" style="isolation: isolate;">

    <!-- Central School Logo Watermark (Subtle, fixed background watermark across all layout templates) -->
    <div class="portal-watermark" aria-hidden="true"></div>

    @php($isStaffUser = auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isChiefExaminer()))
    @php($activeBroadcastCount = \App\Models\BroadcastMessage::where('is_active', true)->count())

    <!-- Top Navigation Bar -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left: Logo & Links -->
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <img src="{{ asset('images/usted-logo.png') }}" alt="USTED Logo" class="h-11 w-auto object-contain">
                        <a href="{{ auth()->check() ? ($isStaffUser ? route('chief-examiner.dashboard') : route('student.dashboard')) : (request()->routeIs('staff.*') ? route('staff.login') : route('login')) }}" class="flex flex-col">
                            <span class="font-extrabold text-base sm:text-lg text-brand-600 leading-tight tracking-tight uppercase">USTED</span>
                            <span class="text-xs text-slate-500 font-medium hidden sm:inline">Campus Resit & Supplementary Portal</span>
                        </a>
                    </div>
                    @auth
                    @if(!$isStaffUser)
                    <nav class="hidden sm:ml-8 sm:flex sm:space-x-2 items-center">
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
                        <a href="{{ route('student.announcements') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-medium transition flex items-center gap-1.5 {{ request()->routeIs('student.announcements') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span>Notices &amp; Updates</span>
                            @if($activeBroadcastCount > 0)
                                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-600 text-white">
                                    {{ $activeBroadcastCount }}
                                </span>
                            @endif
                        </a>
                    </nav>
                    @else
                    <nav class="hidden sm:ml-8 sm:flex sm:space-x-2 items-center">
                        <a href="{{ route('chief-examiner.dashboard') }}" 
                           class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200 flex items-center gap-2 {{ request()->routeIs('chief-examiner.dashboard') ? 'bg-brand-50 text-brand-700 border border-brand-200/90 shadow-xs ring-1 ring-brand-100' : 'text-slate-600 hover:text-brand-700 hover:bg-brand-50/50 hover:border-brand-200/40 border border-transparent' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('chief-examiner.dashboard') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('chief-examiner.timetables.index') }}" 
                           class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200 flex items-center gap-2 {{ request()->routeIs('chief-examiner.timetables.*') ? 'bg-brand-50 text-brand-700 border border-brand-200/90 shadow-xs ring-1 ring-brand-100' : 'text-slate-600 hover:text-brand-700 hover:bg-brand-50/50 hover:border-brand-200/40 border border-transparent' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('chief-examiner.timetables.*') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Timetable</span>
                        </a>
                        <a href="{{ route('chief-examiner.broadcasts.index') }}" 
                           class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200 flex items-center gap-2 {{ request()->routeIs('chief-examiner.broadcasts.*') ? 'bg-brand-50 text-brand-700 border border-brand-200/90 shadow-xs ring-1 ring-brand-100' : 'text-slate-600 hover:text-brand-700 hover:bg-brand-50/50 hover:border-brand-200/40 border border-transparent' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('chief-examiner.broadcasts.*') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                            <span>Broadcast</span>
                            @if($activeBroadcastCount > 0)
                                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-brand-600 text-white shadow-xs">
                                    {{ $activeBroadcastCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('chief-examiner.cycle-settings.edit') }}" 
                           class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200 flex items-center gap-2 {{ request()->routeIs('chief-examiner.cycle-settings.*') ? 'bg-brand-50 text-brand-700 border border-brand-200/90 shadow-xs ring-1 ring-brand-100' : 'text-slate-600 hover:text-brand-700 hover:bg-brand-50/50 hover:border-brand-200/40 border border-transparent' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('chief-examiner.cycle-settings.*') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Cycle Settings</span>
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
                            <div class="text-xs text-slate-500 font-medium">
                                {{ Auth::user()->isChiefExaminer() || Auth::user()->isAdmin() ? 'Chief Examination Officer' : 'Student • ' . (Auth::user()->student_id_number ?? 'ID N/A') }}
                            </div>
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
            <div id="flash-status-alert" class="auto-dismiss-alert mb-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-start justify-between gap-3 shadow-xs transition-all duration-500">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div class="font-medium leading-relaxed">{{ session('status') }}</div>
                </div>
                <button type="button" onclick="dismissFlashAlert(this)" class="text-emerald-500 hover:text-emerald-800 hover:bg-emerald-100/60 p-1 rounded-lg transition-colors flex-shrink-0" title="Dismiss notification">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="auto-dismiss-alert mb-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-start justify-between gap-3 shadow-xs transition-all duration-500">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="font-medium leading-relaxed">{{ session('error') }}</div>
                </div>
                <button type="button" onclick="dismissFlashAlert(this)" class="text-rose-500 hover:text-rose-800 hover:bg-rose-100/60 p-1 rounded-lg transition-colors flex-shrink-0" title="Dismiss notification">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        @if (session('warning'))
            <div class="auto-dismiss-alert mb-4 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-start justify-between gap-3 shadow-xs transition-all duration-500">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="font-medium leading-relaxed">{{ session('warning') }}</div>
                </div>
                <button type="button" onclick="dismissFlashAlert(this)" class="text-amber-500 hover:text-amber-800 hover:bg-amber-100/60 p-1 rounded-lg transition-colors flex-shrink-0" title="Dismiss notification">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
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
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 relative z-10">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} University of Skills Training and Entrepreneurial Development (USTED). Campus Resit & Supplementary Examination Portal.</p>
        </div>
    </footer>

    <!-- Auto-dismiss Flash Alerts (30 Seconds) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const autoDismissAlerts = document.querySelectorAll('.auto-dismiss-alert');
            autoDismissAlerts.forEach(function (alert) {
                // Automatically dismiss after 30 seconds (30,000 milliseconds)
                setTimeout(function () {
                    fadeAndRemoveAlert(alert);
                }, 30000);
            });
        });

        function dismissFlashAlert(btn) {
            const alert = btn.closest('.auto-dismiss-alert');
            if (alert) {
                fadeAndRemoveAlert(alert);
            }
        }

        function fadeAndRemoveAlert(alert) {
            if (!alert) return;
            alert.style.transition = 'opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), transform 0.6s ease, max-height 0.6s ease, margin 0.6s ease, padding 0.6s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-6px)';
            alert.style.maxHeight = '0';
            alert.style.paddingTop = '0';
            alert.style.paddingBottom = '0';
            alert.style.marginTop = '0';
            alert.style.marginBottom = '0';
            alert.style.overflow = 'hidden';
            setTimeout(function () {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 650);
        }
    </script>
</body>
</html>
