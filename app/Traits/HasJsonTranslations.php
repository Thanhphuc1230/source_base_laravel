<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasJsonTranslations
{
    /**
     * Normalize locale code (e.g. 'vn' -> 'vi', 'jp' -> 'ja')
     */
    protected function normalizeLocale(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        if ($locale === 'vn') {
            return 'vi';
        }
        if ($locale === 'jp') {
            return 'ja';
        }
        if ($locale === 'kr') {
            return 'ko';
        }
        if ($locale === 'cn') {
            return 'zh';
        }
        return $locale;
    }

    /**
     * Get translation for a given attribute.
     */
    public function getTranslation(string $attribute, ?string $locale = null, bool $fallback = true): mixed
    {
        $targetLocale = $this->normalizeLocale($locale);
        $raw = $this->attributes[$attribute] ?? null;

        // 1. If value is stored as JSON or array
        $translations = [];
        if (is_array($raw)) {
            $translations = $raw;
        } elseif (is_string($raw) && (str_starts_with($raw, '{') || str_starts_with($raw, '['))) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $translations = $decoded;
            }
        }

        if (!empty($translations)) {
            if (!empty($translations[$targetLocale])) {
                return $translations[$targetLocale];
            }
            // Check alias in json (e.g. 'vn' for 'vi')
            if ($targetLocale === 'vi' && !empty($translations['vn'])) {
                return $translations['vn'];
            }

            if ($fallback) {
                $defaultLocale = config('languages.default', 'vi');
                if (!empty($translations[$defaultLocale])) {
                    return $translations[$defaultLocale];
                }
                // Fallback to first non-empty value
                foreach ($translations as $val) {
                    if (!empty($val)) {
                        return $val;
                    }
                }
            }
        }

        // 2. Backward compatibility: Fallback to legacy _vn / _en columns if present
        $legacyCol = $attribute . '_' . ($targetLocale === 'vi' ? 'vn' : $targetLocale);
        if (isset($this->attributes[$legacyCol]) && !empty($this->attributes[$legacyCol])) {
            return $this->attributes[$legacyCol];
        }

        if ($fallback) {
            $legacyVn = $attribute . '_vn';
            if (isset($this->attributes[$legacyVn]) && !empty($this->attributes[$legacyVn])) {
                return $this->attributes[$legacyVn];
            }
            $legacyEn = $attribute . '_en';
            if (isset($this->attributes[$legacyEn]) && !empty($this->attributes[$legacyEn])) {
                return $this->attributes[$legacyEn];
            }
        }

        return is_string($raw) ? $raw : null;
    }

    /**
     * Set translation for a specific locale.
     */
    public function setTranslation(string $attribute, string $locale, mixed $value): static
    {
        $targetLocale = $this->normalizeLocale($locale);
        $translations = $this->getTranslations($attribute);
        $translations[$targetLocale] = $value;

        // Also synchronize legacy column if it exists in the table attributes
        $legacyCol = $attribute . '_' . ($targetLocale === 'vi' ? 'vn' : $targetLocale);
        if (array_key_exists($legacyCol, $this->attributes)) {
            $this->attributes[$legacyCol] = is_string($value) ? $value : json_encode($value);
        }

        $this->attributes[$attribute] = json_encode($translations, JSON_UNESCAPED_UNICODE);
        return $this;
    }

    /**
     * Get all translations as an associative array.
     */
    public function getTranslations(string $attribute): array
    {
        $raw = $this->attributes[$attribute] ?? null;
        if (is_array($raw)) {
            return $raw;
        }
        if (is_string($raw) && (str_starts_with($raw, '{') || str_starts_with($raw, '['))) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        // Backward compatibility: assemble from legacy columns
        $translations = [];
        $locales = ['vi', 'en', 'de', 'ja', 'ko', 'zh'];
        foreach ($locales as $loc) {
            $col = $attribute . '_' . ($loc === 'vi' ? 'vn' : $loc);
            if (isset($this->attributes[$col]) && $this->attributes[$col] !== null && $this->attributes[$col] !== '') {
                $translations[$loc] = $this->attributes[$col];
            }
        }

        return $translations;
    }

    /**
     * Override getAttribute to support:
     * - dynamic localized attributes (e.g. $model->name)
     * - legacy suffixed attributes (e.g. $model->name_vi, $model->name_vn, $model->name_ja)
     */
    public function getAttribute($key)
    {
        // 1. Check if asking for a legacy or localized suffix (e.g. name_vn, name_vi, name_ja)
        foreach (['vi', 'vn', 'en', 'de', 'ja', 'ko', 'zh', 'jp', 'kr', 'cn'] as $loc) {
            $suffix = '_' . $loc;
            if (str_ends_with($key, $suffix)) {
                $baseAttr = substr($key, 0, -strlen($suffix));
                if (property_exists($this, 'translatable') && in_array($baseAttr, $this->translatable, true)) {
                    return $this->getTranslation($baseAttr, $loc, false);
                }
            }
        }

        // 2. Check if asking for base translatable attribute (e.g. $model->name)
        if (property_exists($this, 'translatable') && in_array($key, $this->translatable, true)) {
            return $this->getTranslation($key);
        }

        return parent::getAttribute($key);
    }

    /**
     * Override setAttribute to handle:
     * - array of translations e.g. ['vi' => '...', 'en' => '...']
     * - suffixed attributes e.g. $model->name_vn = '...' or $model->name_en = '...'
     */
    public function setAttribute($key, $value)
    {
        // 1. Check if $key is a suffixed translatable attribute (e.g. name_vn, name_en, intro_vn, etc.)
        foreach (['vi', 'vn', 'en', 'de', 'ja', 'ko', 'zh', 'jp', 'kr', 'cn'] as $loc) {
            $suffix = '_' . $loc;
            if (str_ends_with($key, $suffix)) {
                $baseAttr = substr($key, 0, -strlen($suffix));
                if (property_exists($this, 'translatable') && in_array($baseAttr, $this->translatable, true)) {
                    $normLoc = $this->normalizeLocale($loc);
                    $translations = $this->getTranslations($baseAttr);
                    $translations[$normLoc] = $value;
                    $this->attributes[$baseAttr] = json_encode($translations, JSON_UNESCAPED_UNICODE);
                    return $this;
                }
            }
        }

        // 2. Check if $key is a base translatable attribute
        if (property_exists($this, 'translatable') && in_array($key, $this->translatable, true)) {
            if (is_array($value)) {
                $clean = [];
                foreach ($value as $loc => $val) {
                    $clean[$this->normalizeLocale((string)$loc)] = $val;
                }
                $this->attributes[$key] = json_encode($clean, JSON_UNESCAPED_UNICODE);
                return $this;
            } elseif (is_string($value)) {
                if (str_starts_with($value, '{') || str_starts_with($value, '[')) {
                    $this->attributes[$key] = $value;
                } else {
                    $translations = $this->getTranslations($key);
                    $translations[$this->normalizeLocale()] = $value;
                    $this->attributes[$key] = json_encode($translations, JSON_UNESCAPED_UNICODE);
                }
                return $this;
            }
        }

        return parent::setAttribute($key, $value);
    }
}
