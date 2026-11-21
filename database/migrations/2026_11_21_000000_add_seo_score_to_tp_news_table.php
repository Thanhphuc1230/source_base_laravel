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
        Schema::table('tp_news', function (Blueprint $table) {
            $table->unsignedTinyInteger('seo_score')->default(0)->after('description_en');
            $table->index('seo_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tp_news', function (Blueprint $table) {
            $table->dropIndex(['seo_score']);
            $table->dropColumn('seo_score');
        });
    }
};
