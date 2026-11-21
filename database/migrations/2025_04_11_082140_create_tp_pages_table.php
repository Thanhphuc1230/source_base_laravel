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
        Schema::create('tp_pages', function (Blueprint $table) {
            $table->id('id_page');
            $table->uuid()->unique();
            $table->json('name');
            $table->json('slug');
            $table->json('content')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('footer')->default(0);
            $table->unsignedInteger('stt')->default(1)->nullable();
            $table->string('image_vn')->nullable();
            $table->string('image_en')->nullable();
            $table->json('keyword')->nullable();
            $table->json('description')->nullable();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->timestamps();

            // Essential indexes
            $table->index(['status', 'stt']);       // Covers: status filtering + ordering
            $table->index(['status', 'footer']);    // Covers: footer pages filtering
            $table->index(['status', 'footer', 'stt']); // Covers: footer pages display ordering
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_pages');
    }
};
