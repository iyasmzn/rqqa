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
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author', 150)->nullable();
            $table->string('publisher', 150)->nullable();
            $table->unsignedSmallInteger('published_year')->nullable();
            $table->string('isbn', 30)->nullable();
            $table->string('language', 50)->nullable();
            $table->unsignedInteger('pages')->nullable();
            $table->string('category', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();

            // Isi e-book: berkas PDF, tautan ke situs lain, atau penampil yang disematkan.
            $table->string('source_type', 20)->default('pdf');
            $table->string('file_path', 500)->nullable();
            $table->string('original_filename')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('external_url', 1000)->nullable();
            $table->string('embed_url', 1000)->nullable();
            $table->boolean('allow_download')->default(true);

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};
