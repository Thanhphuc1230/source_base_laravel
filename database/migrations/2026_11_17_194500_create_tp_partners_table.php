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
        Schema::create('tp_partners', function (Blueprint $table) {
            $table->id('id_partner');
            $table->uuid('uuid')->unique();
            $table->string('name_vn');
            $table->string('name_en')->nullable();
            $table->string('slug_vn')->nullable()->index();
            $table->string('slug_en')->nullable()->index();
            $table->boolean('status')->default(true);
            $table->string('image_vn')->nullable();
            $table->string('image_en')->nullable();
            $table->unsignedInteger('stt')->default(0)->nullable();
            $table->timestamps();

            // Indexes for status and ordering
            $table->index(['status', 'stt']);
            $table->index(['status', 'slug_vn']);
            $table->index(['status', 'slug_en']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_partners');
    }
};
