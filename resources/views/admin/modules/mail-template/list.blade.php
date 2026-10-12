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
                                    @hasPermission('mail-template.create')
                                        <div class="col-sm-auto">
                                            <a href="{{ route('admin.mail-template.create') }}"
                                                class="btn btn-success d-inline-flex align-items-center justify-content-center px-3 rounded-2"
                                                style="height: 38px; font-weight: 500; font-size: 13px;">
                                                <i class="ri-add-line fs-16 me-1"></i> Thêm mới
                                            </a>
                                        </div>
                                    @endhasPermission
                                </div>

                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <div class="table-responsive table-card mt-3 mb-1">
                                    <table class="table align-middle table-nowrap table-hover" id="customerTable">
                                        <thead class="table-light text-muted text-uppercase fs-11" style="background-color: #f8fafc;">
                                            <tr>
                                                <th class="text-center" style="width: 50px;">ID</th>
                                                <th class="text-start">Tên template</th>
                                                <th class="text-start">Tiêu đề</th>
                                                <th class="text-center" style="width: 110px;">Loại</th>
                                                <th class="text-center" style="width: 120px;">Trạng thái</th>
                                                <th class="text-center" style="width: 120px;">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @if (count($templates) > 0)
                                                @foreach ($templates as $template)
                                                    <tr>
                                                        <td class="text-center text-muted fw-semibold fs-12">{{ $template->id }}</td>
                                                        <td class="text-start fw-medium text-dark">{{ $template->name }}</td>
                                                        <td class="text-start text-muted fs-12">{{ $template->subject }}</td>
                                                        <td class="text-center">
                                                            @if($template->type == 'order')
                                                                <span class="badge bg-primary-subtle text-primary px-2 py-1">Đơn hàng</span>
                                                            @elseif($template->type == 'contact')
                                                                <span class="badge bg-info-subtle text-info px-2 py-1">Liên hệ</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if($template->is_active)
                                                                <span class="badge bg-success-subtle text-success px-2 py-1">Hoạt động</span>
                                                            @else
                                                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1">Không hoạt động</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                                                @hasPermission('mail-template.edit')
                                                                    <a href="{{ route('admin.mail-template.edit', $template->id) }}"
                                                                        class="action-btn-ghost action-edit" title="Chỉnh sửa">
                                                                        <i class="ri-pencil-line"></i>
                                                                    </a>
                                                                @endhasPermission
                                                                @hasPermission('mail-template.set-active')
                                                                    <form action="{{ route('admin.mail-template.set-active', $template->id) }}"
                                                                          method="POST" style="display: inline;" class="m-0 p-0">
                                                                        @csrf
                                                                        <button type="submit" class="action-btn-ghost {{ $template->is_active ? 'action-delete' : 'action-view' }} border-0 bg-transparent"
                                                                                title="{{ $template->is_active ? 'Tắt kích hoạt' : 'Kích hoạt' }}">
                                                                            <i class="{{ $template->is_active ? 'ri-close-line' : 'ri-check-line' }}"></i>
                                                                        </button>
                                                                    </form>
                                                                @endhasPermission
                                                                @hasPermission('mail-template.delete')
                                                                    <form action="{{ route('admin.mail-template.delete', $template->id) }}"
                                                                          method="POST" style="display: inline;" class="m-0 p-0">
                                                                        @csrf
                                                                        <button type="submit" class="action-btn-ghost action-delete border-0 bg-transparent"
                                                                                onclick="return confirm('Xác nhận xóa {{ $nameItem }} ?')" title="Xóa">
                                                                            <i class="ri-delete-bin-line"></i>
                                                                        </button>
                                                                    </form>
                                                                @endhasPermission
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="6" class="text-center py-4 text-muted">Chưa có dữ liệu</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                @if(count($templates) > 0)
                                    <div class="d-flex justify-content-end">
                                        <div class="pagination-wrap hstack gap-2" style="display: flex;">
                                            {{ $templates->links() }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div><!-- end card -->
                    </div>
                </div>
            </div>
            <!-- container-fluid -->
        </div>
    @endsection