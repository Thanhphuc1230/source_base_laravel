@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Danh sách')
@section('content')
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
                                    <div class="col-sm-auto">
                                        <button type="button" id="deleteSelectedItems"
                                            class="btn btn-danger d-inline-flex align-items-center justify-content-center px-3 rounded-2"
                                            style="height: 38px; font-weight: 500; font-size: 13px;">
                                            <i class="ri-delete-bin-5-line fs-15 me-1"></i> Xóa đã chọn
                                        </button>
                                    </div>
                                    <div class="col-sm">
                                        <div class="d-flex justify-content-sm-end align-items-center">
                                            <form action="{{ route('admin.' . $nameClass . '.index') }}" method="get"
                                                class="d-flex align-items-center gap-2 m-0 p-0">
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
                                                <th class="text-center" style="width: 40px;"><input type="checkbox" class="form-check-input" id="checkAll"></th>
                                                <th class="text-center" style="width: 50px;">STT</th>
                                                <th class="text-start">Họ và tên</th>
                                                <th class="text-start">Email</th>
                                                <th class="text-start">Số điện thoại</th>
                                                <th class="text-start">Tiêu đề</th>
                                                <th class="text-start">Nội dung</th>
                                                <th class="text-center" style="width: 100px;">Trạng thái</th>
                                                <th class="text-center" style="width: 110px;">Ngày gửi</th>
                                                <th class="text-center" style="width: 100px;">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @if (count($list) > 0)
                                                <form id="delete-form-all" action="{{ route('admin.' . $nameClass . '.destroyAll') }}"
                                                    method="POST">
                                                    @csrf
                                                    @foreach ($list as $item)
                                                        <tr>
                                                            <td class="text-center"><input class="form-check-input" type="checkbox"
                                                                    name="uuids[]" value="{{ $item->uuid }}"></td>
                                                            <td class="text-center text-muted fw-semibold fs-12">{{ $loop->iteration }}</td>
                                                            <td class="text-start fw-medium text-dark">{{ $item->fullname }}</td>
                                                            <td class="text-start text-muted fs-12">{{ $item->email }}</td>
                                                            <td class="text-start text-muted fs-12">{{ $item->phone }}</td>
                                                            <td class="text-start">{{ $item->subject }}</td>
                                                            <td class="text-start text-muted fs-12">{{ Str::limit($item->message, 40) }}</td>
                                                            <td class="text-center">
                                                                @if ($item->status == 1)
                                                                    <a href="{{ route('admin.' . $nameClass . '.status', ['uuid' => $item->uuid, 'status' => 0, 'name' => 'status']) }}"
                                                                        onclick="return confirm('Xác nhận đánh dấu chưa xử lý?')"
                                                                        class="badge bg-success-subtle text-success px-2 py-1 text-uppercase text-decoration-none">Đã xử lý</a>
                                                                @else
                                                                    <a href="{{ route('admin.' . $nameClass . '.status', ['uuid' => $item->uuid, 'status' => 1, 'name' => 'status']) }}"
                                                                        onclick="return confirm('Xác nhận đánh dấu đã xử lý?')"
                                                                        class="badge bg-warning-subtle text-warning px-2 py-1 text-uppercase text-decoration-none">Chờ xử lý</a>
                                                                @endif
                                                            </td>
                                                            <td class="text-center text-muted fs-12">
                                                                {{ $item->created_at ? $item->created_at->format('d-m-Y H:i') : '--' }}
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                                                    <a href="{{ route('admin.' . $nameClass . '.edit', ['uuid' => $item->uuid]) }}"
                                                                        class="action-btn-ghost action-edit" title="Xem chi tiết">
                                                                        <i class="ri-eye-line"></i>
                                                                    </a>
                                                                    <a href="{{ route('admin.' . $nameClass . '.destroy', ['uuid' => $item->uuid]) }}"
                                                                        class="action-btn-ghost action-delete"
                                                                        onclick="return confirm('Xác nhận xóa {{ $nameItem }} ?')" title="Xóa">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </form>
                                            @else
                                                <tr>
                                                    <td colspan="10" class="text-center py-4 text-muted">Chưa có dữ liệu</td>
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
