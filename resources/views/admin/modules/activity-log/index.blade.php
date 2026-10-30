@extends('admin.master')
@section('module', 'Nhật ký hoạt động')
@section('action', 'Danh sách')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="card-title mb-0">Nhật ký hoạt động hệ thống (Audit Log)</h4>
                            @hasPermission('activity_log.delete')
                                <button type="button" class="btn btn-outline-danger d-inline-flex align-items-center justify-content-center px-3 rounded-2"
                                    data-bs-toggle="modal" data-bs-target="#cleanLogsModal"
                                    style="height: 38px; font-weight: 500; font-size: 13px;">
                                    <i class="ri-delete-bin-6-line fs-15 me-1"></i> Dọn dẹp log cũ
                                </button>
                            @endhasPermission
                        </div>

                        <div class="card-body">
                            <!-- Filters form -->
                            <form action="{{ route('admin.activity_log.index') }}" method="GET" class="mb-4">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-3">
                                        <div class="admin-search-box w-100">
                                            <input type="text" class="form-control admin-search-input" name="search"
                                                value="{{ request('search') }}" placeholder="Tìm người dùng, nội dung... (Enter)">
                                            <i class="ri-search-line search-icon"></i>
                                            @if(request('search'))
                                                <a href="{{ route('admin.activity_log.index', request()->except('search')) }}"
                                                    class="clear-btn" title="Xóa tìm kiếm">
                                                    <i class="ri-close-circle-fill fs-14"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="log_name" class="form-select rounded-2" style="height: 38px; font-size: 13px;" onchange="this.form.submit()">
                                            <option value="">Tất cả phân hệ</option>
                                            @foreach($modules as $mod)
                                                <option value="{{ $mod }}" {{ request('log_name') == $mod ? 'selected' : '' }}>
                                                    {{ $mod }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="event" class="form-select rounded-2" style="height: 38px; font-size: 13px;" onchange="this.form.submit()">
                                            <option value="">Tất cả hành động</option>
                                            <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Thêm mới</option>
                                            <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Cập nhật</option>
                                            <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Xóa</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                                            class="form-control rounded-2" style="height: 38px; font-size: 13px;"
                                            placeholder="Từ ngày" onchange="this.form.submit()">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                                            class="form-control rounded-2" style="height: 38px; font-size: 13px;"
                                            placeholder="Đến ngày" onchange="this.form.submit()">
                                    </div>
                                    <div class="col-md-1">
                                        <a href="{{ route('admin.activity_log.index') }}" class="btn btn-light w-100 rounded-2 d-flex align-items-center justify-content-center"
                                            style="height: 38px;" title="Đặt lại bộ lọc">
                                            <i class="ri-refresh-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>

                            <!-- Table list -->
                            <div class="table-responsive table-card mb-3">
                                <table class="table align-middle table-hover">
                                    <thead class="table-light text-muted text-uppercase fs-11" style="background-color: #f8fafc;">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">STT</th>
                                            <th class="text-center" style="width: 150px;">Thời gian</th>
                                            <th class="text-start" style="width: 180px;">Người thực hiện</th>
                                            <th class="text-center" style="width: 110px;">Hành động</th>
                                            <th class="text-start" style="width: 160px;">Phân hệ / Đối tượng</th>
                                            <th class="text-start">Chi tiết mô tả</th>
                                            <th class="text-center" style="width: 90px;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @forelse($logs as $index => $item)
                                            <tr>
                                                <td class="text-center text-muted fs-12">
                                                    {{ $logs->firstItem() + $index }}
                                                </td>
                                                <td class="text-center text-muted fs-12">
                                                    {{ $item->created_at ? $item->created_at->format('d/m/Y H:i:s') : '-' }}
                                                </td>
                                                <td class="text-start">
                                                    @if($item->causer)
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-semibold"
                                                                style="width: 28px; height: 28px; font-size: 11px;">
                                                                {{ strtoupper(substr($item->causer->fullname ?? $item->causer->username ?? 'U', 0, 1)) }}
                                                            </div>
                                                            <div>
                                                                <div class="fw-medium text-dark fs-13">
                                                                    {{ $item->causer->fullname ?? $item->causer->username }}
                                                                </div>
                                                                <div class="text-muted fs-11">
                                                                    {{ $item->causer->email }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="badge bg-light text-muted border">Hệ thống</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($item->event === 'created')
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                                            <i class="ri-add-line align-middle me-1"></i>Thêm mới
                                                        </span>
                                                    @elseif($item->event === 'updated')
                                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">
                                                            <i class="ri-edit-line align-middle me-1"></i>Cập nhật
                                                        </span>
                                                    @elseif($item->event === 'deleted')
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">
                                                            <i class="ri-delete-bin-line align-middle me-1"></i>Xóa
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 fs-11">
                                                            {{ ucfirst($item->event ?? 'Khác') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-start">
                                                    <span class="badge bg-light text-dark border px-2 py-1 fs-12 fw-normal">
                                                        {{ $item->log_name ?? 'Default' }}
                                                    </span>
                                                    @if($item->subject_id)
                                                        <span class="text-muted fs-11 ms-1">#{{ $item->subject_id }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-start text-dark fs-13">
                                                    {{ $item->description }}
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-inline-flex gap-1">
                                                        <button type="button" class="btn btn-ghost-primary btn-sm rounded-1 p-0 view-diff-btn"
                                                            data-id="{{ $item->id }}"
                                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                                            title="Xem chi tiết thay đổi">
                                                            <i class="ri-eye-line fs-15"></i>
                                                        </button>
                                                        @hasPermission('activity_log.delete')
                                                            <form action="{{ route('admin.activity_log.destroy', $item->id) }}" method="POST"
                                                                class="d-inline-block delete-log-form"
                                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa bản ghi nhật ký này?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-ghost-danger btn-sm rounded-1 p-0"
                                                                    style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                                                    title="Xóa bản ghi này">
                                                                    <i class="ri-delete-bin-line fs-15"></i>
                                                                </button>
                                                            </form>
                                                        @endhasPermission
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">
                                                    <i class="ri-inbox-line fs-24 d-block mb-1"></i>
                                                    Không có bản ghi nhật ký nào phù hợp.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted fs-13">
                                    Hiển thị từ {{ $logs->firstItem() ?? 0 }} đến {{ $logs->lastItem() ?? 0 }} trong tổng số {{ $logs->total() }} bản ghi
                                </div>
                                <div>
                                    {{ $logs->appends(request()->query())->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Chi tiết thay đổi (Diff Modal) -->
    <div class="modal fade" id="logDetailModal" tabindex="-1" aria-labelledby="logDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logDetailModalLabel">
                        <i class="ri-history-line align-middle me-1"></i> Chi tiết thay đổi dữ liệu
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalDetailContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-2 px-3" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Dọn dẹp log cũ -->
    @hasPermission('activity_log.delete')
    <div class="modal fade" id="cleanLogsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.activity_log.clean') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">
                            <i class="ri-delete-bin-line align-middle me-1"></i> Dọn dẹp nhật ký hoạt động
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted fs-13 mb-3">
                            Thao tác này sẽ xóa vĩnh viễn các bản ghi nhật ký hoạt động cũ để giải phóng dung lượng cơ sở dữ liệu.
                        </p>
                        <div class="mb-3">
                            <label class="form-label fs-13 fw-medium">Chọn khoảng thời gian dọn dẹp:</label>
                            <select name="days" class="form-select rounded-2" style="height: 38px;">
                                <option value="30">Xóa các bản ghi cũ hơn 30 ngày</option>
                                <option value="60" selected>Xóa các bản ghi cũ hơn 60 ngày (Khuyến nghị)</option>
                                <option value="90">Xóa các bản ghi cũ hơn 90 ngày</option>
                                <option value="180">Xóa các bản ghi cũ hơn 180 ngày</option>
                                <option value="365">Xóa các bản ghi cũ hơn 1 năm</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light rounded-2 px-3" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger rounded-2 px-3">Xác nhận dọn dẹp</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endhasPermission
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const detailModal = new bootstrap.Modal(document.getElementById('logDetailModal'));
    const modalContent = document.getElementById('modalDetailContent');

    document.querySelectorAll('.view-diff-btn').forEach(button => {
        button.addEventListener('click', function () {
            const logId = this.getAttribute('data-id');
            modalContent.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                </div>
            `;
            detailModal.show();

            fetch(`{{ url('admin/activity-log') }}/${logId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    modalContent.innerHTML = `<div class="alert alert-danger">Không tìm thấy thông tin nhật ký.</div>`;
                    return;
                }

                const item = data.data;
                let diffHtml = '';

                if (item.diff && item.diff.length > 0) {
                    diffHtml = `
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-sm align-middle fs-12 mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 25%;">Trường thông tin</th>
                                        <th style="width: 37.5%;" class="text-danger">Giá trị trước thay đổi</th>
                                        <th style="width: 37.5%;" class="text-success">Giá trị sau thay đổi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${item.diff.map(row => `
                                        <tr>
                                            <td class="fw-medium text-dark">${row.label} <code class="text-muted fs-11">(${row.field})</code></td>
                                            <td class="bg-danger-subtle text-danger" style="word-break: break-all;">${row.old !== null ? row.old : '<span class="text-muted fst-italic">[Rỗng]</span>'}</td>
                                            <td class="bg-success-subtle text-success" style="word-break: break-all;">${row.new !== null ? row.new : '<span class="text-muted fst-italic">[Rỗng]</span>'}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    `;
                } else {
                    diffHtml = `
                        <div class="alert alert-light border text-muted fs-12 mt-3 mb-0">
                            Không có thông tin diff chi tiết hoặc chỉ ghi nhận trạng thái toàn cục.
                        </div>
                    `;
                }

                modalContent.innerHTML = `
                    <div class="row g-2 mb-3 fs-13">
                        <div class="col-sm-6">
                            <div class="text-muted">Người thực hiện:</div>
                            <div class="fw-medium text-dark">${item.causer} ${item.causer_email ? `(${item.causer_email})` : ''}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted">Thời gian:</div>
                            <div class="fw-medium text-dark">${item.created_at || '-'}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted">Phân hệ:</div>
                            <div class="fw-medium text-dark">${item.log_name}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted">Hành động:</div>
                            <div>${item.event_badge.label}</div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted">Mô tả:</div>
                            <div class="fw-medium text-dark">${item.description}</div>
                        </div>
                    </div>
                    <hr class="my-2 text-muted">
                    <h6 class="fs-13 fw-semibold text-dark mt-2 mb-1">Chi tiết các trường thay đổi:</h6>
                    ${diffHtml}
                `;
            })
            .catch(err => {
                modalContent.innerHTML = `<div class="alert alert-danger">Đã có lỗi xảy ra khi tải dữ liệu.</div>`;
            });
        });
    });
});
</script>
@endsection
