<?php

namespace App\Repositories\Eloquent;

use App\Models\Media;
use App\Repositories\Interfaces\MediaRepositoryInterface;

class MediaRepository extends BaseRepository implements MediaRepositoryInterface
{
    /**
     * MediaRepository constructor.
     *
     * @param Media $model
     */
    public function __construct(Media $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function paginateWithFilters(array $filters = [], int $perPage = 24)
    {
        $query = $this->model->newQuery();

        // Filter by search keyword
        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        // Filter by module
        if (!empty($filters['module']) && $filters['module'] !== 'all') {
            $query->where('module', $filters['module']);
        }

        // Filter by file type
        if (!empty($filters['type'])) {
            if ($filters['type'] === 'image') {
                $query->where(function ($q) {
                    $q->where('mime_type', 'like', 'image/%')
                      ->orWhereIn('extension', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                });
            } elseif ($filters['type'] === 'video') {
                $query->where(function ($q) {
                    $q->where('mime_type', 'like', 'video/%')
                      ->orWhereIn('extension', ['mp4', 'webm', 'mov', 'ogg']);
                });
            }
        }

        return $query->latest('id')->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function findByPath(string $path)
    {
        $normalized = ltrim($path, '/');
        return $this->model->where('path', $normalized)
            ->orWhere('path', '/' . $normalized)
            ->first();
    }
}
