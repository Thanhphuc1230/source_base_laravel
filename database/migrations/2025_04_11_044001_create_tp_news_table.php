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
        Schema::create('tp_news', function (Blueprint $table) {
            $table->id('id_new');
            $table->uuid('uuid')->unique();
            $table->string('name_vn');
            $table->string('name_en')->nullable();
            $table->string('slug_vn')->unique();
            $table->string('slug_en')->nullable()->unique();
            $table->string('image_vn')->nullable();
            $table->string('image_en')->nullable();
            $table->text('intro_vn');
            $table->text('intro_en')->nullable();
            $table->longText('content_vn');
            $table->longText('content_en')->nullable();
            $table->string('keyword_vn')->nullable();
            $table->string('keyword_en')->nullable();
            $table->string('description_vn')->nullable();
            $table->string('description_en')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->boolean('status')->default(true);
            $table->boolean('home')->default(false);
            $table->unsignedInteger('stt')->default(1)->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('category_id')->references('id_cate_new')->on('tp_cate_news')->onDelete('cascade');

            // Query & sorting indexes
            $table->index('created_at');
            $table->index(['category_id', 'created_at']);
            $table->index(['category_id', 'status', 'created_at']);

            // Composite indexes for frontend route & display
            $table->index(['status', 'slug_vn']);
            $table->index(['status', 'slug_en']);
            $table->index(['status', 'stt']);
            $table->index(['status', 'category_id']);
            $table->index(['views', 'status']);
            $table->index(['created_at', 'status']);

            // Fulltext search
            $table->fullText(['name_vn', 'name_en']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_news');
    }
};
