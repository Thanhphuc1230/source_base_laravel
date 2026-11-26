<?php

namespace App\Repositories\Interfaces;

interface RedirectRepositoryInterface extends RepositoryInterface
{
    public function findActiveRedirectBySource(string $sourceUrl);

    public function findBySourceUrl(string $sourceUrl);

    public function paginateWithFilters(array $filters = [], int $perPage = 20);

    public function incrementHit(int $idRedirect): void;

    public function destroyByUuids(array $uuids): int;
}
