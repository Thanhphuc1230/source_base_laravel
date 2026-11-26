<?php

namespace App\Services;

use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\News;
use App\Models\Product;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\Interfaces\NewsRepositoryInterface;

class NewsService
{
    protected $newsRepository;
    protected $commentRepository;

    public function __construct(NewsRepositoryInterface $newsRepository, CommentRepositoryInterface $commentRepository)
    {
        $this->newsRepository = $newsRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * Get data for news category page
     *
     * @param int $id_cate_new
     * @return array
     */
    public function getCategoryNewsData($id_cate_new)
    {
        $data = [];

        // Cache category detail
        $data['category_detail'] = CateNew::where('status', 1)
            ->where('id_cate_new', $id_cate_new)
            ->select('id_cate_new', 'name', 'slug', 'status')
            ->firstOrFail();

        $perPage = (int) \App\Models\NewsSetting::get('news_pagination', 8);

        // Cache news in category
        $data['news'] = News::where('category_id', $data['category_detail']->id_cate_new)
            ->where('status', 1)
            ->select('id_new', 'name', 'intro', 'slug', 'image_vn', 'image_en', 'created_at', 'category_id')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Get common data
        $data = array_merge($data, $this->getCommonFrontendData());

        return $data;
    }

    /**
     * Get data for news detail page
     *
     * @param int $id_news
     * @return array
     */
    public function getDetailNewsData($id_news)
    {
        $data = [];

        // Cache news detail with category relationship
        $data['news_detail'] = News::with(['cate' => function ($query) {
            $query->select('id_cate_new', 'name', 'slug');
        }])
            ->where('id_new', $id_news)
            ->select('id_new', 'name', 'slug', 'image_vn', 'image_en', 'content', 'created_at', 'category_id', 'keyword', 'description')
            ->firstOrFail();

        // Cache related news
        $data['related_news'] = News::where('category_id', $data['news_detail']->category_id)
            ->where('status', 1)
            ->where('id_new', '!=', $data['news_detail']->id_new)
            ->select('id_new', 'name', 'slug', 'image_vn', 'image_en', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Cache approved comments
        $data['comments'] = $this->commentRepository->getApprovedCommentsForItem('news', $data['news_detail']->id_new);

        // Auto Table of Contents & Reading Time
        $rawContent = function_exists('lang') ? lang($data['news_detail'], 'content') : ($data['news_detail']->content ?? '');
        $processed = app(ContentTocService::class)->process((string) $rawContent);
        $data['processed_content'] = $processed['html'];
        $data['toc'] = $processed['toc'];
        $data['reading_time'] = $processed['reading_time'];

        // Get common data
        $data = array_merge($data, $this->getCommonFrontendData());

        return $data;
    }

    /**
     * Get common data used across frontend pages
     *
     * @return array
     */
    private function getCommonFrontendData()
    {
        return [
            'category_product' => CateProduct::where('status', 1)
                ->where('parent_id', 0)
                ->select('name', 'status', 'slug')
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get(),

            'product_hot' => Product::where('status', 1)
                ->where('hot', 1)
                ->select('name', 'slug', 'image_vn', 'image_en', 'price', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];
    }
}