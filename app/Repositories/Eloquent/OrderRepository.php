<?php

namespace App\Repositories\Eloquent;

use App\Models\OrderStatus;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(OrderStatus $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getOrdersPaginated(array $filters = [], int $perPage = 10)
    {
        $query = DB::table('tp_order_shipping')
            ->join('tp_order_status', 'tp_order_shipping.id_order_shipping', '=', 'tp_order_status.shipping_id')
            ->select(
                'tp_order_shipping.email',
                'tp_order_shipping.phone',
                'tp_order_shipping.f_name_order',
                'tp_order_shipping.l_name_order',
                'tp_order_status.total',
                'tp_order_status.status',
                'tp_order_status.payment_method',
                'tp_order_status.uuid_order_status',
                'tp_order_status.created_at',
                'tp_order_status.updated_at'
            )
            ->orderBy('tp_order_status.created_at', 'desc');

        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('tp_order_shipping.email', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tp_order_shipping.phone', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tp_order_shipping.f_name_order', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tp_order_shipping.l_name_order', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tp_order_status.uuid_order_status', 'LIKE', "%{$searchTerm}%");
            });
        }

        if (isset($filters['status']) && $filters['status'] !== null && $filters['status'] !== '') {
            $query->where('tp_order_status.status', $filters['status']);
        }

        return $query->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function findByUuid($uuid, $columns = ['*'])
    {
        return $this->model->where('uuid_order_status', $uuid)->first($columns);
    }

    /**
     * @inheritDoc
     */
    public function getOrderDetailByUuid(string $uuid)
    {
        return DB::table('tp_order_status')
            ->join('tp_order_shipping', 'tp_order_status.shipping_id', '=', 'tp_order_shipping.id_order_shipping')
            ->select(
                'tp_order_shipping.l_name_order',
                'tp_order_shipping.f_name_order',
                'tp_order_shipping.phone',
                'tp_order_shipping.email',
                'tp_order_shipping.address',
                'tp_order_shipping.note',
                'tp_order_status.payment_method',
                'tp_order_status.total',
                'tp_order_status.id_order_status',
                'tp_order_status.status',
                'tp_order_status.uuid_order_status'
            )
            ->where('tp_order_status.uuid_order_status', $uuid)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function getOrderProducts(int $orderStatusId)
    {
        return DB::table('tp_order_product')
            ->join('tp_products', 'tp_order_product.product_id', '=', 'tp_products.id_product')
            ->select(
                'tp_order_product.*',
                'tp_products.name_vn',
                'tp_products.image_vn as image'
            )
            ->where('tp_order_product.order_status_id', $orderStatusId)
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function updateStatusByUuid(string $uuid, $status, string $field = 'status')
    {
        $orderStatus = $this->findByUuid($uuid);
        if (!$orderStatus) {
            return null;
        }

        $orderStatus->$field = $status;
        $orderStatus->save();

        try {
            app(\App\Services\DashboardService::class)->flushCache();
        } catch (\Throwable $e) {}

        return $orderStatus;
    }

    /**
     * @inheritDoc
     */
    public function deleteOrderByUuid(string $uuid)
    {
        $orderStatus = DB::table('tp_order_status')->where('uuid_order_status', $uuid)->first();
        if (!$orderStatus) {
            return false;
        }

        return DB::transaction(function () use ($orderStatus, $uuid) {
            DB::table('tp_order_product')
                ->where('order_status_id', $orderStatus->id_order_status)
                ->delete();

            DB::table('tp_order_status')
                ->where('uuid_order_status', $uuid)
                ->delete();

            DB::table('tp_order_shipping')
                ->where('id_order_shipping', $orderStatus->shipping_id)
                ->delete();

            try {
                app(\App\Services\DashboardService::class)->flushCache();
            } catch (\Throwable $e) {}

            return true;
        });
    }
}
