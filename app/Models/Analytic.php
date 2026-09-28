<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    /**
     * TUYỆT ĐỐI KHÔNG dùng GeneaLabs\LaravelModelCaching\Traits\Cachable ở Model này!
     * Khi CACHE_DRIVER=file, mỗi lần cập nhật visit_count sẽ kích hoạt Cache::flush(),
     * xóa sạch cache hệ thống và key chống trùng lặp, gây nhảy vọt số truy cập ảo.
     */
    use HasFactory;

    protected $table = 'tp_analytics';

    protected $primaryKey = 'id';

    protected $fillable = [
        'visit_date',
        'visit_count',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];
}
