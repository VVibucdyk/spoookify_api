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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('role_id');
            $table->string('topic_id', 255);
            $table->string('title_post', 255);
            $table->text('excerpt')->nullable();
            $table->string('thumbnail_path', 255)->nullable()->default(null);
            $table->string('podcast_path', 255)->nullable()->default(null);
            $table->text('content');
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
