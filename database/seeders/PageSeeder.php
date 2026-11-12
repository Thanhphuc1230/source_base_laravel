<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $pageRepo = app(\App\Repositories\Interfaces\PageRepositoryInterface::class);

        if (! Page::where('slug_vn', 'gioi-thieu')->exists()) {
            $pageRepo->createWithAutoSlug([
                'name_vn' => 'Giới thiệu',
                'name_en' => 'About us',
                'content_vn' => 'Giới thiệu',
                'content_en' => 'About us',
                'status' => true,
                'stt' => 1,
                'keyword_vn' => 'giới thiệu, công ty',
                'keyword_en' => 'about us, company',
                'description_vn' => 'Trang giới thiệu công ty',
                'description_en' => 'About us page',
                'image_vn' => null,
                'image_en' => null,
                'parent_id' => 0,
            ]);
        }
    }
}
