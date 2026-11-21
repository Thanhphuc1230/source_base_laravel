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
        Schema::create('tp_cate_news', function (Blueprint $table) {
            $table->id('id_cate_new');
            $table->uuid()->unique();
            $table->json('name');
            $table->json('slug');
            $table->string('image_vn')->nullable();
            $table->string('image_en')->nullable();
            $table->json('keyword')->nullable();
            $table->json('description')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('home')->default(false);
            $table->unsignedInteger('stt')->default(0)->nullable();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->timestamps();

            // Composite indexes for hierarchical queries
            $table->index(['status', 'home']);      // Home category display
            $table->index(['status', 'parent_id', 'stt']); // Covers: status + hierarchy + ordering
            $table->index(['parent_id', 'stt']);          // Covers: hierarchy navigation
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_cate_news');
    }
};
