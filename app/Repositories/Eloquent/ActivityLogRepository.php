<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\ActivityLogRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Activitylog\Models\Activity;

class ActivityLogRepository extends BaseRepository implements ActivityLogRepositoryInterface
{
    /**
     * ActivityLogRepository constructor.
     *
     * @param Activity $model
     */
    public function __construct(Activity $model)
    {
        parent::__construct($model);
    }

    /**
     * Get filtered activity logs with pagination.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getFilteredLogs(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['causer']);

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('properties', 'like', "%{$search}%")
                    ->orWhereHasMorph('causer', [User::class], function ($userQ) use ($search) {
                        $userQ->where('fullname', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if (!empty($filters['log_name'])) {
            $query->where('log_name', $filters['log_name']);
        }

        if (!empty($filters['event'])) {
            $query->where('event', $filters['event']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->latest('id')->paginate($perPage);
    }

    /**
     * Get distinct log module names for filtering.
     *
     * @return array
     */
    public function getDistinctLogNames(): array
    {
        return $this->model->newQuery()
            ->select('log_name')
            ->distinct()
            ->whereNotNull('log_name')
            ->orderBy('log_name', 'asc')
            ->pluck('log_name')
            ->toArray();
    }

    /**
     * Find log by ID with causer and subject relations.
     *
     * @param int $id
     * @return Activity|null
     */
    public function findById(int $id): ?Activity
    {
        return $this->model->newQuery()
            ->with(['causer'])
            ->find($id);
    }

    /**
     * Clean activity logs older than specified days.
     *
     * @param int $days
     * @return int
     */
    public function cleanOldLogs(int $days = 60): int
    {
        $cutoffDate = Carbon::now()->subDays($days);
        return $this->model->newQuery()
            ->where('created_at', '<', $cutoffDate)
            ->delete();
    }

    /**
     * Delete a specific activity log.
     *
     * @param int $id
     * @return bool
     */
    public function deleteLog(int $id): bool
    {
        $log = $this->model->find($id);
        return $log ? (bool) $log->delete() : false;
    }
}
