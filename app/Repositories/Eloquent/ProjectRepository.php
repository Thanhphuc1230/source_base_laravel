<?php

namespace App\Repositories\Eloquent;

use App\Models\CateProject;
use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Services\SlugService;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    protected $slugService;

    /**
     * ProjectRepository constructor.
     *
     * @param Project $model
     * @param SlugService $slugService
     */
    public function __construct(Project $model, SlugService $slugService)
    {
        parent::__construct($model);
        $this->slugService = $slugService;
    }

    /**
     * @inheritDoc
     */
    public function getFilteredProjects(array $filters, $perPage = 10)
    {
        $query = $this->model->query();

        // Search filter
        if (isset($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name_vn', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('status', '=', $searchTerm === 'active' ? 1 : 0);
            });
        }

        // Category filter (support hierarchy parent & children)
        if (!empty($filters['category']) && $filters['category'] != 0) {
            $categoryId = (int) $filters['category'];
            $childCategoryIds = CateProject::where('parent_id', $categoryId)->pluck('id_cate_project')->toArray();
            $categoryIds = array_merge([$categoryId], $childCategoryIds);
            $query->whereIn('category_id', $categoryIds);
        }

        // Sorting
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->with('cate')->select('uuid', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'status', 'home', 'stt', 'created_at', 'category_id', 'image_vn', 'image_en', 'id_project')->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function getActiveCategories()
    {
        return CateProject::with('children')
            ->where('status', 1)
            ->where('parent_id', 0)
            ->get();
    }
}
