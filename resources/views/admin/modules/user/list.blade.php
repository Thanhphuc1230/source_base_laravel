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
                                    @hasPermission('user.create')
                                    <div class="col-sm-auto">
                                        <a href="{{ route('admin.user.create') }}"
                                            class="btn btn-success d-inline-flex align-items-center justify-content-center px-3 rounded-2"
                                            style="height: 38px; font-weight: 500; font-size: 13px;">
                                            <i class="ri-add-line fs-16 me-1"></i> Thêm mới
                                        </a>
                                    </div>
                                    @endhasPermission
                                    @hasPermission('user.delete')
                                    <div class="col-sm-auto">
                                        <button type="button" id="deleteSelectedItems"
                                            class="btn btn-danger d-inline-flex align-items-center justify-content-center px-3 rounded-2"
                                            style="height: 38px; font-weight: 500; font-size: 13px;">
                                            <i class="ri-delete-bin-5-line fs-15 me-1"></i> Xóa đã chọn
                                        </button>
                                    </div>
                                    @endhasPermission
                                    <div class="col-sm">
                                        <div class="d-flex justify-content-sm-end align-items-center">
                                            <form action="{{ route('admin.user.index') }}" method="get"
                                                class="d-flex align-items-center gap-2 m-0 p-0 flex-wrap flex-sm-nowrap">
                                                <select class="form-select rounded-2" name="level" onchange="this.form.submit()"
                                                    style="width: 140px; max-width: 140px; height: 38px; font-size: 13px;">
                                                    <option value="">Cấp độ</option>
                                                    <option value="1" {{ request('level') == '1' ? 'selected' : '' }}>Admin</option>
                                                    <option value="2" {{ request('level') == '2' ? 'selected' : '' }}>Staff</option>
                                                    <option value="3" {{ request('level') == '3' ? 'selected' : '' }}>User</option>
                                                </select>
                                                <select class="form-select rounded-2" name="status" onchange="this.form.submit()"
                                                    style="width: 140px; max-width: 140px; height: 38px; font-size: 13px;">
                                                    <option value="">Trạng thái</option>
                                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Khóa</option>
                                                </select>
                                                <div class="admin-search-box">
                                                    <input type="text" class="form-control admin-search-input" name="search"
                                                        value="{{ request('search') }}" placeholder="Tìm kiếm... (Enter)">
                                                    <i class="ri-search-line search-icon"></i>
                                                    @if(request('search'))
                                                        <a href="{{ route('admin.user.index', array_filter(['level' => request('level'), 'status' => request('status')])) }}"
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
                                                <th class="text-center" style="width: 50px;">ID</th>
                                                <th class="text-start">Họ tên</th>
                                                <th class="text-start">Username</th>
                                                <th class="text-start">Email</th>
                                                <th class="text-start" style="width: 120px;">SĐT</th>
                                                <th class="text-center" style="width: 100px;">Cấp độ</th>
                                                <th class="text-center" style="width: 100px;">Trạng thái</th>
                                                <th class="text-center" style="width: 110px;">Ngày tạo</th>
                                                <th class="text-center" style="width: 100px;">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @if (count($list) > 0)
                                                <form id="delete-form-all"
                                                    action="{{ route('admin.user.bulk-delete') }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    @foreach ($list as $item)
                                                        <tr>
                                                            <td class="text-center"><input class="form-check-input" type="checkbox" name="uuids[]"
                                                                    value="{{ $item->uuid }}"></td>
                                                            <td class="text-center text-muted fw-semibold fs-12">{{ $item->id }}</td>
                                                            <td class="text-start fw-medium text-dark">{{ $item->fullname }}</td>
                                                            <td class="text-start text-muted fs-12">{{ $item->username }}</td>
                                                            <td class="text-start text-muted fs-12">{{ $item->email }}</td>
                                                            <td class="text-start text-muted fs-12">{{ $item->phone ?: '-' }}</td>
                                                            <td class="text-center">
                                                                @if($item->level == 1)
                                                                    <span class="badge bg-danger-subtle text-danger px-2 py-1">Admin</span>
                                                                @elseif($item->level == 2)
                                                                    <span class="badge bg-warning-subtle text-warning px-2 py-1">Staff</span>
                                                                @else
                                                                    <span class="badge bg-info-subtle text-info px-2 py-1">User</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if($item->status)
                                                                    <span class="badge bg-success-subtle text-success px-2 py-1">Hoạt động</span>
                                                                @else
                                                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1">Khóa</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center text-muted fs-12">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                                            <td class="text-center">
                                                                <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                                                    @hasPermission('user.edit')
                                                                    <a href="{{ route('admin.user.edit', $item->uuid) }}"
                                                                        class="action-btn-ghost action-edit" title="Chỉnh sửa">
                                                                        <i class="ri-pencil-line"></i>
                                                                    </a>
                                                                    @endhasPermission
                                                                    @hasPermission('user.delete')
                                                                    @if($item->level != 1 && $item->id != auth()->id())
                                                                    <button type="button" class="action-btn-ghost action-delete delete-item border-0 bg-transparent"
                                                                        data-uuid="{{ $item->uuid }}"
                                                                        data-name="{{ $item->fullname }}" title="Xóa">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </button>
                                                                    @endif
                                                                    @endhasPermission
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </form>
                                            @else
                                                <tr>
                                                    <td colspan="10" class="text-center py-4 text-muted">Không có dữ liệu</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                @if (count($list) > 0)
                                <div class="d-flex justify-content-end">
                                    <div class="pagination-wrap hstack gap-2" style="display: flex;">
                                        {{ $list->appends(request()->query())->links() }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div><!-- end row -->
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa người dùng <strong id="deleteUserName"></strong>?</p>
                    <p class="text-danger">Hành động này không thể hoàn tác!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Check all functionality
    $('#checkAll').on('change', function() {
        $('.form-check-input[name="uuids[]"]').prop('checked', $(this).prop('checked'));
    });

    // Delete selected items
    $('#deleteSelectedItems').on('click', function() {
        const selectedItems = $('.form-check-input[name="uuids[]"]:checked');
        if (selectedItems.length === 0) {
            alert('Vui lòng chọn ít nhất một người dùng để xóa!');
            return;
        }

        if (confirm('Bạn có chắc chắn muốn xóa các người dùng đã chọn?')) {
            $('#delete-form-all').submit();
        }
    });

    // Delete single item
    $('.delete-item').on('click', function() {
        const userUuid = $(this).data('uuid');
        const userName = $(this).data('name');

        $('#deleteUserName').text(userName);
        $('#deleteForm').attr('action', '{{ route("admin.user.destroy", ":uuid") }}'.replace(':uuid', userUuid));

        $('#deleteModal').modal('show');
    });
});
</script>
@endpush