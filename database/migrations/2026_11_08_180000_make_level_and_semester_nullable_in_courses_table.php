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
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedSmallInteger('level')->nullable()->default(100)->change();
            $table->string('semester')->nullable()->default('All Semesters')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedSmallInteger('level')->nullable(false)->change();
            $table->string('semester')->nullable(false)->change();
        });
    }
};
