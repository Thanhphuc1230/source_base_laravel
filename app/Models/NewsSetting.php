<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsSetting extends Model
{
    use Cachable, HasFactory;

    protected $fillable = [
        'uuid',
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Request-level memoized map of active news settings.
     */
    protected static ?array $activeSettingsMap = null;

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });

        static::saved(function () {
            static::$activeSettingsMap = null;
        });

        static::deleted(function () {
            static::$activeSettingsMap = null;
        });
    }

    /**
     * Load all active news settings into a key => casted_value map in a single query.
     */
    public static function getAllActiveMap(): array
    {
        if (static::$activeSettingsMap !== null) {
            return static::$activeSettingsMap;
        }

        try {
            $rows = static::where('is_active', true)->get(['key', 'value', 'type']);
            $map = [];
            foreach ($rows as $row) {
                $map[$row->key] = static::castValue($row->value, $row->type);
            }
            static::$activeSettingsMap = $map;
        } catch (\Throwable $e) {
            static::$activeSettingsMap = [];
        }

        return static::$activeSettingsMap;
    }

    /**
     * Flush request-level settings map cache.
     */
    public static function flushMemoryCache(): void
    {
        static::$activeSettingsMap = null;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Lấy giá trị setting theo key
     */
    public static function get($key, $default = null)
    {
        $map = static::getAllActiveMap();

        return array_key_exists($key, $map) ? $map[$key] : $default;
    }

    /**
     * Cập nhật hoặc tạo mới setting
     */
    public static function set($key, $value, $type = 'text', $group = 'general', $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description,
                'is_active' => true,
            ]
        );
    }

    /**
     * Chuyển đổi giá trị theo type
     */
    protected static function castValue($value, $type)
    {
        switch ($type) {
            case 'number':
                return (int) $value;
            case 'json':
                return json_decode($value, true);
            case 'boolean':
                return (bool) $value;
            default:
                return $value;
        }
    }

    /**
     * Scope theo group
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope chỉ active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
