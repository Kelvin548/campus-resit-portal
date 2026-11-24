@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4 sm:px-6">

    <!-- Breadcrumb -->
    <div class="mb-4">
        <a href="{{ route('student.dashboard') }}" class="text-xs font-semibold text-brand-600 hover:underline flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Back to Student Dashboard</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200 overflow-hidden">
        
        <!-- Header Band -->
        <div class="bg-usted-wine-gradient p-6 sm:p-8 text-white" style="background: linear-gradient(135deg, #440a15 0%, #640f1f 45%, #7a1326 100%) !important; color: #ffffff !important;">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold text-brand-200 mb-2">
                <span>{{ $examType === 'supplementary' ? 'Official Supplementary Application' : 'Official Resit Examination Application' }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                {{ $examType === 'supplementary' ? 'Supplementary Examination Application' : 'Resit Examination Application' }}
            </h1>
            <p class="text-brand-100 text-xs sm:text-sm mt-1 max-w-xl">
                Select your academic courses from the official Department of ICT Education course catalog.
            </p>
        </div>

        @if ($errors->any())
            <div class="m-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                <div class="font-bold mb-1">Please address the following items:</div>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('student.resit-form.submit') }}" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
            @csrf
            <input type="hidden" name="exam_type" id="exam_type" value="{{ $examType }}">

            <!-- Section 1: Candidate Particulars -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-brand-600"></span>
                    Candidate Information
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1" for="name">Full Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $student->full_name ?? '') }}" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-medium focus:ring-2 focus:ring-brand-600 focus:border-brand-600" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1" for="index_number">Index / Student ID Number *</label>
                        <input type="text" name="index_number" id="index_number" value="{{ old('index_number', $student->student_id_number ?? '') }}" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 font-mono text-sm font-medium focus:ring-2 focus:ring-brand-600 focus:border-brand-600" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1" for="department">Department *</label>
                        <input type="text" name="department" id="department" value="{{ old('department', 'Department of ICT Education') }}" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1" for="programme">Programme of Study *</label>
                        <input type="text" name="programme" id="programme" value="{{ old('programme', 'B.Sc. Information Technology Education') }}" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1" for="level">Current Level *</label>
                        <select name="level" id="level" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600" required>
                            <option value="100" {{ old('level') == '100' ? 'selected' : '' }}>Level 100</option>
                            <option value="200" {{ old('level', '200') == '200' ? 'selected' : '' }}>Level 200</option>
                            <option value="300" {{ old('level') == '300' ? 'selected' : '' }}>Level 300</option>
                            <option value="400" {{ old('level') == '400' ? 'selected' : '' }}>Level 400</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1" for="telephone">Mobile Phone Number *</label>
                        <input type="text" name="telephone" id="telephone" value="{{ old('telephone', $student->phone_number ?? '') }}" placeholder="0244123456" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1" for="year_entry">Year of Entry *</label>
                        <input type="number" name="year_entry" id="year_entry" min="2018" max="2030" value="{{ old('year_entry', 2024) }}" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1" for="year_completion">Expected Year of Completion *</label>
                        <input type="number" name="year_completion" id="year_completion" min="2020" max="2035" value="{{ old('year_completion', 2028) }}" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600" required>
                    </div>
                </div>
            </div>

            <!-- Section 2: Course Selection (Dynamic Searchable Dropdown) -->
            <div class="pt-4 border-t border-slate-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-brand-600"></span>
                        Course Selection (USTED ICT Education Courses)
                    </h3>
                    <span class="text-xs text-slate-500">
                        {{ $courses->count() }} Active Courses in Database
                    </span>
                </div>

                <!-- Instant Course Search & Fetch Engine -->
                <div class="mb-5 bg-gradient-to-br from-brand-50/70 via-slate-50 to-brand-50/40 p-4 rounded-2xl border-2 border-brand-200/80 shadow-xs">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-800 flex items-center gap-1.5" for="course_search">
                            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Instant Course Search &amp; Auto-Select:</span>
                        </label>
                        <span class="text-[11px] text-brand-700 font-semibold">Type course code or name to fetch</span>
                    </div>

                    <div class="relative">
                        <input type="text" id="course_search" 
                               placeholder="Type course code or name (e.g. ICT131, Database, Programming, Web)..." 
                               oninput="handleCourseSearchInput(this.value)"
                               onkeydown="handleCourseSearchKeydown(event)"
                               autocomplete="off"
                               class="w-full pl-9 pr-10 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm font-medium focus:ring-2 focus:ring-brand-600 focus:border-brand-600 bg-white shadow-2xs">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <button type="button" id="clear_course_search_btn" onclick="clearCourseSearchBox()" 
                                class="hidden absolute right-3 top-2.5 text-slate-400 hover:text-slate-700 p-0.5 rounded-md transition" title="Clear search">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Instant Search Results Panel -->
                    <div id="course_search_results_panel" class="hidden mt-2.5 bg-white rounded-2xl border border-brand-200 shadow-md p-3 max-h-60 overflow-y-auto">
                        <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-100 text-[11px] font-semibold text-slate-500">
                            <span id="course_search_results_count">Matches</span>
                            <span class="text-slate-400">Click a course below to select it</span>
                        </div>
                        <div id="course_search_results_list" class="space-y-1.5">
                            <!-- Dynamically generated course result items -->
                        </div>
                    </div>

                    <!-- Search Feedback Confirmation Banner -->
                    <div id="course_search_feedback" class="hidden mt-2.5 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-800 flex items-center justify-between shadow-2xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span id="course_search_feedback_text"></span>
                        </div>
                        <button type="button" onclick="this.parentElement.classList.add('hidden')" class="text-emerald-600 hover:text-emerald-900 font-bold">&times;</button>
                    </div>
                </div>

                <div id="course_dropdowns_container" class="space-y-3">
                    <div class="course-entry-row bg-white p-3.5 rounded-2xl border border-slate-200 shadow-2xs">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Course 1 *
                        </label>
                        <select name="course_ids[]" class="course-dropdown w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-medium focus:ring-2 focus:ring-brand-600 focus:border-brand-600" required onchange="calculateTotalFee()">
                            <option value="" disabled selected>-- Select an Official USTED Course --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" 
                                        data-fee="{{ $course->registration_fee }}"
                                        data-code="{{ $course->course_code }}"
                                        data-name="{{ $course->course_name }}">
                                    {{ $course->course_code }}: {{ $course->course_name }} ({{ $course->credit_hours }} Credits • GHS {{ number_format($course->registration_fee, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-3 flex items-center justify-between">
                    <button type="button" id="add_another_course_btn" onclick="addCourseDropdown()"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-700 hover:text-brand-900 bg-brand-50 hover:bg-brand-100 px-3 py-1.5 rounded-lg border border-brand-200 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Add Another Course / Paper</span>
                    </button>
                    <span id="course_count_display" class="text-xs text-slate-500">1 course selected</span>
                </div>
            </div>

            <!-- Fee Summary Card -->
            <div class="p-4 rounded-2xl {{ $examType === 'supplementary' ? 'bg-blue-50 border border-blue-200' : 'bg-emerald-50 border border-emerald-200' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold {{ $examType === 'supplementary' ? 'text-blue-800' : 'text-emerald-800' }}">
                            {{ $examType === 'supplementary' ? 'Supplementary Fee Waiver' : 'Total Resit Examination Fee Payable' }}
                        </span>
                        <p class="text-xs {{ $examType === 'supplementary' ? 'text-blue-600' : 'text-emerald-600' }} mt-0.5">
                            {{ $examType === 'supplementary' ? 'Supplementary exams for approved medical/extenuating reasons have no charges.' : 'Payable directly with Mobile Money (MTN, Telecel, AT) upon submission.' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-black {{ $examType === 'supplementary' ? 'text-blue-800' : 'text-emerald-800' }}">
                            GHS <span id="total_fee_display">{{ $examType === 'supplementary' ? '0.00' : '150.00' }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Section 3: Supplementary Exam Uploads (Only for Supplementary) -->
            @if($examType === 'supplementary')
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-blue-800 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    Supplementary Verification Documentation (Required)
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1" for="medical_report">
                            Medical Report (PDF / JPG / PNG, Max 2MB) *
                        </label>
                        <input type="file" name="medical_report" id="medical_report" required
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 border border-slate-300 rounded-xl p-1.5">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1" for="letter">
                            Formal Justification Letter (PDF / DOC, Max 2MB) *
                        </label>
                        <input type="file" name="letter" id="letter" required
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 border border-slate-300 rounded-xl p-1.5">
                    </div>
                </div>
            </div>
            @endif

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('student.dashboard') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
                    Cancel &amp; Return
                </a>
                <button type="submit" 
                        class="px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-sm shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                    <span>Submit &amp; Proceed to Next Step</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>

    </div>

    <!-- Student Application Help & Guidance Box -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xs">
        <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
            <div class="w-9 h-9 rounded-xl bg-brand-50 border border-brand-100 text-brand-700 flex items-center justify-center font-bold">
                <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900">Application Help &amp; Step-by-Step Instructions</h3>
                <p class="text-xs text-slate-500">How to use this registration form and complete your paper entry.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-slate-600">
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                <span class="font-bold text-slate-900 flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-brand-100 text-brand-800 text-[11px] font-bold flex items-center justify-center">1</span>
                    Searching for Courses
                </span>
                <p class="leading-relaxed">
                    Type your course code (e.g. <code class="bg-white px-1.5 py-0.5 rounded border text-brand-700 font-mono font-bold">ICT131</code>) or title in the search box. The search engine instantly fetches the course out. Click <strong>Select</strong> or press <strong>Enter</strong> to automatically choose that course.
                </p>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                <span class="font-bold text-slate-900 flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-brand-100 text-brand-800 text-[11px] font-bold flex items-center justify-center">2</span>
                    Multiple Course Papers
                </span>
                <p class="leading-relaxed">
                    If you are sitting for more than one resit/supplementary exam, click <strong>"+ Add Another Course / Paper"</strong>. You can search again to auto-fill Course 2, Course 3, etc. Total fees update dynamically.
                </p>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                <span class="font-bold text-slate-900 flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-brand-100 text-brand-800 text-[11px] font-bold flex items-center justify-center">3</span>
                    Submitting &amp; Payment
                </span>
                <p class="leading-relaxed">
                    <strong>Resit Exams:</strong> Proceed to instant Mobile Money payment prompt (MTN, Telecel, AT).<br>
                    <strong>Supplementary:</strong> Submit your medical report &amp; formal justification letter for Chief Examiner approval (fees 100% waived).
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    const isSupplementary = "{{ $examType }}" === 'supplementary';
    const allCoursesData = @json($courses);
    let courseIndex = 1;

    // Course Search Engine - Instant Search & Auto-Select
    function handleCourseSearchInput(query) {
        const trimmed = (query || '').trim().toLowerCase();
        const clearBtn = document.getElementById('clear_course_search_btn');
        const resultsPanel = document.getElementById('course_search_results_panel');
        const resultsList = document.getElementById('course_search_results_list');
        const resultsCount = document.getElementById('course_search_results_count');

        if (clearBtn) {
            if (trimmed.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        }

        if (!trimmed) {
            if (resultsPanel) resultsPanel.classList.add('hidden');
            restoreAllDropdownOptions();
            return;
        }

        // Find matches by course_code or course_name
        const matches = allCoursesData.filter(function(c) {
            const code = (c.course_code || '').toLowerCase();
            const name = (c.course_name || '').toLowerCase();
            return code.indexOf(trimmed) !== -1 || name.indexOf(trimmed) !== -1;
        });

        // Render matching courses in results panel
        if (resultsPanel && resultsList) {
            resultsPanel.classList.remove('hidden');
            resultsList.innerHTML = '';

            if (matches.length === 0) {
                if (resultsCount) resultsCount.textContent = '0 matches found';
                resultsList.innerHTML = '<div class="p-3 text-center text-xs text-slate-400">No courses match "' + escapeHtml(query) + '". Please verify the course code or name.</div>';
            } else {
                if (resultsCount) resultsCount.textContent = matches.length + ' matching course' + (matches.length === 1 ? '' : 's') + ' found:';

                matches.forEach(function(course) {
                    const item = document.createElement('div');
                    item.className = 'p-2.5 rounded-xl border border-slate-100 hover:border-brand-300 bg-slate-50/70 hover:bg-brand-50/80 transition cursor-pointer flex items-center justify-between gap-3 group';
                    
                    item.onclick = function() {
                        selectFetchedCourse(course, true);
                    };

                    item.innerHTML = `
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-brand-100 text-brand-900 border border-brand-200 flex-shrink-0">
                                ${escapeHtml(course.course_code)}
                            </span>
                            <div class="truncate">
                                <span class="text-xs font-bold text-slate-900 group-hover:text-brand-900">${escapeHtml(course.course_name)}</span>
                                <span class="text-[11px] text-slate-500 block">${course.credit_hours} Credits • GHS ${parseFloat(course.registration_fee).toFixed(2)}</span>
                            </div>
                        </div>
                        <button type="button" class="px-3 py-1 rounded-lg bg-brand-600 group-hover:bg-brand-700 text-white font-bold text-xs flex-shrink-0 shadow-2xs">
                            Select Course
                        </button>
                    `;
                    resultsList.appendChild(item);
                });
            }
        }

        // If user typed an exact course code (e.g. ICT131), auto-select it immediately!
        const exactMatch = matches.find(function(c) {
            return (c.course_code || '').toLowerCase() === trimmed;
        });
        if (exactMatch && trimmed.length >= 5) {
            selectFetchedCourse(exactMatch, false);
        }

        // Filter the native select dropdowns
        filterDropdownSelectOptions(trimmed);
    }

    function handleCourseSearchKeydown(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            const input = document.getElementById('course_search');
            const trimmed = (input ? input.value : '').trim().toLowerCase();
            if (!trimmed) return;

            const matches = allCoursesData.filter(function(c) {
                const code = (c.course_code || '').toLowerCase();
                const name = (c.course_name || '').toLowerCase();
                return code.indexOf(trimmed) !== -1 || name.indexOf(trimmed) !== -1;
            });

            if (matches.length > 0) {
                selectFetchedCourse(matches[0], true);
            }
        }
    }

    function selectFetchedCourse(course, closeResults) {
        if (closeResults === undefined) closeResults = true;
        
        const dropdowns = document.querySelectorAll('.course-dropdown');
        let targetDropdown = null;

        // 1. Check if course is already selected in any dropdown
        for (let i = 0; i < dropdowns.length; i++) {
            if (dropdowns[i].value === String(course.id)) {
                showSearchFeedback('Course ' + course.course_code + ' is already selected in your paper list.');
                highlightDropdown(dropdowns[i]);
                if (closeResults) closeSearchPanel();
                return;
            }
        }

        // 2. Find first empty / unselected dropdown
        for (let i = 0; i < dropdowns.length; i++) {
            if (!dropdowns[i].value) {
                targetDropdown = dropdowns[i];
                break;
            }
        }

        // 3. If all current dropdowns are filled, automatically add another course slot!
        if (!targetDropdown) {
            addCourseDropdown();
            const updatedDropdowns = document.querySelectorAll('.course-dropdown');
            targetDropdown = updatedDropdowns[updatedDropdowns.length - 1];
        }

        // Ensure option exists in target dropdown and select it
        ensureCourseOptionExists(targetDropdown, course);
        targetDropdown.value = String(course.id);

        // Recalculate fees & update count
        calculateTotalFee();
        updateCourseCount();

        // Highlight the row visually
        highlightDropdown(targetDropdown);

        // Display confirmation feedback
        showSearchFeedback('✓ Successfully Fetched & Selected: ' + course.course_code + ' — ' + course.course_name);

        if (closeResults) {
            closeSearchPanel();
        }
    }

    function ensureCourseOptionExists(dropdown, course) {
        let exists = false;
        for (let i = 0; i < dropdown.options.length; i++) {
            if (dropdown.options[i].value === String(course.id)) {
                exists = true;
                dropdown.options[i].hidden = false;
                dropdown.options[i].disabled = false;
                break;
            }
        }
        if (!exists) {
            const newOpt = document.createElement('option');
            newOpt.value = String(course.id);
            newOpt.dataset.fee = course.registration_fee;
            newOpt.dataset.code = course.course_code;
            newOpt.dataset.name = course.course_name;
            newOpt.textContent = course.course_code + ': ' + course.course_name + ' (' + course.credit_hours + ' Credits • GHS ' + parseFloat(course.registration_fee).toFixed(2) + ')';
            dropdown.appendChild(newOpt);
        }
    }

    function highlightDropdown(dropdown) {
        const row = dropdown.closest('.course-entry-row');
        if (row) {
            row.scrollIntoView({ behavior: 'smooth', block: 'center' });
            row.classList.add('ring-2', 'ring-brand-500', 'bg-brand-50/50');
            setTimeout(function() {
                row.classList.remove('ring-2', 'ring-brand-500', 'bg-brand-50/50');
            }, 1800);
        }
    }

    function showSearchFeedback(text) {
        const feedbackBox = document.getElementById('course_search_feedback');
        const feedbackText = document.getElementById('course_search_feedback_text');
        if (feedbackBox && feedbackText) {
            feedbackText.textContent = text;
            feedbackBox.classList.remove('hidden');
        }
    }

    function closeSearchPanel() {
        const resultsPanel = document.getElementById('course_search_results_panel');
        if (resultsPanel) resultsPanel.classList.add('hidden');
    }

    function clearCourseSearchBox() {
        const input = document.getElementById('course_search');
        if (input) {
            input.value = '';
            input.focus();
        }
        const clearBtn = document.getElementById('clear_course_search_btn');
        if (clearBtn) clearBtn.classList.add('hidden');
        closeSearchPanel();
        restoreAllDropdownOptions();
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function filterDropdownSelectOptions(query) {
        const dropdowns = document.querySelectorAll('.course-dropdown');
        dropdowns.forEach(function(dropdown) {
            const currentVal = dropdown.value;
            for (let i = 0; i < dropdown.options.length; i++) {
                const opt = dropdown.options[i];
                if (!opt.value) continue;
                const optText = opt.textContent.toLowerCase();
                const matches = optText.indexOf(query) !== -1 || opt.value === currentVal;
                opt.hidden = !matches;
                opt.disabled = !matches;
            }
        });
    }

    function restoreAllDropdownOptions() {
        const dropdowns = document.querySelectorAll('.course-dropdown');
        dropdowns.forEach(function(dropdown) {
            for (let i = 0; i < dropdown.options.length; i++) {
                dropdown.options[i].hidden = false;
                dropdown.options[i].disabled = false;
            }
        });
    }

    // Add another course select dropdown dynamically
    function addCourseDropdown() {
        courseIndex++;
        const container = document.getElementById('course_dropdowns_container');
        const firstDropdown = container.querySelector('.course-dropdown');
        
        const row = document.createElement('div');
        row.className = 'course-entry-row bg-white p-3.5 rounded-2xl border border-slate-200 shadow-2xs relative';
        
        row.innerHTML = `
            <div class="flex items-center justify-between mb-1.5">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Course ${courseIndex} *</label>
                <button type="button" onclick="removeCourseDropdown(this)" class="text-rose-600 hover:text-rose-800 text-xs font-bold">Remove</button>
            </div>
            <select name="course_ids[]" class="course-dropdown w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-medium focus:ring-2 focus:ring-brand-600 focus:border-brand-600" required onchange="calculateTotalFee()">
                ${firstDropdown.innerHTML}
            </select>
        `;
        
        container.appendChild(row);

        // Reset the value of the newly added dropdown so it starts empty
        const newDropdown = row.querySelector('.course-dropdown');
        if (newDropdown) {
            newDropdown.value = '';
            for (let i = 0; i < newDropdown.options.length; i++) {
                newDropdown.options[i].hidden = false;
                newDropdown.options[i].disabled = false;
            }
        }

        updateCourseCount();
        calculateTotalFee();
    }

    function removeCourseDropdown(btn) {
        btn.closest('.course-entry-row').remove();
        updateCourseCount();
        calculateTotalFee();
    }

    function updateCourseCount() {
        const count = document.querySelectorAll('.course-dropdown').length;
        document.getElementById('course_count_display').textContent = count + (count === 1 ? ' course selected' : ' courses selected');
    }

    // Calculate total fees dynamically
    function calculateTotalFee() {
        if (isSupplementary) {
            document.getElementById('total_fee_display').textContent = '0.00';
            return;
        }

        let total = 0;
        const dropdowns = document.querySelectorAll('.course-dropdown');
        dropdowns.forEach(function(dropdown) {
            const selected = dropdown.options[dropdown.selectedIndex];
            if (selected && selected.dataset.fee) {
                total += parseFloat(selected.dataset.fee);
            } else if (selected && selected.value) {
                total += 150.00;
            }
        });

        document.getElementById('total_fee_display').textContent = total.toFixed(2);
    }
</script>
@endsection
