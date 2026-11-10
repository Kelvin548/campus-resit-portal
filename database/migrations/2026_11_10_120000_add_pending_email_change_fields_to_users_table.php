<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pending_email')->nullable()->unique();
            $table->char('email_change_token_hash', 64)->nullable()->unique();
            $table->timestamp('email_change_expires_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_pending_email_unique');
            $table->dropUnique('users_email_change_token_hash_unique');
            $table->dropColumn([
                'pending_email',
                'email_change_token_hash',
                'email_change_expires_at',
            ]);
        });
    }
};
