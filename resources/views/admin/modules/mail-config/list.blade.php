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
                                    @hasPermission('mail-config.create')
                                        <div class="col-sm-auto">
                                            <a href="{{ route('admin.mail-config.create') }}"
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

                                <div class="mb-3">
                                    <div class="p-3 bg-light rounded-2 border">
                                        <div class="fw-semibold text-dark mb-1">Cấu hình mail đang hoạt động:</div>
                                        @if($activeConfig)
                                            <div class="text-muted fs-13">
                                                <strong>Mailer:</strong> <span class="text-primary">{{ $activeConfig['driver'] ?? 'N/A' }}</span> |
                                                <strong>Host:</strong> <span class="text-dark">{{ $activeConfig['host'] ?? 'N/A' }}</span> |
                                                <strong>From:</strong> <span class="text-dark">{{ $activeConfig['from']['address'] ?? 'N/A' }}</span>
                                            </div>
                                        @else
                                            <div class="text-warning fs-13">
                                                Đang sử dụng cấu hình .env (không có cấu hình DB nào đang hoạt động)
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="table-responsive table-card mt-3 mb-1">
                                    <table class="table align-middle table-nowrap table-hover" id="customerTable">
                                        <thead class="table-light text-muted text-uppercase fs-11" style="background-color: #f8fafc;">
                                            <tr>
                                                <th class="text-center" style="width: 50px;">ID</th>
                                                <th class="text-start">Mailer</th>
                                                <th class="text-start">Host</th>
                                                <th class="text-center" style="width: 80px;">Port</th>
                                                <th class="text-start">Email gửi</th>
                                                <th class="text-center" style="width: 120px;">Trạng thái</th>
                                                <th class="text-center" style="width: 100px;">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @if (count($configs) > 0)
                                                @foreach ($configs as $config)
                                                    <tr>
                                                        <td class="text-center text-muted fw-semibold fs-12">{{ $config->id }}</td>
                                                        <td class="text-start fw-medium text-dark">{{ $config->mailer }}</td>
                                                        <td class="text-start text-muted fs-12">{{ $config->host ?? 'N/A' }}</td>
                                                        <td class="text-center text-muted fs-12">{{ $config->port ?? 'N/A' }}</td>
                                                        <td class="text-start text-muted fs-12">{{ $config->from_address }}</td>
                                                        <td class="text-center">
                                                            @if($config->is_active)
                                                                <span class="badge bg-success-subtle text-success px-2 py-1">Hoạt động</span>
                                                            @else
                                                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1">Không hoạt động</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                                                @hasPermission('mail-config.edit')
                                                                    <a href="{{ route('admin.mail-config.edit', $config->id) }}"
                                                                        class="action-btn-ghost action-edit" title="Chỉnh sửa">
                                                                        <i class="ri-pencil-line"></i>
                                                                    </a>
                                                                @endhasPermission
                                                                @hasPermission('mail-config.delete')
                                                                    <form action="{{ route('admin.mail-config.delete', $config->id) }}"
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
                                                    <td colspan="7" class="text-center py-4 text-muted">Chưa có dữ liệu</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- end card -->
                    </div>
                </div>
            </div>
            <!-- container-fluid -->
        </div>
    @endsection