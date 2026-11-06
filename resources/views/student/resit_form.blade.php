{{-- resources/views/student/resit_form.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-xl p-8 max-w-2xl w-full">
        <h2 class="text-2xl font-bold text-wine-700 mb-4 text-center">{{ $examType === 'supplementary' ? 'Supplementary' : 'Resit' }} Exam Request</h2>
        <p class="text-slate-600 mb-6 text-center">Please fill out the form below.</p>
    <h2 class="text-2xl font-bold mb-4">Resit / Supplementary Exam Request</h2>
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('student.resit-form.submit') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1" for="name">Name</label>
                <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-medium mb-1" for="department">Department</label>
                <input type="text" name="department" id="department" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-medium mb-1" for="index_number">Index Number</label>
                <input type="text" name="index_number" id="index_number" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-medium mb-1" for="level">Level</label>
                <input type="text" name="level" id="level" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="md:col-span-2">
                <label class="block font-medium mb-1" for="programme">Programme</label>
                <input type="text" name="programme" id="programme" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-medium mb-1" for="paper_count">No. of Paper(s)</label>
                <input type="number" name="paper_count" id="paper_count" min="1" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-medium mb-1" for="year_entry">Year of Entry</label>
                <input type="number" name="year_entry" id="year_entry" min="1900" max="2100" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-medium mb-1" for="year_completion">Year of Completion</label>
                <input type="number" name="year_completion" id="year_completion" min="1900" max="2100" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="md:col-span-2">
                <label class="block font-medium mb-1" for="telephone">Telephone Number(s)</label>
                <input type="text" name="telephone" id="telephone" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="md:col-span-2">
                {{-- Exam Type is determined by the registration hub – no selector needed --}}
                <input type="hidden" name="exam_type" id="exam_type" value="{{ $examType }}">
                <div id="fee_section" class="mt-2">
                    <span class="block font-medium">Fee: GHS <span id="fee_amount">{{ $examType === 'supplementary' ? '0.00' : '150.00' }}</span></span>
                </div>
                {{-- Show supplementary extra fields if needed --}}
                @if($examType === 'supplementary')
                <div id="supplementary_extra" class="md:col-span-2" style="display:block;">
                    <label class="block font-medium mb-1" for="medical_report">Medical Report (PDF/JPG/PNG, max 2 MB)</label>
                    <input type="file" name="medical_report" id="medical_report" class="w-full">
                    <label class="block font-medium mt-2" for="letter">Letter (PDF/DOC/DOCX, max 2 MB)</label>
                    <input type="file" name="letter" id="letter" class="w-full">
                </div>
                @else
                <div id="supplementary_extra" class="md:col-span-2" style="display:none;"></div>
                @endif
            </div>
            <div id="supplementary_extra" class="md:col-span-2" style="display:none;">
                <label class="block font-medium mb-1" for="medical_report">Medical Report (PDF/JPG/PNG, max 2 MB)</label>
                <input type="file" name="medical_report" id="medical_report" class="w-full">
                <label class="block font-medium mt-2" for="letter">Letter (PDF/DOC/DOCX, max 2 MB)</label>
                <input type="file" name="letter" id="letter" class="w-full">
            </div>
            <div class="md:col-span-2">
                <label class="block font-medium mb-1" for="course_entries">Course Title & Code, Lecturer's Name (one per line)</label>
                <textarea name="course_entries[]" id="course_entries" rows="4" class="w-full border rounded px-3 py-2" placeholder="e.g. MATH101 - Calculus I - Dr. Mensah" required></textarea>
                <small class="text-gray-600">Click “Add another” to add more lines.</small>
                <button type="button" id="add_course_entry" class="mt-2 text-sm text-blue-600 underline">Add another</button>
            </div>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Submit Request</button>
    </form>
</div>

<script>
    document.getElementById('exam_type').addEventListener('change', function () {
        var isSupplementary = this.value === 'supplementary';
        // Show/hide the extra upload fields (only for supplementary)
        document.getElementById('supplementary_extra').style.display = isSupplementary ? 'block' : 'none';
        // Update fee amount: 0 for supplementary, 150.00 for resit
        var feeAmount = isSupplementary ? '0.00' : '150.00';
        document.getElementById('fee_amount').innerText = feeAmount;
        // Ensure fee section is always visible
        document.getElementById('fee_section').style.display = 'block';
    });
    // Run on page load to set correct fee based on default selection
    document.addEventListener('DOMContentLoaded', function() {
        var event = new Event('change');
        document.getElementById('exam_type').dispatchEvent(event);
    });
    document.getElementById('add_course_entry').addEventListener('click', function () {
        const textarea = document.createElement('textarea');
        textarea.name = 'course_entries[]';
        textarea.rows = 2;
        textarea.className = 'w-full border rounded px-3 py-2 mt-2';
        textarea.required = true;
        this.parentNode.insertBefore(textarea, this);
    });
</script>
@endsection