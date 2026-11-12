<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            [
                'name_vn' => 'Trang Chủ',
                'name_en' => 'Home',
                'link' => '',
                'slug' => 'trang-chu',
                'type' => 'link',
                'parent_id' => 0,
                'object_id' => 0,
                'stt' => 0,
            ],
            [
                'name_vn' => 'Giới thiệu',
                'name_en' => 'About us',
                'link' => '',
                'slug' => 'gioi-thieu',
                'type' => 'page',
                'parent_id' => 0,
                'object_id' => 1,
                'stt' => 0,
            ],
            [
                'name_vn' => 'Sản Phẩm',
                'name_en' => 'Product',
                'link' => '',
                'slug' => 'san-pham',
                'type' => 'cate_product',
                'parent_id' => 0,
                'object_id' => 1,
                'stt' => 0,
            ],
            [
                'name_vn' => 'Tin Tức',
                'name_en' => 'News',
                'link' => '',
                'slug' => 'tin-tuc',
                'type' => 'cate_new',
                'parent_id' => 0,
                'object_id' => 1,
                'stt' => 0,
            ],
            [
                'name_vn' => 'Liên Hệ',
                'name_en' => 'Contact',
                'link' => 'lien-he.html',
                'slug' => 'lien-he',
                'type' => 'link',
                'parent_id' => 0,
                'object_id' => 0,
                'stt' => 2,
            ],
        ];

        foreach ($menus as $m) {
            if (! Menu::where('slug', $m['slug'])->exists()) {
                $m['uuid'] = Str::uuid()->toString();
                Menu::create($m);
            }
        }
    }
}
