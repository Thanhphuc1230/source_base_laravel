<?php

namespace App\Repositories\Eloquent;

use App\Models\Partner;
use App\Repositories\Interfaces\PartnerRepositoryInterface;

class PartnerRepository extends BaseRepository implements PartnerRepositoryInterface
{
    /**
     * PartnerRepository constructor.
     *
     * @param Partner $model
     */
    public function __construct(Partner $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getFilteredPartners(array $filters, $perPage = 10)
    {
        $query = $this->model->query();

        // Apply search filter
        if (isset($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('status', '=', $searchTerm === 'active' ? 1 : 0);
            });
        }

        // Apply sorting
        $sortField = $filters['sort_field'] ?? 'stt';
        $sortDirection = $filters['sort_direction'] ?? 'asc';
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function getActivePartners()
    {
        return $this->model->where('status', 1)
            ->orderBy('stt', 'asc')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function updateOrder($uuid, $order)
    {
        return $this->model->where('uuid', $uuid)->update(['stt' => $order]);
    }
}
