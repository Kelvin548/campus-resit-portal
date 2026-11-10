<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_change_token_hash_unique');
            $table->renameColumn('email_change_token_hash', 'email_verification_code_hash');
            $table->renameColumn('email_change_expires_at', 'email_verification_expires_at');
            $table->unsignedTinyInteger('email_verification_attempts')->default(0);
            $table->unique('email_verification_code_hash', 'users_email_verification_code_hash_unique');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_verification_code_hash_unique');
            $table->dropColumn('email_verification_attempts');
            $table->renameColumn('email_verification_code_hash', 'email_change_token_hash');
            $table->renameColumn('email_verification_expires_at', 'email_change_expires_at');
            $table->unique('email_change_token_hash', 'users_email_change_token_hash_unique');
        });
    }
};
