@extends('admin.master')
@section('module', 'Redirect & 404 Monitor')
@section('action', 'Danh sách')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <h4 class="card-title mb-0">Quản lý Chuyển hướng (301/302) & Theo dõi 404</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.redirect.create') }}" class="btn btn-primary btn-sm" style="height: 38px; line-height: 24px;">
                            <i class="fa fa-plus me-1"></i> Thêm chuyển hướng
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.redirect.index') }}" class="row g-2 mb-3">
                        <div class="col-md-5">
                            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" style="height: 38px;" placeholder="Tìm URL nguồn, URL đích hoặc ghi chú...">
                        </div>
                        <div class="col-md-3">
                            <select name="type" class="form-select" style="height: 38px;" onchange="this.form.submit()">
                                <option value="">Tất cả loại</option>
                                <option value="301" {{ ($filters['type'] ?? '') === '301' ? 'selected' : '' }}>301 (Vĩnh viễn)</option>
                                <option value="302" {{ ($filters['type'] ?? '') === '302' ? 'selected' : '' }}>302 (Tạm thời)</option>
                                <option value="404" {{ ($filters['type'] ?? '') === '404' ? 'selected' : '' }}>404 Monitor</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-secondary w-100" style="height: 38px;">Lọc</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>URL Nguồn</th>
                                    <th>URL Đích</th>
                                    <th class="text-center" style="width: 90px;">Mã</th>
                                    <th class="text-center" style="width: 90px;">Lượt</th>
                                    <th class="text-center" style="width: 110px;">Trạng thái</th>
                                    <th class="text-center" style="width: 120px;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($redirects as $item)
                                    <tr>
                                        <td><code>{{ $item->source_url }}</code></td>
                                        <td>
                                            @if($item->target_url)
                                                <code>{{ $item->target_url }}</code>
                                            @else
                                                <span class="text-muted fst-italic">Chưa cấu hình đích</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $item->status_code === 404 ? 'bg-danger' : ($item->status_code === 301 ? 'bg-primary' : 'bg-info') }}">
                                                {{ $item->status_code }}
                                            </span>
                                        </td>
                                        <td class="text-center fw-bold">{{ number_format($item->hits) }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $item->is_active ? 'Đang bật' : 'Tắt' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.redirect.edit', $item->uuid) }}" class="btn btn-sm btn-outline-primary" title="Sửa">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.redirect.destroy', $item->uuid) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xác nhận xoá?')" title="Xoá">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Chưa có dữ liệu chuyển hướng hoặc log 404.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $redirects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
