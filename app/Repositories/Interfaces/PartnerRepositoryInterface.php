<?php

namespace App\Repositories\Interfaces;

interface PartnerRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered partners with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredPartners(array $filters, $perPage = 10);

    /**
     * Get active partners
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActivePartners();

    /**
     * Update numerical order of a partner
     *
     * @param string $uuid
     * @param int $order
     * @return bool
     */
    public function updateOrder($uuid, $order);
}
