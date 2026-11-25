<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tp_products', function (Blueprint $table) {
            $table->id('id_product');
            $table->uuid('uuid')->unique();
            $table->json('name');
            $table->json('slug');
            $table->json('intro')->nullable();
            $table->decimal('price', 15, 0);
            $table->decimal('price_old', 15, 0)->nullable();
            $table->integer('quantity')->default(50);
            $table->json('content')->nullable();
            $table->string('image_vn')->nullable();
            $table->string('image_en')->nullable();
            $table->longText('image_detail')->nullable();
            $table->string('video')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('hot')->default(false);
            $table->boolean('home')->default(false);
            $table->boolean('sale')->default(false);
            $table->unsignedInteger('stt')->default(1);
            $table->json('keyword')->nullable();
            $table->json('description')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('category_id')->references('id_cate_product')->on('tp_cate_products')->onDelete('cascade');

            // Query & sorting indexes (pruned redundant left-prefix duplicates)
            $table->index(['category_id', 'status', 'created_at']);
            $table->index(['category_id', 'status', 'stt']);
            $table->index(['quantity', 'status']);

            // Composite indexes for frontend route & display
            $table->index(['status', 'stt']);
            $table->index(['status', 'home']);
            $table->index(['status', 'hot', 'stt']);
            $table->index(['status', 'sale', 'stt']);
            $table->index(['created_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_products');
    }
};
