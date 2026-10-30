@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Danh sách')
@section('content')
    @if(session('debug'))
        <div class="alert alert-info">
            {{ session('debug') }}
        </div>
    @endif
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Quản lý {{ $nameItem }}</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="listjs-table" id="customerList">
                                <div class="row g-3 mb-3 align-items-center">
                                    <div class="col-sm">
                                        <div class="d-flex justify-content-sm-end align-items-center">
                                            <form action="{{ route('admin.' . $nameClass . '.index') }}" method="get"
                                                class="d-flex align-items-center gap-2 m-0 p-0">
                                                <select name="status" class="form-select rounded-2" style="height: 38px; font-size: 13px; width: 140px;" onchange="this.form.submit()">
                                                    <option value="">Tất cả trạng thái</option>
                                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Chờ xử lý</option>
                                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đã hoàn thành</option>
                                                    <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Đã hủy</option>
                                                </select>
                                                <div class="admin-search-box">
                                                    <input type="text" class="form-control admin-search-input" name="search"
                                                        value="{{ request('search') }}" placeholder="Tìm kiếm... (Enter)">
                                                    <i class="ri-search-line search-icon"></i>
                                                    @if(request('search'))
                                                        <a href="{{ route('admin.' . $nameClass . '.index') }}"
                                                           class="clear-btn" title="Xóa tìm kiếm">
                                                            <i class="ri-close-circle-fill fs-14"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive table-card mt-3 mb-1">
                                    <table class="table align-middle table-nowrap table-hover" id="customerTable">
                                        <thead class="table-light text-muted text-uppercase fs-11" style="background-color: #f8fafc;">
                                            <tr>
                                                <th class="text-center" style="width: 50px;">STT</th>
                                                <th class="text-start">Email khách hàng</th>
                                                <th class="text-start">Số điện thoại</th>
                                                <th class="text-center" style="width: 120px;">Thanh toán</th>
                                                <th class="text-end" style="width: 130px;">Tổng đơn</th>
                                                <th class="text-center" style="width: 120px;">Tình trạng</th>
                                                <th class="text-center" style="width: 110px;">Ngày tạo</th>
                                                <th class="text-center" style="width: 100px;">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @if (count($list) > 0)
                                                @foreach ($list as $item)
                                                    <tr>
                                                        <td class="text-center text-muted fw-semibold fs-12">{{ $loop->iteration }}</td>
                                                        <td class="text-start fw-medium text-dark">{{ $item->email }}</td>
                                                        <td class="text-start text-muted fs-12">{{ $item->phone }}</td>
                                                        <td class="text-center">
                                                            <span class="category-badge">{{ $item->payment_method == 'cod' ? 'Tiền mặt' : 'Chuyển khoản' }}</span>
                                                        </td>
                                                        @php
                                                            if ($item->total == 0) {
                                                                $totalPrice = 'Liên hệ';
                                                            } else {
                                                                $totalPrice = number_format($item->total) .' đ';
                                                            }
                                                        @endphp
                                                        <td class="text-end fw-bold text-dark">{{ $totalPrice }}</td>
                                                        <td class="text-center">
                                                            <div class="dropdown d-inline-block">
                                                                <button class="btn btn-sm dropdown-toggle py-1 px-2 fs-11 rounded-2 border-0
                                                                    {{ $item->status == 1 ? 'bg-success-subtle text-success' : ($item->status == 2 ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning') }}"
                                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    @if ($item->status == 1)
                                                                        <i class="ri-checkbox-circle-line me-1"></i>Hoàn thành
                                                                    @elseif ($item->status == 2)
                                                                        <i class="ri-close-circle-line me-1"></i>Đã hủy
                                                                    @else
                                                                        <i class="ri-time-line me-1"></i>Chờ xử lý
                                                                    @endif
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm fs-12 py-1">
                                                                    <li>
                                                                        <a class="dropdown-item text-warning py-1" href="{{ route('admin.' . $nameClass . '.status', ['uuid' => $item->uuid_order_status, 'status' => 0, 'field' => 'status']) }}">
                                                                            <i class="ri-time-line me-1"></i> Chờ xử lý
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item text-success py-1" href="{{ route('admin.' . $nameClass . '.status', ['uuid' => $item->uuid_order_status, 'status' => 1, 'field' => 'status']) }}">
                                                                            <i class="ri-checkbox-circle-line me-1"></i> Hoàn thành
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item text-danger py-1" href="{{ route('admin.' . $nameClass . '.status', ['uuid' => $item->uuid_order_status, 'status' => 2, 'field' => 'status']) }}">
                                                                            <i class="ri-close-circle-line me-1"></i> Đã hủy
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                        <td class="text-center text-muted fs-12">
                                                            {{ $item->created_at ? date('d/m/Y', strtotime($item->created_at)) : '--' }}
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                                                <a href="{{ route('admin.' . $nameClass . '.edit', ['uuid' => $item->uuid_order_status]) }}"
                                                                    class="action-btn-ghost action-edit" title="Chỉnh sửa đơn hàng">
                                                                    <i class="ri-pencil-line"></i>
                                                                </a>
                                                                <a href="{{ route('admin.' . $nameClass . '.destroy_order', ['uuid' => $item->uuid_order_status]) }}"
                                                                    class="action-btn-ghost action-delete"
                                                                    onclick="return confirm('Xác nhận xóa {{ $nameItem }} ?')" title="Xóa">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="8" class="text-center py-4 text-muted">Chưa có dữ liệu</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <div class="pagination-wrap hstack gap-2" style="display: flex;">
                                        {!! $list->appends(request()->except('page'))->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card -->
                    </div>
                </div>
            </div>
            <!-- container-fluid -->
        </div>
    @endsection
