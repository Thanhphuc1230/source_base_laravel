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
                                        <a href="{{ route('admin.' . $nameClass . '.create') }}"
                                            class="btn btn-success d-inline-flex align-items-center justify-content-center px-3 rounded-2"
                                            style="height: 38px; font-weight: 500; font-size: 13px;">
                                            <i class="ri-add-line fs-16 me-1"></i> Thêm mới
                                        </a>
                                    </div>
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
                                                <th class="text-center" style="width: 40px;"><input type="checkbox" class="form-check-input" id="masterCheckbox"></th>
                                                <th class="text-center" style="width: 50px;">ID</th>
                                                <th class="text-start">Tên Font</th>
                                                <th class="text-start">Font Family</th>
                                                <th class="text-center" style="width: 100px;">Loại</th>
                                                <th class="text-center" style="width: 100px;">Trạng thái</th>
                                                <th class="text-center" style="width: 75px;">Thứ tự</th>
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
                                                            <td class="text-center"><input id="checkbox-data" class="form-check-input" type="checkbox"
                                                                    name="uuids[]" value="{{ $item->id }}"></td>
                                                            <td class="text-center text-muted fw-semibold fs-12">{{ $item->id }}</td>
                                                            <td class="text-start fw-medium text-dark">
                                                                <span style="font-family: {{ $item->family }};">
                                                                    {{ $item->name }}
                                                                </span>
                                                            </td>
                                                            <td class="text-start"><code>{{ $item->family }}</code></td>
                                                            <td class="text-center">
                                                                @if($item->type === 'system')
                                                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1">System</span>
                                                                @elseif($item->type === 'google')
                                                                    <span class="badge bg-info-subtle text-info px-2 py-1">Google</span>
                                                                @else
                                                                    <span class="badge bg-success-subtle text-success px-2 py-1">Upload</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="form-check form-switch d-inline-block m-0">
                                                                    <input class="form-check-input status-checkbox" type="checkbox" role="switch"
                                                                        value="{{ $item->is_active }}" data-uuid="{{ $item->id }}" data-field="is_active"
                                                                        data-status="{{ $item->is_active }}" {{ $item->is_active == 1 ? 'checked' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" class="form-control form-control-sm stt-input mx-auto"
                                                                    value="{{ $item->sort_order }}" data-uuid="{{ $item->id }}"
                                                                    onchange="updateStt(this)">
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                                                    <a href="{{ route('admin.' . $nameClass . '.edit', [$item->id, 'page' => $list->currentPage()]) }}"
                                                                        class="action-btn-ghost action-edit" title="Chỉnh sửa">
                                                                        <i class="ri-pencil-line"></i>
                                                                    </a>
                                                                    <a href="#" class="action-btn-ghost action-delete remove-item-btn"
                                                                        data-uuid="{{ $item->id }}" data-name="{{ $nameItem }}"
                                                                        data-bs-toggle="modal" data-bs-target="#deleteRecordModal" title="Xóa">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </form>
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
        @include('admin.ajax.status')
        @include('admin.ajax.delete-modal')
        @include('admin.ajax.update-stt')
    @endsection