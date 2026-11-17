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
        if (!Schema::hasTable('tp_product_attributes')) {
            Schema::create('tp_product_attributes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->string('name_vn');
                $table->string('name_en')->nullable();
                $table->longText('values_json'); // Array of values e.g. ["1m6 x 2m", "1m8 x 2m"]
                $table->unsignedInteger('stt')->default(1);
                $table->timestamps();

                $table->foreign('product_id')->references('id_product')->on('tp_products')->onDelete('cascade');
                $table->index(['product_id', 'stt']);
            });
        }

        if (!Schema::hasTable('tp_product_variants')) {
            Schema::create('tp_product_variants', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->string('sku')->nullable();
                $table->longText('option_values'); // JSON e.g. {"Kích thước": "1m8 x 2m", "Màu sắc": "Gỗ Óc Chó"}
                $table->decimal('price', 15, 0)->default(0);
                $table->decimal('price_old', 15, 0)->nullable();
                $table->integer('quantity')->default(50);
                $table->string('image')->nullable();
                $table->boolean('is_default')->default(false);
                $table->boolean('status')->default(true);
                $table->timestamps();

                $table->foreign('product_id')->references('id_product')->on('tp_products')->onDelete('cascade');
                $table->index(['product_id', 'status']);
                $table->index(['product_id', 'is_default']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_product_variants');
        Schema::dropIfExists('tp_product_attributes');
    }
};
