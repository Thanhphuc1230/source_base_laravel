<?php

namespace App\Http\Controllers\Admin;

use App\Models\Analytic;
use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AnalyticController extends BaseController
{
    /**
     * @var string
     */
    protected $module;

    /**
     * @var string
     */
    protected $nameItem;

    /**
     * @var DashboardService
     */
    protected $dashboardService;

    /**
     * AnalyticController constructor.
     *
     * @param DashboardService $dashboardService
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->module = 'analytics';
        $this->nameItem = 'Bảng điều khiển';
        $this->dashboardService = $dashboardService;

        parent::__construct($this->module);

        View::share('nameClass', 'chart');
    }

    /**
     * Display the main admin dashboard with real-time KPI metrics, alerts, and charts.
     *
     * @param Request $request
     * @return \Illuminate\View\View|JsonResponse
     */
    public function index(Request $request)
    {
        // 1. Handle AJAX chart filtering
        if ($request->ajax() || $request->has('ajax')) {
            // Revenue Chart AJAX request
            if ($request->input('chart_type') === 'revenue' || $request->has('period')) {
                $period = $request->input('period', '30_days');
                $year = $request->input('year') ? (int) $request->input('year') : null;
                $chartData = $this->dashboardService->getRevenueChartData($period, $year);

                return response()->json([
                    'success' => true,
                    'chartData' => $chartData,
                ]);
            }

            // Web Visits Chart AJAX request
            if ($request->has('month') && $request->has('year')) {
                $month = (int) $request->input('month', date('n'));
                $year = (int) $request->input('year', date('Y'));

                $visits = Analytic::whereMonth('visit_date', $month)
                    ->whereYear('visit_date', $year)
                    ->orderBy('visit_date', 'asc')
                    ->get();

                $chartData = $visits->map(function ($item) {
                    return [
                        'date' => Carbon::parse($item->visit_date)->format('d-m-Y'),
                        'count' => (int) $item->visit_count,
                    ];
                });

                $monthViews = (int) Analytic::whereMonth('visit_date', $month)
                    ->whereYear('visit_date', $year)
                    ->sum('visit_count');

                return response()->json([
                    'success' => true,
                    'chartData' => $chartData,
                    'monthViews' => $monthViews,
                    'month' => $month,
                    'year' => $year,
                ]);
            }
        }

        // 2. Fetch overview KPI datasets via Service Layer (Redis cached)
        $overview = $this->dashboardService->getOverviewData($request->has('refresh'));

        // 3. Initial 30-day Revenue Chart dataset
        $revenueChartData = $this->dashboardService->getRevenueChartData('30_days');

        // 4. Initial Month Visits Chart dataset
        $now = Carbon::now();
        $visits = Analytic::whereMonth('visit_date', $now->month)
            ->whereYear('visit_date', $now->year)
            ->orderBy('visit_date', 'asc')
            ->get();

        $visitChartData = $visits->map(function ($item) {
            return [
                'date' => Carbon::parse($item->visit_date)->format('d-m-Y'),
                'count' => (int) $item->visit_count,
            ];
        });

        $data = array_merge($overview, [
            'nameItem' => $this->nameItem,
            'revenueChartData' => $revenueChartData,
            'chartData' => json_encode($visitChartData),
        ]);

        return $this->view_admin('dashboard', $data);
    }
}
