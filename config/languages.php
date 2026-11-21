<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default System Locale
    |--------------------------------------------------------------------------
    */
    'default' => 'vi',

    /*
    |--------------------------------------------------------------------------
    | Supported Languages
    |--------------------------------------------------------------------------
    |
    | Danh sách 6 ngôn ngữ chính thức được hỗ trợ trong hệ thống:
    | vi: Tiếng Việt (Mặc định)
    | en: English
    | de: Deutsch (Đức)
    | ja: 日本語 (Nhật Bản)
    | ko: 한국어 (Hàn Quốc)
    | zh: 简体中文 (Trung Quốc)
    |
    */
    'supported' => [
        'vi' => [
            'name' => 'Tiếng Việt',
            'native' => 'Tiếng Việt',
            'code' => 'vi',
            'aliases' => ['vn'], // Backward compatibility with legacy 'vn' code
            'flag' => 'admin/images/flags/vn.svg',
            'is_default' => true,
        ],
        'en' => [
            'name' => 'Tiếng Anh',
            'native' => 'English',
            'code' => 'en',
            'aliases' => [],
            'flag' => 'admin/images/flags/us.svg',
            'is_default' => false,
        ],
        'de' => [
            'name' => 'Tiếng Đức',
            'native' => 'Deutsch',
            'code' => 'de',
            'aliases' => [],
            'flag' => 'admin/images/flags/germany.svg',
            'is_default' => false,
        ],
        'ja' => [
            'name' => 'Tiếng Nhật',
            'native' => '日本語',
            'code' => 'ja',
            'aliases' => ['jp'],
            'flag' => 'admin/images/flags/jp.svg',
            'is_default' => false,
        ],
        'ko' => [
            'name' => 'Tiếng Hàn',
            'native' => '한국어',
            'code' => 'ko',
            'aliases' => ['kr'],
            'flag' => 'admin/images/flags/kr.svg',
            'is_default' => false,
        ],
        'zh' => [
            'name' => 'Tiếng Trung',
            'native' => '简体中文',
            'code' => 'zh',
            'aliases' => ['cn'],
            'flag' => 'admin/images/flags/china.svg',
            'is_default' => false,
        ],
    ],
];
