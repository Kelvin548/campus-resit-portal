<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resit_form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('exam_type', ['resit', 'supplementary']);
            $table->string('name');
            $table->string('department');
            $table->string('index_number');
            $table->string('level');
            $table->string('programme');
            $table->integer('number_of_papers');
            $table->text('course_details'); // course title, code, lecturer name (multiple lines)
            $table->string('year_of_entry');
            $table->string('year_of_completion');
            $table->string('telephone');
            $table->string('medical_report_path')->nullable();
            $table->string('letter_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resit_form_submissions');
    }
};
?>