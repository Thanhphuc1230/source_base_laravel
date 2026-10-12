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
                                    @hasPermission('news.create')
                                    <div class="col-sm-auto">
                                        <a href="{{ route('admin.' . $nameClass . '.create') }}"
                                            class="btn btn-success d-inline-flex align-items-center justify-content-center px-3 rounded-2"
                                            style="height: 38px; font-weight: 500; font-size: 13px;">
                                            <i class="ri-add-line fs-16 me-1"></i> Thêm mới
                                        </a>
                                    </div>
                                    @endhasPermission
                                    @hasPermission('news.delete')
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
                                            <form action="{{ route('admin.' . $nameClass . '.index') }}" method="get"
                                                class="d-flex align-items-center gap-2 m-0 p-0 flex-wrap flex-sm-nowrap">
                                                <select class="form-select rounded-2" name="category" onchange="this.form.submit()"
                                                    style="width: 200px; max-width: 200px; height: 38px; font-size: 13px;">
                                                    <option value="0">Tất cả chủ đề</option>
                                                    @foreach ($category as $item)
                                                        <option value="{{ $item->id_category_new }}" {{ request('category') == $item->id_category_new ? 'selected' : '' }}>
                                                            {{ $item->name_vn }}
                                                        </option>
                                                        @if ($item->children)
                                                            @foreach ($item->children as $child)
                                                                <option value="{{ $child->id_category_new }}" {{ request('category') == $child->id_category_new ? 'selected' : '' }}>
                                                                    |---{{ $child->name_vn }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <div class="admin-search-box">
                                                    <input type="text" class="form-control admin-search-input" name="search"
                                                        value="{{ request('search') }}" placeholder="Tìm kiếm... (Enter)">
                                                    <i class="ri-search-line search-icon"></i>
                                                    @if(request('search'))
                                                        <a href="{{ route('admin.' . $nameClass . '.index', array_filter(['category' => request('category')])) }}"
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
                                                <th class="text-center" style="width: 45px;">STT</th>
                                                <th class="text-center" style="width: 65px;">Hình ảnh</th>
                                                <th class="text-start">Tiêu đề bài viết</th>
                                                <th class="text-start" style="width: 180px;">Chủ đề / Danh mục</th>
                                                <th class="text-center" style="width: 95px;">Trạng thái</th>
                                                <th class="text-center" style="width: 90px;">Trang chủ</th>
                                                <th class="text-center" style="width: 70px;">Thứ tự</th>
                                                <th class="text-center" style="width: 110px;">Ngày cập nhật</th>
                                                <th class="text-center" style="width: 125px;">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @if (count($list) > 0)
                                                <form id="delete-form-all"
                                                    action="{{ route('admin.' . $nameClass . '.destroyAll') }}"
                                                    method="POST">
                                                    @csrf
                                                    @foreach ($list as $item)
                                                        <tr>
                                                            <td class="text-center"><input class="form-check-input" type="checkbox" name="uuids[]"
                                                                    value="{{ $item->uuid }}"></td>
                                                            <td class="text-center text-muted fw-semibold fs-12">{{ $loop->iteration }}</td>
                                                            <td class="text-center">
                                                                <div class="rounded-2 border bg-light d-inline-flex align-items-center justify-content-center overflow-hidden shadow-2xs" style="width: 42px; height: 42px;">
                                                                    <img src="{{ $item->image }}" alt="{{ $item->name_vn }}" class="w-100 h-100 object-fit-cover">
                                                                </div>
                                                            </td>
                                                            <td class="text-start">
                                                                <div>
                                                                    <a href="{{ route('admin.' . $nameClass . '.edit', ['uuid' => $item->uuid, 'page' => $list->currentPage()]) }}" class="fw-bold text-dark fs-13 text-decoration-none hover-primary d-block leading-snug">
                                                                        {{ $item->name_vn }}
                                                                    </a>
                                                                    @if(!empty($item->name_en))
                                                                        <span class="text-muted fs-11 d-block mt-0.5">{{ $item->name_en }}</span>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td class="text-start">
                                                                @if ($item->cate)
                                                                    <span class="category-badge">
                                                                        {{ $item->cate->name_vn }}
                                                                    </span>
                                                                @else
                                                                    <span class="category-badge-empty">
                                                                        Chưa phân loại
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="form-check form-switch d-inline-block m-0">
                                                                    <input class="form-check-input status-checkbox"
                                                                        type="checkbox" role="switch"
                                                                        value="{{ $item->status }}"
                                                                        data-uuid="{{ $item->uuid }}" data-field="status"
                                                                        data-status="{{ $item->status }}"
                                                                        {{ $item->status == 1 ? 'checked' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="form-check form-switch d-inline-block m-0">
                                                                    <input class="form-check-input status-checkbox"
                                                                        type="checkbox" role="switch"
                                                                        value="{{ $item->home }}"
                                                                        data-uuid="{{ $item->uuid }}" data-field="home"
                                                                        data-status="{{ $item->home }}"
                                                                        {{ $item->home == 1 ? 'checked' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" class="form-control form-control-sm stt-input mx-auto"
                                                                    value="{{ $item->stt }}"
                                                                    data-uuid="{{ $item->uuid }}"
                                                                    onchange="updateStt(this)">
                                                            </td>
                                                            <td class="text-center text-muted fs-12">
                                                                {{ $item->created_at ? $item->created_at->format('d-m-Y') : '--' }}
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                                                    @if($item->slug)
                                                                        <a href="{{ route('web.resolve',['slug' => $item->slug]) }}"
                                                                            target="_blank"
                                                                            class="action-btn-ghost action-view"
                                                                            title="Xem chi tiết ngoài website">
                                                                            <i class="ri-eye-line"></i>
                                                                        </a>
                                                                    @endif
                                                                    @hasPermission('news.edit')
                                                                        <a href="{{ route('admin.' . $nameClass . '.edit', ['uuid' => $item->uuid, 'page' => $list->currentPage()]) }}"
                                                                            class="action-btn-ghost action-edit"
                                                                            title="Chỉnh sửa">
                                                                            <i class="ri-pencil-line"></i>
                                                                        </a>
                                                                    @endhasPermission
                                                                    @hasPermission('news.delete')
                                                                        <a href="{{ route('admin.' . $nameClass . '.destroy', ['uuid' => $item->uuid]) }}"
                                                                            class="action-btn-ghost action-delete"
                                                                            onclick="return confirm('Xác nhận xóa {{ $nameItem }} ?')"
                                                                            title="Xóa">
                                                                            <i class="ri-delete-bin-line"></i>
                                                                        </a>
                                                                    @endhasPermission
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
        @include('admin.ajax.status')
        @include('admin.ajax.update-stt')

        @push('styles')
        <style>
            /* Table general layout & Spacing */
            .table-card table th, .table-card table td {
                padding: 0.65rem 0.75rem;
                vertical-align: middle;
            }
            .table-hover tbody tr {
                transition: background-color 0.15s ease-in-out;
            }
            .table-hover tbody tr:hover {
                background-color: #f8fafc !important;
            }
            .hover-primary:hover {
                color: #2563eb !important;
            }

            /* Category Chip / Badge */
            .category-badge {
                background-color: #f1f5f9 !important;
                color: #334155 !important;
                border: 1px solid #e2e8f0 !important;
                font-size: 11.5px;
                font-weight: 500;
                padding: 3px 8px;
                border-radius: 4px;
                display: inline-block;
                max-width: 170px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                vertical-align: middle;
            }
            .category-badge-empty {
                background-color: #fafafa !important;
                color: #94a3b8 !important;
                border: 1px dashed #cbd5e1 !important;
                font-size: 11px;
                font-weight: 400;
                padding: 3px 7px;
                border-radius: 4px;
                display: inline-block;
            }

            /* Unified Status Switches (Single Modern Brand Accent Color) */
            .form-check.form-switch .form-check-input {
                width: 2.2em !important;
                height: 1.2em !important;
                background-color: #cbd5e1 !important;
                border-color: #cbd5e1 !important;
                cursor: pointer;
                transition: all 0.2s ease-in-out;
            }
            .form-check.form-switch .form-check-input:checked {
                background-color: #2563eb !important;
                border-color: #2563eb !important;
                box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2) !important;
            }

            /* Compact STT Reorder Input */
            .stt-input {
                width: 52px !important;
                height: 28px !important;
                font-size: 12px !important;
                font-weight: 500 !important;
                text-align: center !important;
                border: 1px solid #e2e8f0 !important;
                border-radius: 4px !important;
                padding: 2px 4px !important;
                background-color: #ffffff;
                transition: border-color 0.15s ease, box-shadow 0.15s ease;
            }
            .stt-input:focus {
                border-color: #2563eb !important;
                box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15) !important;
                outline: none !important;
            }
            .stt-input::-webkit-outer-spin-button,
            .stt-input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            .stt-input[type=number] {
                -moz-appearance: textfield;
            }

            /* Modern Colored Action Buttons */
            .action-btn-ghost {
                width: 32px;
                height: 32px;
                padding: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 6px !important;
                font-size: 15px;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                text-decoration: none;
                box-shadow: 0 1px 2px rgba(0,0,0,0.04);
            }
            /* Nút Xem: Xanh băng dịu với icon xanh biển */
            .action-btn-ghost.action-view {
                background-color: #e0f2fe !important;
                color: #0284c7 !important;
                border: 1px solid #bae6fd !important;
            }
            .action-btn-ghost.action-view:hover {
                background-color: #0284c7 !important;
                color: #ffffff !important;
                border-color: #0284c7 !important;
                transform: translateY(-1px);
                box-shadow: 0 3px 6px rgba(2, 132, 199, 0.25);
            }
            /* Nút Sửa: Tím hoàng gia sang trọng */
            .action-btn-ghost.action-edit {
                background-color: #e0e7ff !important;
                color: #4338ca !important;
                border: 1px solid #c7d2fe !important;
            }
            .action-btn-ghost.action-edit:hover {
                background-color: #4338ca !important;
                color: #ffffff !important;
                border-color: #4338ca !important;
                transform: translateY(-1px);
                box-shadow: 0 3px 6px rgba(67, 56, 202, 0.25);
            }
            /* Nút Xóa: Đỏ san hô */
            .action-btn-ghost.action-delete {
                background-color: #fee2e2 !important;
                color: #ef4444 !important;
                border: 1px solid #fecaca !important;
            }
            .action-btn-ghost.action-delete:hover {
                background-color: #ef4444 !important;
                color: #ffffff !important;
                border-color: #ef4444 !important;
                transform: translateY(-1px);
                box-shadow: 0 3px 6px rgba(239, 68, 68, 0.25);
            }
        </style>
        @endpush

        @push('scripts')
        <script>
            // Debounce auto-search after typing
            let searchTimer = null;
            const searchInput = document.querySelector('.admin-search-input');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        this.form.submit();
                    }, 450);
                });
            }
        </script>
        @endpush
    @endsection
