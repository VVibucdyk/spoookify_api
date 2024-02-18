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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('role_id');
            $table->string('topic_id');
            $table->string('about_me')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('profile_path')->nullable();
            $table->boolean('is_bgm_active')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
