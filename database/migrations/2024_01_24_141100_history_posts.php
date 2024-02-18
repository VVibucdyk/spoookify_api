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
        Schema::create('history_activity_posts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('post_id');
            $table->boolean('is_like')->nullable()->default(0);
            $table->boolean('is_seen')->nullable()->default(0);
            $table->boolean('is_listen')->nullable()->default(0);
            $table->boolean('is_report')->nullable()->default(0);
            $table->boolean('is_save_bookmark')->nullable()->default(0);
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
