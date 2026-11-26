<?php

namespace App\Repositories\Eloquent;

use App\Models\Redirect;
use App\Repositories\Interfaces\RedirectRepositoryInterface;

class RedirectRepository extends BaseRepository implements RedirectRepositoryInterface
{
    public function __construct(Redirect $model)
    {
        parent::__construct($model);
    }

    public function findActiveRedirectBySource(string $sourceUrl)
    {
        return $this->model
            ->select('id_redirect', 'source_url', 'target_url', 'status_code')
            ->where('source_url', $sourceUrl)
            ->where('is_active', true)
            ->whereIn('status_code', [301, 302])
            ->whereNotNull('target_url')
            ->first();
    }

    public function findBySourceUrl(string $sourceUrl)
    {
        return $this->model->where('source_url', $sourceUrl)->first();
    }

    public function paginateWithFilters(array $filters = [], int $perPage = 20)
    {
        $query = $this->model->newQuery();

        if (! empty($filters['search'])) {
            $search = trim((string) $filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('source_url', 'like', "%{$search}%")
                  ->orWhere('target_url', 'like', "%{$search}%")
                  ->orWhere('note', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['type'])) {
            if ($filters['type'] === '404') {
                $query->where('status_code', 404);
            } elseif (in_array((int) $filters['type'], [301, 302], true)) {
                $query->where('status_code', (int) $filters['type']);
            }
        }

        return $query->orderByDesc('hits')->orderByDesc('id_redirect')->paginate($perPage);
    }

    public function incrementHit(int $idRedirect): void
    {
        $this->model->where('id_redirect', $idRedirect)->update([
            'hits' => \Illuminate\Support\Facades\DB::raw('hits + 1'),
            'last_hit_at' => now(),
        ]);
    }

    public function destroyByUuids(array $uuids): int
    {
        return $this->model->whereIn('uuid', $uuids)->delete();
    }
}
