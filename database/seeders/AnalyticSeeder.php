<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nếu đã có thống kê thì không tạo thêm
        if (DB::table('tp_analytics')->exists()) {
            return;
        }

        $data = [];
        for ($i = 9; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $data[] = [
                'visit_date' => $date,
                'visit_count' => rand(150, 950),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('tp_analytics')->insertOrIgnore($data);
    }
}
