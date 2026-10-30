<?php

namespace App\Models;

use App\Traits\BaseLogsActivity;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use BaseLogsActivity, Cachable, HasFactory;

    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;

    protected $table = 'tp_order_status';

    protected $primaryKey = 'id_order_status';

    protected $fillable = [
        'uuid_order_status',
        'shipping_id',
        'payment_method',
        'total',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
        'total' => 'decimal:2',
    ];

    /**
     * Scope query for pending orders.
     */
    public function scopePending($query)
    {
        return $query->where('tp_order_status.status', self::STATUS_PENDING);
    }

    /**
     * Scope query for completed orders.
     */
    public function scopeCompleted($query)
    {
        return $query->where('tp_order_status.status', self::STATUS_COMPLETED);
    }

    /**
     * Scope query for cancelled orders.
     */
    public function scopeCancelled($query)
    {
        return $query->where('tp_order_status.status', self::STATUS_CANCELLED);
    }

    /**
     * Scope query for overdue pending orders (> X hours).
     */
    public function scopeOverduePending($query, int $hours = 24)
    {
        return $query->where('tp_order_status.status', self::STATUS_PENDING)
            ->where('tp_order_status.created_at', '<', now()->subHours($hours));
    }

    /**
     * Get the shipping information for this order
     */
    public function shipping()
    {
        return $this->belongsTo(OrderShipping::class, 'shipping_id', 'id_order_shipping');
    }

    /**
     * Get the products for this order
     */
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_status_id', 'id_order_status');
    }
}
