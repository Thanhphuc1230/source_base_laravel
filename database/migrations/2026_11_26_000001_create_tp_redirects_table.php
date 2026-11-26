<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tp_redirects', function (Blueprint $table) {
            $table->id('id_redirect');
            $table->uuid('uuid')->unique();
            $table->string('source_url', 255)->unique();
            $table->string('target_url', 500)->nullable();
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->unsignedBigInteger('hits')->default(0);
            $table->timestamp('last_hit_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('note', 255)->nullable();
            $table->timestamps();

            $table->index(['is_active', 'status_code']);
            $table->index(['status_code', 'hits']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tp_redirects');
    }
};
