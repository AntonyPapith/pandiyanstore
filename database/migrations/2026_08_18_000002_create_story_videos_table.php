<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('story_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained()->cascadeOnDelete();
            $table->string('title', 80);
            $table->string('video_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_videos');
    }
};
