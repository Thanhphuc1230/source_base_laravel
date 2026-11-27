<?php

namespace App\Repositories\Interfaces;

interface HomeSectionRepositoryInterface extends RepositoryInterface
{
    public function getAllOrdered();

    public function getActiveOrdered();

    public function getMaxOrder(): int;

    public function updateOrderByUuids(array $orderedUuids): void;
}
