<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    use HasFactory;

    protected $table = 'tp_redirects';

    protected $primaryKey = 'id_redirect';

    protected $fillable = [
        'uuid',
        'source_url',
        'target_url',
        'status_code',
        'hits',
        'last_hit_at',
        'is_active',
        'note',
    ];

    protected $casts = [
        'status_code' => 'integer',
        'hits' => 'integer',
        'is_active' => 'boolean',
        'last_hit_at' => 'datetime',
    ];
}
