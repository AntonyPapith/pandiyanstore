<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('stories', 'categories');
        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
            $table->text('description')->nullable()->change();
            $table->dropColumn('color');
        });

        Schema::rename('story_videos', 'products');
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('story_id', 'category_id');
            $table->renameColumn('title', 'name');
            $table->renameColumn('video_path', 'image_path');
            $table->unsignedInteger('quantity')->default(0)->after('image_path');
            $table->decimal('price', 10, 2)->default(0)->after('quantity');
            $table->decimal('discount_price', 10, 2)->nullable()->after('price');
            $table->text('description')->nullable()->after('discount_price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'price', 'discount_price', 'description']);
            $table->renameColumn('category_id', 'story_id');
            $table->renameColumn('name', 'title');
            $table->renameColumn('image_path', 'video_path');
        });
        Schema::rename('products', 'story_videos');

        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
            $table->string('color', 7)->default('#08033D');
        });
        Schema::rename('categories', 'stories');
    }
};
