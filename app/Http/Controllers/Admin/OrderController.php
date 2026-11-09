<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class OrderController extends BaseController
{
    protected $module;

    protected $nameItem;

    protected $imageFolder;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepo;

    public function __construct(OrderRepositoryInterface $orderRepo, $imageFolder = 'order')
    {
        $this->module = 'order';
        $this->nameItem = 'Đơn Hàng';
        $this->imageFolder = $imageFolder;
        $this->orderRepo = $orderRepo;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
        ];

        $data['list'] = $this->orderRepo->getOrdersPaginated($filters, 10);
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('list', $data);
    }

    public function status($uuid, $status, $field)
    {
        $updated = $this->orderRepo->updateStatusByUuid($uuid, $status, $field);

        if (! $updated) {
            toast('Không tìm thấy '.$this->nameItem, 'error');

            return redirect()->back();
        }

        $mess = match ((int) $status) {
            1 => 'Chuyển trạng thái đơn hàng sang: Hoàn thành',
            2 => 'Chuyển trạng thái đơn hàng sang: Đã hủy',
            default => 'Chuyển trạng thái đơn hàng sang: Chờ xử lý',
        };
        toast($mess, 'success');

        return redirect()->back();
    }

    public function edit($uuid)
    {
        $data['user_order'] = $this->orderRepo->getOrderDetailByUuid($uuid);

        if (! $data['user_order']) {
            toast('Không tìm thấy '.$this->nameItem, 'error');

            return back();
        }

        $data['list'] = $this->orderRepo->getOrderProducts($data['user_order']->id_order_status);
        $data['action'] = 'edit';
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('detail', $data);
    }

    public function destroy_order($uuid)
    {
        $deleted = $this->orderRepo->deleteOrderByUuid($uuid);

        if ($deleted) {
            toast('Xóa '.$this->nameItem.' thành công', 'success');

            return back();
        }

        toast('Không tìm thấy '.$this->nameItem, 'error');

        return back();
    }
}
