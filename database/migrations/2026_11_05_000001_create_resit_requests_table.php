<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resit_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('department');
            $table->string('index_number');
            $table->string('level');
            $table->string('programme');
            $table->unsignedInteger('paper_count');
            $table->json('course_entries');
            $table->year('year_entry');
            $table->year('year_completion');
            $table->string('telephone');
            $table->enum('exam_type', ['resit', 'supplementary']);
            $table->string('medical_report_path')->nullable();
            $table->string('letter_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resit_requests');
    }
};
?>