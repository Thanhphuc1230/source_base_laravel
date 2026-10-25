<?php

namespace App\Repositories\Interfaces;

interface MediaRepositoryInterface extends RepositoryInterface
{
    /**
     * Get paginated media items with filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginateWithFilters(array $filters = [], int $perPage = 24);

    /**
     * Find media item by path.
     *
     * @param string $path
     * @return \App\Models\Media|null
     */
    public function findByPath(string $path);
}
