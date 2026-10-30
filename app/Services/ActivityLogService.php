<?php

namespace App\Services;

use App\Repositories\Interfaces\ActivityLogRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Activitylog\Models\Activity;

class ActivityLogService
{
    /**
     * @var ActivityLogRepositoryInterface
     */
    protected $activityLogRepository;

    /**
     * Common attribute translations for human-readable diff.
     *
     * @var array
     */
    protected static $attributeLabels = [
        'name_vn' => 'Tên (Tiếng Việt)',
        'name_en' => 'Tên (English)',
        'slug_vn' => 'Đường dẫn (VN)',
        'slug_en' => 'Đường dẫn (EN)',
        'intro_vn' => 'Mô tả ngắn (VN)',
        'intro_en' => 'Mô tả ngắn (EN)',
        'content_vn' => 'Nội dung (VN)',
        'content_en' => 'Nội dung (EN)',
        'keyword_vn' => 'Từ khóa SEO (VN)',
        'keyword_en' => 'Từ khóa SEO (EN)',
        'price' => 'Giá bán',
        'price_old' => 'Giá cũ',
        'status' => 'Trạng thái',
        'is_featured' => 'Nổi bật',
        'is_hot' => 'Bán chạy',
        'category_id' => 'Danh mục',
        'parent_id' => 'Danh mục cha',
        'image_vn' => 'Hình ảnh đại diện',
        'image_en' => 'Hình ảnh (EN)',
        'image_detail' => 'Thư viện ảnh',
        'sort' => 'Thứ tự hiển thị',
        'fullname' => 'Họ và tên',
        'username' => 'Tên đăng nhập',
        'email' => 'Địa chỉ Email',
        'phone' => 'Số điện thoại',
        'address' => 'Địa chỉ',
        'level' => 'Phân quyền',
        'avatar' => 'Ảnh đại diện',
        'total' => 'Tổng tiền',
        'payment_method' => 'Phương thức thanh toán',
        'display_name' => 'Tên hiển thị',
        'description' => 'Mô tả',
    ];

    /**
     * ActivityLogService constructor.
     *
     * @param ActivityLogRepositoryInterface $activityLogRepository
     */
    public function __construct(ActivityLogRepositoryInterface $activityLogRepository)
    {
        $this->activityLogRepository = $activityLogRepository;
    }

    /**
     * Get paginated and filtered activity logs.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getLogs(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->activityLogRepository->getFilteredLogs($filters, $perPage);
    }

    /**
     * Get distinct log module names for filtering.
     *
     * @return array
     */
    public function getLogNames(): array
    {
        return $this->activityLogRepository->getDistinctLogNames();
    }

    /**
     * Get single log with formatted diff data.
     *
     * @param int $id
     * @return array|null
     */
    public function getLogDetail(int $id): ?array
    {
        $log = $this->activityLogRepository->findById($id);
        if (!$log) {
            return null;
        }

        $formattedDiff = $this->formatDiff($log);

        return [
            'log' => $log,
            'event_label' => $this->getEventBadge($log->event),
            'diff' => $formattedDiff,
        ];
    }

    /**
     * Format before/after diff for display.
     *
     * @param Activity $log
     * @return array
     */
    public function formatDiff(Activity $log): array
    {
        $properties = $log->properties ? $log->properties->toArray() : [];
        $attributes = $properties['attributes'] ?? [];
        $old = $properties['old'] ?? [];

        $diff = [];
        $allKeys = array_unique(array_merge(array_keys($old), array_keys($attributes)));

        foreach ($allKeys as $key) {
            if (in_array($key, ['created_at', 'updated_at', 'password', 'remember_token'])) {
                continue;
            }

            $oldVal = $old[$key] ?? null;
            $newVal = $attributes[$key] ?? null;

            // Only include if value actually changed or it's a create/delete event
            if ($log->event === 'updated' && $oldVal === $newVal) {
                continue;
            }

            $diff[] = [
                'field' => $key,
                'label' => self::$attributeLabels[$key] ?? ucfirst(str_replace('_', ' ', $key)),
                'old' => is_array($oldVal) ? json_encode($oldVal, JSON_UNESCAPED_UNICODE) : $oldVal,
                'new' => is_array($newVal) ? json_encode($newVal, JSON_UNESCAPED_UNICODE) : $newVal,
            ];
        }

        return $diff;
    }

    /**
     * Get human-readable badge data for an event.
     *
     * @param string|null $event
     * @return array
     */
    public function getEventBadge(?string $event): array
    {
        return match ($event) {
            'created' => ['label' => 'Thêm mới', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
            'updated' => ['label' => 'Cập nhật', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
            'deleted' => ['label' => 'Xóa', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
            default => ['label' => ucfirst($event ?? 'Khác'), 'class' => 'bg-slate-50 text-slate-700 border-slate-200'],
        };
    }

    /**
     * Clean activity logs older than specified days.
     *
     * @param int $days
     * @return int
     */
    public function cleanLogs(int $days = 60): int
    {
        return $this->activityLogRepository->cleanOldLogs($days);
    }

    /**
     * Delete a single activity log.
     *
     * @param int $id
     * @return bool
     */
    public function deleteLog(int $id): bool
    {
        return $this->activityLogRepository->deleteLog($id);
    }
}
