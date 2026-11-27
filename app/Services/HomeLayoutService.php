<?php

namespace App\Services;

use App\Models\HomeSection;
use App\Repositories\Interfaces\CateNewRepositoryInterface;
use App\Repositories\Interfaces\CateProductRepositoryInterface;
use App\Repositories\Interfaces\HomeSectionRepositoryInterface;
use App\View\Composers\FrontendComposer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class HomeLayoutService
{
    public function __construct(
        protected HomeSectionRepositoryInterface $homeSectionRepository,
        protected CateProductRepositoryInterface $cateProductRepository,
        protected CateNewRepositoryInterface $cateNewRepository
    ) {}

    public function getBuilderData(): array
    {
        return [
            'sections' => $this->homeSectionRepository->getAllOrdered(),
            'cateProducts' => $this->cateProductRepository->getModelInstance()
                ->select('id_cate_product', 'name', 'slug', 'parent_id', 'status', 'stt')
                ->where('status', 1)
                ->orderBy('stt', 'asc')
                ->get(),
            'cateNews' => $this->cateNewRepository->getModelInstance()
                ->select('id_cate_new', 'name', 'slug', 'parent_id', 'status', 'stt')
                ->where('status', 1)
                ->orderBy('stt', 'asc')
                ->get(),
            'systemBlocks' => [
                'hero' => 'Banner Chính (Hero Slider)',
                'features' => 'Cam Kết & Dịch Vụ (Features)',
                'icon_categories' => 'Icon Danh Mục Nổi Bật',
                'flash_sale' => 'Sản Phẩm Nổi Bật (Hot Sale)',
                'mid_banner' => 'Banner Đôi Giữa Trang',
                'brands' => 'Thương Hiệu Đối Tác',
            ],
        ];
    }

    public function addSections(array $payload): int
    {
        $maxOrder = $this->homeSectionRepository->getMaxOrder();
        $limit = max(1, (int) ($payload['limit'] ?? 8));
        $created = 0;

        $type = $payload['type'] ?? '';

        if ($type === 'system_blocks' && ! empty($payload['block_types'])) {
            foreach ((array) $payload['block_types'] as $blockType) {
                if (! array_key_exists($blockType, HomeSection::TYPES)) {
                    continue;
                }
                $maxOrder++;
                $this->homeSectionRepository->create([
                    'uuid' => (string) Str::uuid(),
                    'type' => $blockType,
                    'reference_id' => null,
                    'title_vn' => HomeSection::TYPES[$blockType],
                    'title_en' => ucwords(str_replace('_', ' ', $blockType)),
                    'limit' => $limit,
                    'stt' => $maxOrder,
                    'status' => true,
                ]);
                $created++;
            }
        } elseif ($type === 'category_products' && ! empty($payload['reference_ids'])) {
            $categories = $this->cateProductRepository->getModelInstance()
                ->whereIn('id_cate_product', (array) $payload['reference_ids'])
                ->get()
                ->keyBy('id_cate_product');

            foreach ((array) $payload['reference_ids'] as $refId) {
                $cat = $categories->get($refId);
                if (! $cat) {
                    continue;
                }
                $maxOrder++;
                $this->homeSectionRepository->create([
                    'uuid' => (string) Str::uuid(),
                    'type' => 'category_products',
                    'reference_id' => (int) $refId,
                    'title_vn' => $cat->getTranslation('name', 'vi') ?: $cat->name,
                    'title_en' => $cat->getTranslation('name', 'en') ?: $cat->name,
                    'limit' => $limit,
                    'stt' => $maxOrder,
                    'status' => true,
                ]);
                $created++;
            }
        } elseif ($type === 'news' && ! empty($payload['reference_ids'])) {
            $categories = $this->cateNewRepository->getModelInstance()
                ->whereIn('id_cate_new', (array) $payload['reference_ids'])
                ->get()
                ->keyBy('id_cate_new');

            foreach ((array) $payload['reference_ids'] as $refId) {
                $cat = $categories->get($refId);
                if (! $cat) {
                    continue;
                }
                $maxOrder++;
                $this->homeSectionRepository->create([
                    'uuid' => (string) Str::uuid(),
                    'type' => 'news',
                    'reference_id' => (int) $refId,
                    'title_vn' => $cat->getTranslation('name', 'vi') ?: $cat->name,
                    'title_en' => $cat->getTranslation('name', 'en') ?: $cat->name,
                    'limit' => $limit,
                    'stt' => $maxOrder,
                    'status' => true,
                ]);
                $created++;
            }
        } elseif (array_key_exists($type, HomeSection::TYPES)) {
            $maxOrder++;
            $this->homeSectionRepository->create([
                'uuid' => (string) Str::uuid(),
                'type' => $type,
                'reference_id' => isset($payload['reference_id']) ? (int) $payload['reference_id'] : null,
                'title_vn' => $payload['title_vn'] ?? HomeSection::TYPES[$type],
                'title_en' => $payload['title_en'] ?? ucwords(str_replace('_', ' ', $type)),
                'limit' => $limit,
                'stt' => $maxOrder,
                'status' => (bool) ($payload['status'] ?? true),
            ]);
            $created++;
        }

        if ($created > 0) {
            $this->flushHomeCaches();
        }

        return $created;
    }

    public function updateSection(string $uuid, array $data): bool
    {
        $section = $this->homeSectionRepository->findByUuid($uuid);
        if (! $section) {
            return false;
        }

        $updated = (bool) $this->homeSectionRepository->update([
            'title_vn' => $data['title_vn'] ?? $section->title_vn,
            'title_en' => $data['title_en'] ?? $section->title_en,
            'limit' => max(1, (int) ($data['limit'] ?? $section->limit)),
            'status' => array_key_exists('status', $data) ? (bool) $data['status'] : $section->status,
        ], $section->id);

        if ($updated) {
            $this->flushHomeCaches();
        }

        return $updated;
    }

    public function toggleStatus(string $uuid, int $status): bool
    {
        $section = $this->homeSectionRepository->findByUuid($uuid);
        if (! $section) {
            return false;
        }

        $updated = (bool) $this->homeSectionRepository->update(['status' => (bool) $status], $section->id);
        $this->flushHomeCaches();

        return $updated;
    }

    public function reorder(array $orderedUuids): void
    {
        $this->homeSectionRepository->updateOrderByUuids($orderedUuids);
        $this->flushHomeCaches();
    }

    public function deleteSection(string $uuid): bool
    {
        $section = $this->homeSectionRepository->findByUuid($uuid);
        if (! $section) {
            return false;
        }

        $deleted = (bool) $this->homeSectionRepository->delete($section->id);
        if ($deleted) {
            $this->flushHomeCaches();
        }

        return $deleted;
    }

    public function flushHomeCaches(): void
    {
        CacheService::forgetTags([
            'frontend',
            'system',
            'website',
            'categories',
            'products',
            'sliders',
            'news',
            'brands',
            'features',
        ]);
        Cache::forget('home_page_aggregated_data');
        Cache::forget('frontend_global_data');
        FrontendComposer::flushMemoryCache();
    }
}
