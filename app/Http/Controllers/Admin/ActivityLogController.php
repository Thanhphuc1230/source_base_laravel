<?php

namespace App\Http\Controllers\Admin;

use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends BaseController
{
    /**
     * @var ActivityLogService
     */
    protected $activityLogService;

    /**
     * ActivityLogController constructor.
     *
     * @param ActivityLogService $activityLogService
     */
    public function __construct(ActivityLogService $activityLogService)
    {
        parent::__construct('tp_activity_log');
        $this->activityLogService = $activityLogService;
    }

    /**
     * Display a listing of activity logs.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->input('search'),
            'log_name' => $request->input('log_name'),
            'event' => $request->input('event'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ];

        $logs = $this->activityLogService->getLogs($filters, 20);
        $modules = $this->activityLogService->getLogNames();

        return view('admin.modules.activity-log.index', compact('logs', 'modules', 'filters'));
    }

    /**
     * Display log details with formatted diff via AJAX.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $data = $this->activityLogService->getLogDetail($id);

        if (!$data) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $data['log']->id,
                'description' => $data['log']->description,
                'log_name' => $data['log']->log_name,
                'event' => $data['log']->event,
                'event_badge' => $data['event_label'],
                'causer' => $data['log']->causer ? ($data['log']->causer->fullname ?? $data['log']->causer->username ?? $data['log']->causer->email) : 'Hệ thống / Khách',
                'causer_email' => $data['log']->causer ? $data['log']->causer->email : null,
                'ip' => $data['log']->properties['ip'] ?? null,
                'user_agent' => $data['log']->properties['user_agent'] ?? null,
                'created_at' => $data['log']->created_at ? $data['log']->created_at->format('d/m/Y H:i:s') : null,
                'diff' => $data['diff'],
                'raw_properties' => $data['log']->properties,
            ]
        ]);
    }

    /**
     * Clean old activity logs.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function clean(Request $request): RedirectResponse
    {
        $days = (int) $request->input('days', 60);
        $deleted = $this->activityLogService->cleanLogs($days);

        return redirect()->route('admin.activity_log.index')
            ->with('success', "Đã dọn dẹp {$deleted} bản ghi nhật ký cũ hơn {$days} ngày.");
    }

    /**
     * Delete a specific activity log.
     *
     * @param int|string $id
     * @return RedirectResponse
     */
    public function delete($id): RedirectResponse
    {
        $this->activityLogService->deleteLog((int) $id);

        return redirect()->route('admin.activity_log.index')
            ->with('success', 'Đã xóa bản ghi nhật ký thành công.');
    }
}
