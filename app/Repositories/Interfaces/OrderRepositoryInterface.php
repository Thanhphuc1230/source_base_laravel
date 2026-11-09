<?php

namespace App\Repositories\Interfaces;

interface OrderRepositoryInterface extends RepositoryInterface
{
    /**
     * Get paginated orders list with shipping details and filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getOrdersPaginated(array $filters = [], int $perPage = 10);

    /**
     * Find order status record by UUID.
     *
     * @param string $uuid
     * @param array $columns
     * @return \App\Models\OrderStatus|null
     */
    public function findByUuid($uuid, $columns = ['*']);

    /**
     * Get joined order & shipping info for detail view.
     *
     * @param string $uuid
     * @return object|null
     */
    public function getOrderDetailByUuid(string $uuid);

    /**
     * Get products in a specific order.
     *
     * @param int $orderStatusId
     * @return \Illuminate\Support\Collection
     */
    public function getOrderProducts(int $orderStatusId);

    /**
     * Update order status field by UUID.
     *
     * @param string $uuid
     * @param int|string $status
     * @param string $field
     * @return \App\Models\OrderStatus|null
     */
    public function updateStatusByUuid(string $uuid, $status, string $field = 'status');

    /**
     * Completely remove order, shipping info, and order line items.
     *
     * @param string $uuid
     * @return bool
     */
    public function deleteOrderByUuid(string $uuid);
}
