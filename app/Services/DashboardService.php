<?php

namespace App\Services;

use App\Models\Analytic;
use App\Models\Product;
use App\Repositories\Interfaces\DashboardRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * @var DashboardRepositoryInterface
     */
    protected $dashboardRepository;

    /**
     * Cache TTL in seconds (5 minutes).
     */
    const CACHE_TTL = 300;

    /**
     * DashboardService constructor.
     *
     * @param DashboardRepositoryInterface $dashboardRepository
     */
    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    /**
     * Get complete dashboard overview dataset.
     *
     * @param bool $refresh
     * @return array
     */
    public function getOverviewData(bool $refresh = false): array
    {
        $cacheKey = 'admin:dashboard:overview';

        if ($refresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            $summary = $this->dashboardRepository->getRevenueAndOrderSummary();
            $lowStock = $this->dashboardRepository->getLowStockProducts(5, 10);
            $overdueOrders = $this->dashboardRepository->getOverduePendingOrders(24, 10);
            $topProducts = $this->dashboardRepository->getTopSellingProducts(5);
            $recentOrders = $this->dashboardRepository->getRecentOrders(6);

            // General counts
            $totalProducts = Product::count();
            $totalNews = DB::table('tp_news')->count();
            $totalComments = DB::table('tp_comments')->count();

            // Web visit metrics
            $totalViews = (int) Analytic::sum('visit_count');
            $now = Carbon::now();
            $monthViews = (int) Analytic::whereMonth('visit_date', $now->month)
                ->whereYear('visit_date', $now->year)
                ->sum('visit_count');

            $lastMonth = $now->copy()->subMonth();
            $lastMonthViews = (int) Analytic::whereMonth('visit_date', $lastMonth->month)
                ->whereYear('visit_date', $lastMonth->year)
                ->sum('visit_count');

            $weekStart = $now->copy()->startOfWeek();
            $weekEnd = $now->copy()->endOfWeek();
            $weekViews = (int) Analytic::whereBetween('visit_date', [$weekStart, $weekEnd])
                ->sum('visit_count');

            return [
                'summary' => $summary,
                'low_stock' => $lowStock,
                'low_stock_count' => count($lowStock),
                'overdue_orders' => $overdueOrders,
                'overdue_orders_count' => count($overdueOrders),
                'top_products' => $topProducts,
                'recent_orders' => $recentOrders,
                'total_products' => $totalProducts,
                'total_news' => $totalNews,
                'total_comments' => $totalComments,
                'total_views' => $totalViews,
                'month_views' => $monthViews,
                'last_month_views' => $lastMonthViews,
                'week_views' => $weekViews,
            ];
        });
    }

    /**
     * Get revenue chart data formatted for ApexCharts.
     *
     * @param string $period ('7_days', '30_days', 'year')
     * @param int|null $year
     * @return array
     */
    public function getRevenueChartData(string $period = '30_days', ?int $year = null): array
    {
        $targetYear = $year ?: (int) date('Y');
        $cacheKey = "admin:dashboard:chart:{$period}:{$targetYear}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($period, $targetYear) {
            if ($period === '7_days') {
                $data = $this->dashboardRepository->getDailyRevenueChart(7);
            } elseif ($period === 'year') {
                $data = $this->dashboardRepository->getMonthlyRevenueChart($targetYear);
            } else {
                $data = $this->dashboardRepository->getDailyRevenueChart(30);
            }

            return $data;
        });
    }

    /**
     * Flush dashboard cache.
     *
     * @param int|null $year
     * @return void
     */
    public function flushCache(?int $year = null): void
    {
        Cache::forget('admin:dashboard:overview');
        $currentYear = (int) date('Y');
        $years = array_unique([$currentYear, $currentYear - 1, $currentYear - 2, $year ?? $currentYear]);
        foreach ($years as $y) {
            Cache::forget("admin:dashboard:chart:7_days:{$y}");
            Cache::forget("admin:dashboard:chart:30_days:{$y}");
            Cache::forget("admin:dashboard:chart:year:{$y}");
        }
    }
}
