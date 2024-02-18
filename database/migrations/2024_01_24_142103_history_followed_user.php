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
        Schema::create('history_followed_users', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('followed_user_id');
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
