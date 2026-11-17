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
        $data['image_vn'] = $this->saveImage($request, $current, 'image_vn');
        $data['image_en'] = $this->saveImage($request, $current, 'image_en');

        $this->partnerRepository->update($uuid, $data);
        toast('Cập nhật '.$this->nameItem.' thành công', 'success');

        PartnerChanged::dispatch($current, 'updated');

        return $request->has('return_back') 
            ? back() 
            : ($request->has('return_list') 
                ? redirect()->route('admin.'.$this->nameClass.'.index', ['page' => $request->currentPage]) 
                : null);
    }

    public function create()
    {
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;
        return $this->view_admin('detail', $data);
    }

    public function edit(string $uuid, Request $request)
    {
        $data['page'] = $this->partnerRepository->findByUuid($uuid);
        
        if (!$data['page']) {
            toast('Không tìm thấy '.$this->nameItem, 'error');
            return back();
        }

        $data['action'] = 'edit';
        $data['nameItem'] = $this->nameItem;
        $data['currentPage'] = $request->page ?? 1;

        return $this->view_admin('detail', $data);
    }

    public function destroy(string $uuid)
    {
        $partner = $this->partnerRepository->findByUuid($uuid);
        
        if ($partner) {
            $this->partnerRepository->delete($uuid);
            toast('Xóa '.$this->nameItem.' thành công', 'success');
            PartnerChanged::dispatch($partner, 'deleted');
        } else {
            toast('Không tìm thấy '.$this->nameItem, 'error');
        }

        return back();
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);
        
        if (!empty($uuids)) {
            $this->partnerRepository->deleteMultiple($uuids);
            toast('Xóa '.$this->nameItem.' thành công', 'success');
            PartnerChanged::dispatch(null, 'deleted');
        }

        return back();
    }

    public function status(string $uuid, int $status, string $field)
    {
        return $this->updateFieldStatus($this->partnerRepository, $uuid, $field, $status);
    }

    public function numericalOrder(Request $request, string $uuid)
    {
        $order = (int) $request->input('stt', 0);
        $updated = $this->partnerRepository->updateOrder($uuid, $order);

        if ($updated) {
            PartnerChanged::dispatch(null, 'reordered');
            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật số thứ tự thành công'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Cập nhật thất bại'
        ], 400);
    }
}
