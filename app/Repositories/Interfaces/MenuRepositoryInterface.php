<?php

namespace App\Repositories\Interfaces;

interface MenuRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered menus with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredMenus(array $filters, $perPage = 10);

    /**
     * Get all active menus
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveMenus();

    /**
     * Get menu tree for display
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMenuTree();

    /**
     * Get maximum stt for a given parent_id
     *
     * @param int $parentId
     * @return int
     */
    public function getMaxStt(int $parentId): int;

    /**
     * Update menu hierarchy recursively
     *
     * @param array $nodes
     * @param int $parentId
     * @param int $depth
     * @return void
     */
    public function updateHierarchy(array $nodes, int $parentId = 0, int $depth = 1): void;
}
