<?php

namespace App\Repositories\Eloquent;

use App\Models\HomeSection;
use App\Repositories\Interfaces\HomeSectionRepositoryInterface;

class HomeSectionRepository extends BaseRepository implements HomeSectionRepositoryInterface
{
    public function __construct(HomeSection $model)
    {
        parent::__construct($model);
    }

    public function getAllOrdered()
    {
        return $this->model
            ->with([
                'categoryProduct:id_cate_product,name,slug',
                'categoryNews:id_cate_new,name,slug',
            ])
            ->orderBy('stt', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function getActiveOrdered()
    {
        return $this->model
            ->with([
                'categoryProduct:id_cate_product,uuid,name,slug,image_vn,image_en,parent_id,status,home,stt',
                'categoryNews:id_cate_new,uuid,name,slug,image_vn,image_en,status,home,stt',
            ])
            ->where('status', true)
            ->orderBy('stt', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function getMaxOrder(): int
    {
        return (int) $this->model->max('stt');
    }

    public function updateOrderByUuids(array $orderedUuids): void
    {
        foreach ($orderedUuids as $index => $uuid) {
            $this->model->where('uuid', $uuid)->update(['stt' => $index + 1]);
        }
    }
}
