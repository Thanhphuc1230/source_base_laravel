<?php

namespace App\Repositories\Eloquent;

use App\Models\OrderStatus;
use App\Models\Product;
use App\Repositories\Interfaces\DashboardRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardRepositoryInterface
{
    /**
     * Get revenue and order counts summary (today, week, month, new, pending, cancelled, completed).
     *
     * @return array
     */
    public function getRevenueAndOrderSummary(): array
    {
        $now = Carbon::now();
        $todayStart = $now->copy()->startOfDay();
        $weekStart = $now->copy()->startOfWeek();
        $monthStart = $now->copy()->startOfMonth();

        $stats = DB::table('tp_order_status')
            ->selectRaw("
                COALESCE(SUM(CASE WHEN status = 1 AND created_at >= ? THEN CAST(total AS DECIMAL(15,2)) ELSE 0 END), 0) as revenue_today,
                COALESCE(SUM(CASE WHEN status = 1 AND created_at >= ? THEN CAST(total AS DECIMAL(15,2)) ELSE 0 END), 0) as revenue_week,
                COALESCE(SUM(CASE WHEN status = 1 AND created_at >= ? THEN CAST(total AS DECIMAL(15,2)) ELSE 0 END), 0) as revenue_month,
                COALESCE(SUM(CASE WHEN status = 1 THEN CAST(total AS DECIMAL(15,2)) ELSE 0 END), 0) as revenue_total,

                COUNT(CASE WHEN created_at >= ? THEN 1 END) as orders_today,
                COUNT(CASE WHEN created_at >= ? THEN 1 END) as orders_week,
                COUNT(CASE WHEN created_at >= ? THEN 1 END) as orders_month,
                COUNT(CASE WHEN status = 0 THEN 1 END) as orders_pending,
                COUNT(CASE WHEN status = 1 THEN 1 END) as orders_completed,
                COUNT(CASE WHEN status = 2 THEN 1 END) as orders_cancelled,
                COUNT(*) as orders_total
            ", [
                $todayStart, $weekStart, $monthStart,
                $todayStart, $weekStart, $monthStart
            ])
            ->first();

        return [
            'revenue_today' => (float) ($stats->revenue_today ?? 0),
            'revenue_week' => (float) ($stats->revenue_week ?? 0),
            'revenue_month' => (float) ($stats->revenue_month ?? 0),
            'revenue_total' => (float) ($stats->revenue_total ?? 0),
            'orders_today' => (int) ($stats->orders_today ?? 0),
            'orders_week' => (int) ($stats->orders_week ?? 0),
            'orders_month' => (int) ($stats->orders_month ?? 0),
            'orders_pending' => (int) ($stats->orders_pending ?? 0),
            'orders_completed' => (int) ($stats->orders_completed ?? 0),
            'orders_cancelled' => (int) ($stats->orders_cancelled ?? 0),
            'orders_total' => (int) ($stats->orders_total ?? 0),
        ];
    }

    /**
     * Get daily revenue and order count for the last N days.
     *
     * @param int $days
     * @return array
     */
    public function getDailyRevenueChart(int $days = 30): array
    {
        $startDate = Carbon::today()->subDays($days - 1)->startOfDay();

        $rows = DB::table('tp_order_status')
            ->selectRaw("
                DATE(created_at) as order_date,
                COALESCE(SUM(CASE WHEN status = 1 THEN CAST(total AS DECIMAL(15,2)) ELSE 0 END), 0) as daily_revenue,
                COUNT(*) as total_orders,
                COUNT(CASE WHEN status = 1 THEN 1 END) as completed_orders
            ")
            ->where('created_at', '>=', $startDate)
            ->groupBy('order_date')
            ->orderBy('order_date', 'asc')
            ->get()
            ->keyBy('order_date');

        $chartData = [];
        for ($i = 0; $i < $days; $i++) {
            $currentDate = $startDate->copy()->addDays($i)->format('Y-m-d');
            $displayDate = $startDate->copy()->addDays($i)->format('d/m');
            $record = $rows->get($currentDate);

            $chartData[] = [
                'date' => $displayDate,
                'full_date' => $currentDate,
                'revenue' => $record ? (float) $record->daily_revenue : 0,
                'orders' => $record ? (int) $record->total_orders : 0,
                'completed_orders' => $record ? (int) $record->completed_orders : 0,
            ];
        }

        return $chartData;
    }

    /**
     * Get monthly revenue and order count for a given year.
     *
     * @param int $year
     * @return array
     */
    public function getMonthlyRevenueChart(int $year): array
    {
        $rows = DB::table('tp_order_status')
            ->selectRaw("
                MONTH(created_at) as order_month,
                COALESCE(SUM(CASE WHEN status = 1 THEN CAST(total AS DECIMAL(15,2)) ELSE 0 END), 0) as monthly_revenue,
                COUNT(*) as total_orders
            ")
            ->whereYear('created_at', $year)
            ->groupBy('order_month')
            ->orderBy('order_month', 'asc')
            ->get()
            ->keyBy('order_month');

        $chartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $record = $rows->get($m);
            $chartData[] = [
                'month' => 'T' . $m,
                'full_month' => Carbon::createFromDate($year, $m, 1)->format('m/Y'),
                'revenue' => $record ? (float) $record->monthly_revenue : 0,
                'orders' => $record ? (int) $record->total_orders : 0,
            ];
        }

        return $chartData;
    }

    /**
     * Get top selling products with quantity sold and revenue.
     *
     * @param int $limit
     * @return array
     */
    public function getTopSellingProducts(int $limit = 5): array
    {
        $topSold = DB::table('tp_order_product')
            ->join('tp_order_status', 'tp_order_product.order_status_id', '=', 'tp_order_status.id_order_status')
            ->selectRaw("
                tp_order_product.product_id,
                COALESCE(SUM(tp_order_product.quantity), 0) as total_sold,
                COALESCE(SUM(tp_order_product.quantity * CAST(tp_order_product.price AS DECIMAL(15,2))), 0) as total_revenue
            ")
            ->where('tp_order_status.status', '!=', 2) // exclude cancelled orders
            ->groupBy('tp_order_product.product_id')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();

        if ($topSold->isEmpty()) {
            return [];
        }

        $productIds = $topSold->pluck('product_id')->toArray();
        $products = Product::whereIn('id_product', $productIds)
            ->with('cate:id_cate_product,name_vn')
            ->select(['id_product', 'uuid', 'name_vn', 'price', 'image_vn', 'category_id', 'quantity'])
            ->get()
            ->keyBy('id_product');

        $maxSold = (int) $topSold->first()->total_sold ?: 1;

        return $topSold->map(function ($item) use ($products, $maxSold) {
            $product = $products->get($item->product_id);
            $sold = (int) $item->total_sold;
            $percent = min(100, round(($sold / $maxSold) * 100));

            return [
                'id_product' => $item->product_id,
                'name' => $product ? $product->name_vn : 'Sản phẩm #' . $item->product_id,
                'price' => $product ? (float) $product->price : 0,
                'image' => $product ? $product->image_vn : null,
                'category' => $product && $product->cate ? $product->cate->name_vn : null,
                'stock' => $product ? (int) $product->quantity : 0,
                'total_sold' => $sold,
                'total_revenue' => (float) $item->total_revenue,
                'percentage' => $percent,
            ];
        })->toArray();
    }

    /**
     * Get low stock products alert list.
     *
     * @param int $threshold
     * @param int $limit
     * @return array
     */
    public function getLowStockProducts(int $threshold = 5, int $limit = 10): array
    {
        return Product::lowStock($threshold)
            ->with('cate:id_cate_product,name_vn')
            ->select(['id_product', 'uuid', 'name_vn', 'price', 'quantity', 'image_vn', 'category_id', 'status'])
            ->orderBy('quantity', 'asc')
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                return [
                    'id_product' => $product->id_product,
                    'uuid' => $product->uuid,
                    'name' => $product->name_vn,
                    'price' => (float) $product->price,
                    'image' => $product->image_vn,
                    'category' => $product->cate ? $product->cate->name_vn : null,
                    'quantity' => (int) $product->quantity,
                    'is_out_of_stock' => $product->quantity <= 0,
                ];
            })
            ->toArray();
    }

    /**
     * Get overdue pending orders alert list (> X hours).
     *
     * @param int $hours
     * @param int $limit
     * @return array
     */
    public function getOverduePendingOrders(int $hours = 24, int $limit = 10): array
    {
        return OrderStatus::overduePending($hours)
            ->join('tp_order_shipping', 'tp_order_status.shipping_id', '=', 'tp_order_shipping.id_order_shipping')
            ->select([
                'tp_order_status.id_order_status',
                'tp_order_status.uuid_order_status',
                'tp_order_status.total',
                'tp_order_status.status',
                'tp_order_status.payment_method',
                'tp_order_status.created_at',
                'tp_order_shipping.f_name_order',
                'tp_order_shipping.l_name_order',
                'tp_order_shipping.phone',
                'tp_order_shipping.email',
            ])
            ->orderBy('tp_order_status.created_at', 'asc')
            ->limit($limit)
            ->get()
            ->map(function ($order) {
                $createdAt = Carbon::parse($order->created_at);
                $hoursDiff = (int) $createdAt->diffInHours(now());

                return [
                    'id_order_status' => $order->id_order_status,
                    'uuid' => $order->uuid_order_status,
                    'customer_name' => trim(($order->f_name_order ?? '') . ' ' . ($order->l_name_order ?? '')) ?: 'Khách hàng',
                    'phone' => $order->phone,
                    'email' => $order->email,
                    'total' => (float) $order->total,
                    'payment_method' => $order->payment_method,
                    'created_at' => $createdAt->format('d/m/Y H:i'),
                    'hours_overdue' => $hoursDiff,
                ];
            })
            ->toArray();
    }

    /**
     * Get recent orders with customer details.
     *
     * @param int $limit
     * @return array
     */
    public function getRecentOrders(int $limit = 5): array
    {
        return DB::table('tp_order_status')
            ->join('tp_order_shipping', 'tp_order_status.shipping_id', '=', 'tp_order_shipping.id_order_shipping')
            ->select([
                'tp_order_status.id_order_status',
                'tp_order_status.uuid_order_status',
                'tp_order_status.total',
                'tp_order_status.status',
                'tp_order_status.payment_method',
                'tp_order_status.created_at',
                'tp_order_shipping.f_name_order',
                'tp_order_shipping.l_name_order',
                'tp_order_shipping.phone',
            ])
            ->orderBy('tp_order_status.created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($order) {
                return [
                    'id_order_status' => $order->id_order_status,
                    'uuid' => $order->uuid_order_status,
                    'customer_name' => trim(($order->f_name_order ?? '') . ' ' . ($order->l_name_order ?? '')) ?: 'Khách hàng',
                    'phone' => $order->phone,
                    'total' => (float) $order->total,
                    'status' => (int) $order->status,
                    'payment_method' => $order->payment_method,
                    'created_at' => Carbon::parse($order->created_at)->format('d/m/Y H:i'),
                ];
            })
            ->toArray();
    }
}
