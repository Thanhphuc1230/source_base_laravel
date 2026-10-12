@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Danh sách')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Quản lý vai trò</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.user-role.index') }}">Phân quyền người dùng</a></li>
                                <li class="breadcrumb-item active">Quản lý vai trò</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="flex-grow-1">
                                    <a href="{{ route('admin.user-role.roles.create') }}"
                                        class="btn btn-success d-inline-flex align-items-center justify-content-center px-3 rounded-2"
                                        style="height: 38px; font-weight: 500; font-size: 13px;">
                                        <i class="ri-add-line fs-16 me-1"></i> Tạo vai trò mới
                                    </a>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('admin.user-role.index') }}"
                                        class="btn btn-soft-secondary d-inline-flex align-items-center justify-content-center px-3 rounded-2"
                                        style="height: 38px; font-weight: 500; font-size: 13px;">
                                        <i class="ri-arrow-left-line fs-15 me-1"></i> Quay lại danh sách user
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive table-card mb-1">
                                <table class="table align-middle table-nowrap table-hover mb-0">
                                    <thead class="table-light text-muted text-uppercase fs-11" style="background-color: #f8fafc;">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">ID</th>
                                            <th class="text-start">Tên vai trò</th>
                                            <th class="text-start">Tên hiển thị</th>
                                            <th class="text-start">Mô tả</th>
                                            <th class="text-center" style="width: 110px;">Số quyền</th>
                                            <th class="text-center" style="width: 120px;">Số người dùng</th>
                                            <th class="text-center" style="width: 120px;">Ngày tạo</th>
                                            <th class="text-center" style="width: 100px;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        @forelse ($roles as $role)
                                            <tr>
                                                <td class="text-center text-muted fw-semibold fs-12">{{ $role->id }}</td>
                                                <td class="text-start">
                                                    <span class="category-badge">{{ $role->name }}</span>
                                                </td>
                                                <td class="text-start fw-bold text-dark">{{ $role->display_name }}</td>
                                                <td class="text-start text-muted fs-12">
                                                    @if ($role->description)
                                                        {{ Str::limit($role->description, 50) }}
                                                    @else
                                                        <span class="fst-italic text-secondary">Chưa có mô tả</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info-subtle text-info px-2 py-1">
                                                        {{ $role->permissions->count() }} quyền
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success-subtle text-success px-2 py-1">
                                                        {{ $role->users->count() }} người dùng
                                                    </span>
                                                </td>
                                                <td class="text-center text-muted fs-12">{{ $role->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                                        <a href="{{ route('admin.user-role.roles.edit', ['id' => $role->id]) }}" 
                                                           class="action-btn-ghost action-edit" title="Chỉnh sửa">
                                                           <i class="ri-pencil-line"></i>
                                                        </a>
                                                        @if ($role->users->count() == 0)
                                                            <form action="{{ route('admin.user-role.roles.destroy', ['id' => $role->id]) }}" 
                                                                  method="POST" 
                                                                  onsubmit="return confirm('Bạn có chắc muốn xóa vai trò này?')"
                                                                  class="m-0 p-0" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="action-btn-ghost action-delete border-0 bg-transparent" title="Xóa">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="action-btn-ghost text-muted opacity-50" title="Không thể xóa vai trò đang được sử dụng" style="cursor: not-allowed;">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    <i class="ri-folder-open-line display-6 d-block mb-1"></i>
                                                    Chưa có vai trò nào được tạo
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- container-fluid -->
    </div>
@endsection