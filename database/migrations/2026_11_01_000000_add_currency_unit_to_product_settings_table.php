<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('product_settings')) {
            Schema::table('product_settings', function (Blueprint $table) {
                if (! Schema::hasColumn('product_settings', 'currency_unit')) {
                    $table->string('currency_unit', 50)->default('đ')->nullable()->after('is_active');
                }
            });

            // Ensure default record exists in key-value format
            $exists = DB::table('product_settings')->where('key', 'products_currency_unit')->exists();
            if (! $exists) {
                DB::table('product_settings')->insert([
                    'uuid' => (string) Str::uuid(),
                    'key' => 'products_currency_unit',
                    'value' => 'đ',
                    'type' => 'text',
                    'group' => 'general',
                    'description' => 'Đơn vị tiền tệ hiển thị toàn hệ thống (Web & Admin)',
                    'is_active' => 1,
                    'sort_order' => 0,
                    'currency_unit' => 'đ',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('product_settings')) {
            Schema::table('product_settings', function (Blueprint $table) {
                if (Schema::hasColumn('product_settings', 'currency_unit')) {
                    $table->dropColumn('currency_unit');
                }
            });

            DB::table('product_settings')->where('key', 'products_currency_unit')->delete();
        }
    }
};
