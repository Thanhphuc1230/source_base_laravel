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
        if (!Schema::hasTable('tp_media')) {
            Schema::create('tp_media', function (Blueprint $table) {
                $table->id();
                $table->char('uuid', 36)->unique();
                $table->string('name', 255);
                $table->string('file_name', 255);
                $table->string('path', 500)->index();
                $table->string('module', 50)->default('general')->index();
                $table->string('mime_type', 100);
                $table->string('extension', 10);
                $table->unsignedBigInteger('size')->default(0);
                $table->string('dimensions', 50)->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->index('created_at');
                $table->index(['module', 'id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_media');
    }
};
