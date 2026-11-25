<?php

if (! function_exists('lang')) {
    function lang($item, $property)
    {
        $locale = app()->getLocale() ?: session()->get('locale', config('languages.default', 'vi'));
        if ($locale === 'vn') {
            $locale = 'vi';
        }

        // Check if $item is an object
        if (is_object($item)) {
            // 1. If Model uses HasJsonTranslations
            if (method_exists($item, 'getTranslation')) {
                $translated = $item->getTranslation($property, $locale);
                if ($translated !== null && $translated !== '') {
                    return $translated;
                }
            }

            // 2. Direct attribute accessor
            if (isset($item->{$property}) && !empty($item->{$property})) {
                return $item->{$property};
            }

            // 3. Legacy column check (e.g. name_vn, name_vi, name_en)
            $col = $property . '_' . ($locale === 'vi' ? 'vn' : $locale);
            if (isset($item->{$col}) && !empty($item->{$col})) {
                return $item->{$col};
            }

            return $item->{$property . '_vn'} ?? $item->{$property . '_en'} ?? null;
        }

        // Check if $item is an array
        if (is_array($item)) {
            // Check JSON translated array structure: ['name' => ['vi' => '...', 'en' => '...']]
            if (isset($item[$property]) && is_array($item[$property])) {
                return $item[$property][$locale] ?? $item[$property]['vi'] ?? ($item[$property]['en'] ?? null);
            }
            if (isset($item[$property]) && is_string($item[$property]) && (str_starts_with($item[$property], '{'))) {
                $decoded = json_decode($item[$property], true);
                if (is_array($decoded)) {
                    return $decoded[$locale] ?? $decoded['vi'] ?? ($decoded['en'] ?? null);
                }
            }

            $col = $property . '_' . ($locale === 'vi' ? 'vn' : $locale);
            return $item[$col] ?? ($item[$property . '_vn'] ?? ($item[$property . '_en'] ?? null));
        }

        return null;
    }
}
