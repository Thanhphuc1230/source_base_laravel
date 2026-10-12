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
                                        <a href="{{ route('admin.user-role.roles.index') }}"
                                            class="btn btn-primary d-inline-flex align-items-center justify-content-center px-3 rounded-2"
                                            style="height: 38px; font-weight: 500; font-size: 13px;">
                                            <i class="ri-settings-3-line fs-16 me-1"></i> Quản lý vai trò
                                        </a>
                                    </div>
                                    <div class="col-sm">
                                        <div class="d-flex justify-content-sm-end align-items-center">
                                            <form action="{{ route('admin.' . $nameClass . '.index') }}" method="get"
                                                class="d-flex align-items-center gap-2 m-0 p-0">
                                                <div class="admin-search-box" style="width: 250px; max-width: 250px;">
                                                    <input type="text" class="form-control admin-search-input" name="search"
                                                        value="{{ request('search') }}" placeholder="Tìm kiếm tên, email... (Enter)">
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
                                                <th class="text-center" style="width: 50px;">ID</th>
                                                <th class="text-start">Tên đầy đủ</th>
                                                <th class="text-start">Username</th>
                                                <th class="text-start">Email</th>
                                                <th class="text-center" style="width: 90px;">Level</th>
                                                <th class="text-start">Vai trò hiện tại</th>
                                                <th class="text-center" style="width: 100px;">Trạng thái</th>
                                                <th class="text-center" style="width: 110px;">Ngày tạo</th>
                                                <th class="text-center" style="width: 110px;">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @if (count($list) > 0)
                                                @foreach ($list as $item)
                                                    <tr>
                                                        <td class="text-center text-muted fw-semibold fs-12">{{ $item->id }}</td>
                                                        <td class="text-start fw-medium text-dark">{{ $item->fullname }}</td>
                                                        <td class="text-start text-muted fs-12">{{ $item->username }}</td>
                                                        <td class="text-start text-muted fs-12">{{ $item->email }}</td>
                                                        <td class="text-center">
                                                            <span class="category-badge">
                                                                Level {{ $item->level }}
                                                            </span>
                                                        </td>
                                                        <td class="text-start">
                                                            @if ($item->roles->count() > 0)
                                                                @foreach ($item->roles as $role)
                                                                    <span class="badge bg-primary-subtle text-primary me-1 px-2 py-1">
                                                                        {{ $role->display_name }}
                                                                    </span>
                                                                @endforeach
                                                            @else
                                                                <span class="category-badge-empty">
                                                                    Chưa có vai trò
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($item->status == 1)
                                                                <span class="badge bg-success-subtle text-success px-2 py-1 text-uppercase">Active</span>
                                                            @else
                                                                <span class="badge bg-danger-subtle text-danger px-2 py-1 text-uppercase">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center text-muted fs-12">
                                                            {{ $item->created_at ? $item->created_at->format('d-m-Y') : '--' }}
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="{{ route('admin.' . $nameClass . '.edit', ['id' => $item->id]) }}"
                                                                class="btn btn-sm btn-soft-primary d-inline-flex align-items-center gap-1 rounded-2 px-2 py-1 fs-12"
                                                                title="Phân quyền">
                                                                <i class="ri-user-settings-line"></i> Phân quyền
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="9" class="text-center py-4 text-muted">Chưa có dữ liệu</td>
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