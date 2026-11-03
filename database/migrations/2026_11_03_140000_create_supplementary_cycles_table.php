<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplementary_cycles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->dateTime('registration_starts_at');
            $table->dateTime('registration_ends_at');
            $table->dateTime('student_access_ends_at');
            $table->boolean('is_current')->default(true);
            $table->timestamps();

            $table->index('is_current');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplementary_cycles');
    }
};
