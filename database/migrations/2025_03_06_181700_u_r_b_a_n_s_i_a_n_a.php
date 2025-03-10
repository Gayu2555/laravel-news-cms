<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 255)->unique();
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->dateTime('date_published');
            $table->text('content');
            $table->string('image_url', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('author_name', 100)->nullable();
            $table->string('author_url', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('article_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->enum('position', ['news_list', 'sub_headline', 'headline']);
            $table->timestamps();

            $table->unique(['category_id', 'article_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_positions');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('categories');
    }
};
