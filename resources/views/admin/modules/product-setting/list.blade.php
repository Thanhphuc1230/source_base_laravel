@extends('admin.master')
@section('module', $title)
@section('action', 'Danh sách')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Quản lý {{ $title }}</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="listjs-table" id="customerList">
                                <div class="row g-3 mb-3 align-items-center">
                                    <div class="col-sm-auto">
                                        <a href="{{ route('admin.' . $module . '.create') }}"
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
                                            <form action="{{ route('admin.' . $module . '.index') }}" method="get"
                                                class="d-flex align-items-center gap-2 m-0 p-0 flex-wrap flex-sm-nowrap">
                                                <select class="form-select rounded-2" name="group" onchange="this.form.submit()"
                                                    style="width: 170px; max-width: 170px; height: 38px; font-size: 13px;">
                                                    <option value="">Tất cả nhóm</option>
                                                    <option value="display" {{ request('group') == 'display' ? 'selected' : '' }}>Display</option>
                                                    <option value="policy" {{ request('group') == 'policy' ? 'selected' : '' }}>Policy</option>
                                                    <option value="general" {{ request('group') == 'general' ? 'selected' : '' }}>General</option>
                                                    <option value="performance" {{ request('group') == 'performance' ? 'selected' : '' }}>Performance</option>
                                                </select>
                                                <div class="admin-search-box">
                                                    <input type="text" class="form-control admin-search-input" name="search"
                                                        value="{{ request('search') }}" placeholder="Tìm kiếm... (Enter)">
                                                    <i class="ri-search-line search-icon"></i>
                                                    @if(request('search'))
                                                        <a href="{{ route('admin.' . $module . '.index', array_filter(['group' => request('group')])) }}"
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
                                                <th class="text-start">Key</th>
                                                <th class="text-start">Value</th>
                                                <th class="text-center" style="width: 90px;">Type</th>
                                                <th class="text-center" style="width: 110px;">Group</th>
                                                <th class="text-center" style="width: 100px;">Trạng thái</th>
                                                <th class="text-center" style="width: 100px;">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @if (count($settings) > 0)
                                                @foreach($settings as $item)
                                                    <tr>
                                                        <td class="text-center">
                                                            <input class="form-check-input" type="checkbox" name="uuids[]"
                                                                value="{{ $item->uuid }}">
                                                        </td>
                                                        <td class="text-start">
                                                            <strong class="text-dark">{{ $item->key }}</strong>
                                                            <span class="text-muted fs-11 d-block mt-0.5">Order: {{ $item->sort_order }}</span>
                                                        </td>
                                                        <td class="text-start">
                                                            <div style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                                @if($item->type == 'html')
                                                                    <span class="badge bg-info-subtle text-info px-2 py-1">HTML Content</span>
                                                                @elseif($item->type == 'json')
                                                                    <span class="badge bg-info-subtle text-info px-2 py-1">JSON Data</span>
                                                                @elseif($item->type == 'boolean')
                                                                    @if($item->value == 'true')
                                                                        <span class="badge bg-success-subtle text-success px-2 py-1">Có / Bật</span>
                                                                    @else
                                                                        <span class="badge bg-danger-subtle text-danger px-2 py-1">Không / Tắt</span>
                                                                    @endif
                                                                @else
                                                                    <span class="text-muted fs-12">{{ Str::limit($item->value, 50) }}</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-primary-subtle text-primary px-2 py-1">{{ $item->type }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="category-badge">{{ $item->group }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="form-check form-switch d-inline-block m-0">
                                                                <input class="form-check-input status-checkbox"
                                                                    type="checkbox" role="switch"
                                                                    value="{{ $item->is_active }}"
                                                                    data-uuid="{{ $item->uuid }}" data-field="is_active"
                                                                    data-status="{{ $item->is_active }}"
                                                                    {{ $item->is_active == 1 ? 'checked' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                                                <a href="{{ route('admin.' . $module . '.edit', $item->uuid) }}"
                                                                    class="action-btn-ghost action-edit" title="Chỉnh sửa">
                                                                    <i class="ri-pencil-line"></i>
                                                                </a>
                                                                <a href="{{ route('admin.' . $module . '.destroy', $item->uuid) }}"
                                                                    class="action-btn-ghost action-delete"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa?')" title="Xóa">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">Không có dữ liệu</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <div class="pagination-wrap hstack gap-2" style="display: flex;">
                                        {{ $settings->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card -->
                    </div>
                </div>
            </div>
            <!-- container-fluid -->
        </div>
    </div>
    @include('admin.ajax.status')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete selected
    const deleteSelectedItemsBtn = document.getElementById('deleteSelectedItems');
    if (deleteSelectedItemsBtn) {
        deleteSelectedItemsBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('input[name="uuids[]"]:checked');
            if (checkedBoxes.length === 0) {
                alert('Vui lòng chọn ít nhất một setting để xóa!');
                return;
            }
            if (confirm(`Bạn có chắc chắn muốn xóa ${checkedBoxes.length} setting đã chọn?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.product-setting.destroyAll") }}';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                checkedBoxes.forEach(checkbox => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'uuids[]';
                    hiddenInput.value = checkbox.value;
                    form.appendChild(hiddenInput);
                });
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    
    // Check all
    const checkAllCheckbox = document.getElementById('checkAll');
    const childCheckboxes = document.querySelectorAll('input[name="uuids[]"]');
    if (checkAllCheckbox) {
        checkAllCheckbox.addEventListener('change', function() {
            childCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
});
</script>
@endpush
