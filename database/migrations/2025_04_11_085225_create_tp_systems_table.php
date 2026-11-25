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
        Schema::create('tp_systems', function (Blueprint $table) {
            $table->id('id_system');
            $table->json('active_languages')->nullable(); // Cấu hình ngôn ngữ hoạt động động
            // thông tin liên hệ
            $table->string('email', 50);
            $table->string('email_alert', 50)->nullable();
            $table->json('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->json('footer')->nullable();
            
            // socical media
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('zalo')->nullable();

            // Seo website & Custom Scripts
            $table->string('favicon')->nullable();
            $table->string('logo')->nullable();
            $table->string('share_image')->nullable();
            $table->string('meta_title')->nullable();
            $table->json('name')->nullable();
            $table->json('description')->nullable();
            $table->json('keyword')->nullable();
            $table->mediumText('header_js')->nullable();
            $table->mediumText('body_js')->nullable();

            // Contact page text settings
            $table->json('contact_title')->nullable();
            $table->json('contact_desc')->nullable();

            $table->text('map')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_systems');
    }
};
