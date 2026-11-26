<?php

namespace App\Services;

use App\Repositories\Interfaces\RedirectRepositoryInterface;
use Illuminate\Support\Str;

class RedirectService
{
    protected RedirectRepositoryInterface $redirectRepository;

    public function __construct(RedirectRepositoryInterface $redirectRepository)
    {
        $this->redirectRepository = $redirectRepository;
    }

    public function normalizePath(string $urlOrPath): string
    {
        $path = trim($urlOrPath);
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $parsed = parse_url($path, PHP_URL_PATH);
            $path = $parsed !== null && $parsed !== false ? $parsed : '/';
        }
        $path = '/' . ltrim($path, '/');
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        return mb_substr($path, 0, 255);
    }

    public function resolveRedirect(string $path): ?array
    {
        $normalized = $this->normalizePath($path);
        $cacheKey = 'redirect_lookup_' . md5($normalized);
        $sentinel = '__NONE__';

        $result = CacheService::remember('redirects', $cacheKey, CacheService::TTL['medium'], function () use ($normalized, $sentinel) {
            $row = $this->redirectRepository->findActiveRedirectBySource($normalized);
            if (! $row) {
                return $sentinel;
            }

            return [
                'id' => $row->id_redirect,
                'target_url' => $row->target_url,
                'status_code' => (int) $row->status_code,
            ];
        });

        return $result === $sentinel ? null : $result;
    }

    public function recordHit(int $idRedirect): void
    {
        try {
            $this->redirectRepository->incrementHit($idRedirect);
        } catch (\Throwable $e) {
            // Non-blocking analytics update
        }
    }

    public function record404(string $path): void
    {
        $normalized = $this->normalizePath($path);

        // Skip static asset extensions so missing images/maps do not pollute 404 monitor
        if (preg_match('/\.(jpg|jpeg|png|gif|webp|svg|ico|css|js|map|woff|woff2|ttf|eot|pdf|zip)$/i', $normalized)) {
            return;
        }

        try {
            $existing = $this->redirectRepository->findBySourceUrl($normalized);
            if ($existing) {
                $this->redirectRepository->incrementHit($existing->id_redirect);

                return;
            }

            $this->redirectRepository->create([
                'uuid' => (string) Str::uuid(),
                'source_url' => $normalized,
                'target_url' => null,
                'status_code' => 404,
                'hits' => 1,
                'last_hit_at' => now(),
                'is_active' => false,
                'note' => '404 Monitor',
            ]);
        } catch (\Throwable $e) {
            // Ignore race condition on duplicate 404 insert
        }
    }

    public function registerSlugChangeRedirect(string $oldSlug, string $newSlug, int $statusCode = 301): void
    {
        $source = $this->normalizePath($oldSlug);
        $target = $this->normalizePath($newSlug);

        if ($source === '/' || $target === '/' || $source === $target) {
            return;
        }

        $existing = $this->redirectRepository->findBySourceUrl($source);
        if ($existing) {
            $this->redirectRepository->update([
                'target_url' => $target,
                'status_code' => $statusCode,
                'is_active' => true,
                'note' => 'Auto-created on slug change',
            ], $existing->id_redirect);
        } else {
            $this->redirectRepository->create([
                'uuid' => (string) Str::uuid(),
                'source_url' => $source,
                'target_url' => $target,
                'status_code' => $statusCode,
                'hits' => 0,
                'is_active' => true,
                'note' => 'Auto-created on slug change',
            ]);
        }

        $this->flushCache($source);
    }

    public function paginate(array $filters = [], int $perPage = 20)
    {
        return $this->redirectRepository->paginateWithFilters($filters, $perPage);
    }

    public function findByUuid(string $uuid)
    {
        return $this->redirectRepository->findByUuid($uuid);
    }

    public function create(array $data)
    {
        $source = $this->normalizePath($data['source_url']);
        $target = ! empty($data['target_url']) ? trim((string) $data['target_url']) : null;
        if ($target !== null && ! str_starts_with($target, 'http://') && ! str_starts_with($target, 'https://')) {
            $target = $this->normalizePath($target);
        }

        $record = $this->redirectRepository->create([
            'uuid' => (string) Str::uuid(),
            'source_url' => $source,
            'target_url' => $target,
            'status_code' => (int) ($data['status_code'] ?? 301),
            'is_active' => (bool) ($data['is_active'] ?? true),
            'note' => $data['note'] ?? null,
        ]);

        $this->flushCache($source);

        return $record;
    }

    public function update(string $uuid, array $data): bool
    {
        $existing = $this->redirectRepository->findByUuid($uuid);
        if (! $existing) {
            return false;
        }

        $source = $this->normalizePath($data['source_url']);
        $target = ! empty($data['target_url']) ? trim((string) $data['target_url']) : null;
        if ($target !== null && ! str_starts_with($target, 'http://') && ! str_starts_with($target, 'https://')) {
            $target = $this->normalizePath($target);
        }

        $oldSource = $existing->source_url;
        $updated = (bool) $this->redirectRepository->update([
            'source_url' => $source,
            'target_url' => $target,
            'status_code' => (int) ($data['status_code'] ?? 301),
            'is_active' => (bool) ($data['is_active'] ?? true),
            'note' => $data['note'] ?? null,
        ], $uuid);

        $this->flushCache($oldSource);
        $this->flushCache($source);

        return $updated;
    }

    public function toggleStatus(string $uuid, int $status): bool
    {
        $existing = $this->redirectRepository->findByUuid($uuid);
        if (! $existing) {
            return false;
        }

        $updated = (bool) $this->redirectRepository->update(['is_active' => (bool) $status], $uuid);
        $this->flushCache($existing->source_url);

        return $updated;
    }

    public function delete(string $uuid): bool
    {
        $existing = $this->redirectRepository->findByUuid($uuid);
        if (! $existing) {
            return false;
        }

        $deleted = (bool) $this->redirectRepository->delete($existing->id_redirect);
        $this->flushCache($existing->source_url);

        return $deleted;
    }

    public function destroyAll(array $uuids): int
    {
        $count = $this->redirectRepository->destroyByUuids($uuids);
        CacheService::forgetTag('redirects');

        return $count;
    }

    public function flushCache(?string $sourceUrl = null): void
    {
        if ($sourceUrl !== null) {
            $normalized = $this->normalizePath($sourceUrl);
            CacheService::forget('redirects', 'redirect_lookup_' . md5($normalized));
        }
        CacheService::forgetTag('redirects');
    }
}
