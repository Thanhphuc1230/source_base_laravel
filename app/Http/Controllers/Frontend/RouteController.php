<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\SlugResolutionService;
use Illuminate\Support\Facades\Log;

class RouteController extends Controller
{
    public function __construct(
        protected SlugResolutionService $slugResolutionService
    ) {}

    /**
     * Universal route handler - xử lý tất cả slug với fallback chain
     * Priority: Product Detail > News Detail > Category Product > Category News > Service > Project > Page > Brand
     */
    public function resolve($slug)
    {
        $result = $this->slugResolutionService->findContentBySlug($slug);

        if (! $result) {
            return view('errors.404');
        }

        return $this->dispatchToController($result);
    }

    /**
     * Dispatch đến controller tương ứng
     */
    private function dispatchToController(array $content): mixed
    {
        try {
            switch ($content['type']) {
                case 'product':
                    return app(ProductController::class)->detailProduct($content['id']);

                case 'news':
                    return app(NewsController::class)->detailNews($content['id']);

                case 'cate_product':
                    return app(ProductController::class)->categoryProduct($content['id']);

                case 'cate_news':
                    return app(NewsController::class)->categoryNews($content['id']);

                case 'service':
                    return app(ServiceController::class)->detailService($content['id']);

                case 'cate_service':
                    return app(ServiceController::class)->categoryService($content['id']);

                case 'project':
                    return app(ProjectController::class)->detailProject($content['id']);

                case 'cate_project':
                    return app(ProjectController::class)->categoryProject($content['id']);

                case 'page':
                    return app(PageController::class)->page($content['id']);

                case 'brand':
                    return app(ProductController::class)->brandProduct($content['id']);

                default:
                    return view('errors.404');
            }
        } catch (\Exception $e) {
            Log::error("Error dispatching controller for type '{$content['type']}': " . $e->getMessage());
            return view('errors.404');
        }
    }
}
