<?php

namespace App\Repositories\Eloquent;

use App\Models\CateNew;
use App\Models\News;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use App\Services\SlugService;
use Illuminate\Support\Str;

class NewsRepository extends BaseRepository implements NewsRepositoryInterface
{
    protected $slugService;

    /**
     * NewsRepository constructor.
     *
     * @param News $model
     * @param SlugService $slugService
     */
    public function __construct(News $model, SlugService $slugService)
    {
        parent::__construct($model);
        $this->slugService = $slugService;
    }

    /**
     * @inheritDoc
     */
    public function getFilteredNews(array $filters, $perPage = 10)
    {
        $query = $this->model->query();

        // Search filter
        if (!empty($filters['search'])) {
            $searchTerm = trim($filters['search']);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name_vn', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('name_en', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Category filter (support hierarchy parent & children)
        if (!empty($filters['category']) && $filters['category'] != 0) {
            $categoryId = (int) $filters['category'];
            $childCategoryIds = CateNew::where('parent_id', $categoryId)->pluck('id_cate_new')->toArray();
            $categoryIds = array_merge([$categoryId], $childCategoryIds);
            $query->whereIn('category_id', $categoryIds);
        }

        // Sorting
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->with(['cate:id_cate_new,name_vn,name_en,slug_vn,slug_en'])
            ->select('uuid', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'status', 'home', 'stt', 'created_at', 'category_id', 'image_vn', 'image_en', 'id_new')
            ->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function getActiveCategories()
    {
        return CateNew::with('children')
            ->where('status', 1)
            ->where('parent_id', 0)
            ->get();
    }
}
