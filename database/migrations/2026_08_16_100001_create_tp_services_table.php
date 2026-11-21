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
        Schema::create('tp_services', function (Blueprint $table) {
            $table->id('id_service');
            $table->uuid('uuid')->unique();
            $table->json('name');
            $table->json('slug');
            $table->string('image_vn')->nullable();
            $table->string('image_en')->nullable();
            $table->json('intro')->nullable();
            $table->json('content')->nullable();
            $table->json('keyword')->nullable();
            $table->json('description')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->boolean('status')->default(true);
            $table->boolean('home')->default(false);
            $table->unsignedInteger('stt')->default(1)->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('category_id')->references('id_cate_service')->on('tp_cate_services')->onDelete('cascade');

            // Query & sorting indexes
            $table->index('created_at');
            $table->index(['category_id', 'created_at']);
            $table->index(['category_id', 'status', 'created_at']);
            $table->index(['category_id', 'status', 'stt']);

            // Composite indexes
            $table->index(['status', 'stt']);
            $table->index(['status', 'home']);
            $table->index(['status', 'home', 'stt']);
            $table->index(['status', 'category_id']);
            $table->index(['views', 'status']);
            $table->index(['created_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_services');
    }
};
