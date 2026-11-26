<?php

namespace App\Http\Controllers\Admin;

use App\Events\Partner\PartnerChanged;
use App\Http\Requests\Admin\PartnerRequest;
use App\Models\Partner;
use App\Repositories\Interfaces\PartnerRepositoryInterface;
use App\Traits\Admin\CrudOperationsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PartnerController extends BaseController
{
    use CrudOperationsTrait;

    protected $module;
    protected $model;
    protected $nameItem;
    protected $imageFolder;
    protected $partnerRepository;
    protected $repository; // For trait
    protected $eventClass = PartnerChanged::class; // For trait

    public function __construct(PartnerRepositoryInterface $partnerRepository, $imageFolder = 'partner')
    {
        $this->module = 'partner';
        $this->model = new Partner;
        $this->nameItem = 'Đối tác';
        $this->imageFolder = $imageFolder;
        $this->partnerRepository = $partnerRepository;
        $this->repository = $partnerRepository; // For trait

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'sort_field' => 'stt',
            'sort_direction' => 'asc'
        ];

        $data['list'] = $this->partnerRepository->getFilteredPartners($filters);
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('list', $data);
    }

    public function store(PartnerRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        
        // Handle multilingual images
        $data['image_vn'] = $this->saveImage($request, null, 'image_vn');
        $data['image_en'] = $this->saveImage($request, null, 'image_en');

        $partner = $this->partnerRepository->create($data);
        toast('Thêm '.$this->nameItem.' thành công', 'success');

        PartnerChanged::dispatch($partner, 'created');

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function update(PartnerRequest $request, string $uuid)
    {
        $current = $this->partnerRepository->findByUuid($uuid);
        
        if (!$current) {
            toast('Không tìm thấy '.$this->nameItem, 'error');
            return back();
        }

        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');
        
        // Handle multilingual images
        $data['image_vn'] = $this->updateImage($request, $current, null, 'image_vn');
        $data['image_en'] = $this->updateImage($request, $current, null, 'image_en');

        $this->partnerRepository->update($data, $uuid);
        toast('Cập nhật '.$this->nameItem.' thành công', 'success');

        PartnerChanged::dispatch($current, 'updated');

        return $request->has('return_back')
            ? back()
            : $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function edit($uuid, $currentPage = 1)
    {
        return $this->performEdit($uuid, $currentPage);
    }

    public function destroy(string $uuid)
    {
        $partner = $this->partnerRepository->findByUuid($uuid);
        $result = $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);
        PartnerChanged::dispatch($partner, 'deleted');
        return $result;
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);
        $result = $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);
        PartnerChanged::dispatch(null, 'deleted');
        return $result;
    }

    public function status($uuid, $status, $field)
    {
        $partner = $this->partnerRepository->findByUuid($uuid);
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $field, $this->model::class);
        PartnerChanged::dispatch($partner, 'status_updated');
        return $result;
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $partner = $this->partnerRepository->findByUuid($uuid);
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);
        PartnerChanged::dispatch($partner, 'order_updated');
        return $result;
    }
}
