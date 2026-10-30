<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Activitylog\Models\Activity;

interface ActivityLogRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered activity logs with pagination.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getFilteredLogs(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    /**
     * Get distinct log module names for filtering.
     *
     * @return array
     */
    public function getDistinctLogNames(): array;

    /**
     * Find log by ID with causer and subject relations.
     *
     * @param int $id
     * @return Activity|null
     */
    public function findById(int $id): ?Activity;

    /**
     * Clean activity logs older than specified days.
     *
     * @param int $days
     * @return int
     */
    public function cleanOldLogs(int $days = 60): int;

    /**
     * Delete a specific activity log.
     *
     * @param int $id
     * @return bool
     */
    public function deleteLog(int $id): bool;
}
