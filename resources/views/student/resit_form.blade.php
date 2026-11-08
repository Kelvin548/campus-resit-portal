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

                <!-- Instant Course Search Filter -->
                <div class="mb-4 bg-slate-50 p-3.5 rounded-2xl border border-slate-200">
                    <label class="block text-xs font-bold text-slate-700 mb-1" for="course_search">
                        Instant Course Search / Filter:
                    </label>
                    <div class="relative">
                        <input type="text" id="course_search" placeholder="Type course code or name (e.g. ICT112, Database, Web, Systems)..." 
                               onkeyup="filterCourseDropdowns(this.value)"
                               class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600 focus:border-brand-600 bg-white">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
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
</div>

<script>
    const isSupplementary = "{{ $examType }}" === 'supplementary';
    let courseIndex = 1;

    // Filter dropdown options dynamically based on search keyword
    function filterCourseDropdowns(keyword) {
        keyword = keyword.toLowerCase().trim();
        const dropdowns = document.querySelectorAll('.course-dropdown');

        dropdowns.forEach(dropdown => {
            const options = dropdown.querySelectorAll('option');
            options.forEach(opt => {
                if (opt.value === "") return; // keep default placeholder
                const text = opt.textContent.toLowerCase();
                if (text.includes(keyword)) {
                    opt.style.display = "";
                } else {
                    opt.style.display = "none";
                }
            });
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
        dropdowns.forEach(dropdown => {
            const selected = dropdown.options[dropdown.selectedIndex];
            if (selected && selected.dataset.fee) {
                total += parseFloat(selected.dataset.fee);
            } else {
                total += 150.00;
            }
        });

        document.getElementById('total_fee_display').textContent = total.toFixed(2);
    }
</script>
@endsection
