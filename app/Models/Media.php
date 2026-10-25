<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Media extends Model
{
    use HasFactory;

    protected $table = 'tp_media';

    protected $fillable = [
        'uuid',
        'name',
        'file_name',
        'path',
        'module',
        'mime_type',
        'extension',
        'size',
        'dimensions',
        'created_by',
    ];

    protected $casts = [
        'size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'url',
        'formatted_size',
        'is_image',
        'is_video',
    ];

    /**
     * Get the public asset URL.
     */
    public function getUrlAttribute(): string
    {
        return asset($this->path);
    }

    /**
     * Get human-readable file size.
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = (int) $this->size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }

    /**
     * Determine if file is an image.
     */
    public function getIsImageAttribute(): bool
    {
        return Str::startsWith($this->mime_type, 'image/') || in_array(strtolower($this->extension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
    }

    /**
     * Determine if file is a video.
     */
    public function getIsVideoAttribute(): bool
    {
        return Str::startsWith($this->mime_type, 'video/') || in_array(strtolower($this->extension), ['mp4', 'webm', 'mov', 'ogg']);
    }

    /**
     * User relation.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
