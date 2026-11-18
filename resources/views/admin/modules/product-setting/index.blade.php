@extends('admin.master')
@php
    $isEn = session('locale') === 'en';
@endphp
@section('module', $title)
@section('action', $isEn ? 'Detail' : 'Chi tiết')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $title }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Hệ thống</a></li>
                                <li class="breadcrumb-item active">{{ $title }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            @include('admin.partials.error-banner')

            <form action="{{ route('admin.product-setting.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Column 1: Cấu hình chung -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold text-emerald-900">{{ $isEn ? '1. Display & Currency Settings' : '1. Cấu hình hiển thị & Tiền tệ' }}</h5>
                            </div>
                            <div class="card-body">
                                <!-- Currency Unit -->
                                <div class="mb-3 p-3 bg-light rounded-2 border">
                                    <label class="form-label font-weight-semibold text-primary" for="products_currency_unit">
                                        <i class="ri-money-dollar-circle-line me-1"></i> {{ $isEn ? 'System Currency Unit' : 'Đơn vị tiền tệ toàn hệ thống' }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" id="products_currency_unit" name="products_currency_unit"
                                            class="form-control fw-bold fs-14" value="{{ $settings['currency_unit'] ?? 'đ' }}"
                                            placeholder="{{ $isEn ? 'e.g. đ, VNĐ, $, USD' : 'ví dụ: đ, VNĐ, $, USD' }}" maxlength="20" required>
                                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="document.getElementById('products_currency_unit').value='đ'">đ</button>
                                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="document.getElementById('products_currency_unit').value='VNĐ'">VNĐ</button>
                                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="document.getElementById('products_currency_unit').value='$'">$</button>
                                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="document.getElementById('products_currency_unit').value='USD'">USD</button>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        {{ $isEn ? 'Change this currency unit to update all prices across web store and admin management.' : 'Thay đổi đơn vị này sẽ tự động cập nhật toàn bộ hiển thị giá cả ở ngoài Website và trang Quản trị Admin.' }}
                                    </small>
                                </div>

                                <!-- Pagination -->
                                <div class="mb-3">
                                    <label class="form-label font-weight-semibold" for="products_pagination">
                                        Số lượng sản phẩm trên một trang <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" id="products_pagination" name="products_pagination"
                                        class="form-control" value="{{ $settings['pagination'] }}" min="1" max="100" required>
                                    <small class="text-muted">Đặt số lượng sản phẩm được phân trang ngoài frontend.</small>
                                </div>

                                <!-- Show Intro Switch -->
                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-success py-2">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="products_show_intro" name="products_show_intro" value="1"
                                            {{ $settings['show_intro'] ? 'checked' : '' }}>
                                        <label class="form-check-label font-weight-semibold" for="products_show_intro">
                                            Hiển thị mô tả giới thiệu ngắn (Intro)
                                        </label>
                                    </div>
                                    <small class="text-muted d-block ms-5">Tích chọn để hiển thị phần mô tả ngắn gọn bên dưới tiêu đề sản phẩm.</small>
                                </div>

                                <!-- Click Image Detail Switch -->
                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-success py-2">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="products_click_image_detail" name="products_click_image_detail" value="1"
                                            {{ $settings['click_image_detail'] ? 'checked' : '' }}>
                                        <label class="form-check-label font-weight-semibold" for="products_click_image_detail">
                                            Click vào hình ảnh để xem chi tiết
                                        </label>
                                    </div>
                                    <small class="text-muted d-block ms-5">Nếu tắt, người dùng click vào ảnh sẽ không chuyển sang trang chi tiết sản phẩm.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2: Cấu hình giao diện, màu sắc -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold text-emerald-900">2. Cấu hình màu sắc & Kiểu chữ</h5>
                            </div>
                            <div class="card-body">
                                <!-- Font Size -->
                                <div class="mb-3">
                                    <label class="form-label font-weight-semibold" for="products_font_size">
                                        Kích thước tiêu đề (Font size)
                                    </label>
                                    <select class="form-control" id="products_font_size" name="products_font_size">
                                        @foreach(['12px', '14px', '16px', '18px', '20px', '22px'] as $size)
                                            <option value="{{ $size }}" {{ $settings['font_size'] == $size ? 'selected' : '' }}>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Cỡ chữ tiêu đề sản phẩm khi hiển thị trên danh sách.</small>
                                </div>

                                <!-- Color Pickers -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-semibold" for="products_title_color">
                                            Màu sắc tiêu đề sản phẩm
                                        </label>
                                        <div class="d-flex align-items-center">
                                            <input type="color" id="products_title_color" name="products_title_color"
                                                class="form-control form-control-color border-0 me-2" value="{{ $settings['title_color'] }}" style="width: 50px; height: 38px;">
                                            <span class="text-muted font-monospace">{{ strtoupper($settings['title_color']) }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-semibold" for="products_category_color">
                                            Màu sắc tên danh mục
                                        </label>
                                        <div class="d-flex align-items-center">
                                            <input type="color" id="products_category_color" name="products_category_color"
                                                class="form-control form-control-color border-0 me-2" value="{{ $settings['category_color'] }}" style="width: 50px; height: 38px;">
                                            <span class="text-muted font-monospace">{{ strtoupper($settings['category_color']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Column 3: Cấu hình Banner -->
                    <div class="col-12">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold text-emerald-900">3. Cấu hình Banner Sản Phẩm</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Banner Danh mục sản phẩm -->
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label font-weight-semibold" for="file_products_banner_category">
                                            Banner Danh mục sản phẩm (Khuyên dùng: 1920x400px)
                                        </label>
                                        <div class="d-flex align-items-center gap-1.5 mb-2">
                                            <button type="button" class="btn btn-sm btn-primary rounded-2 px-3 shadow-xs d-inline-flex align-items-center gap-1.5" style="height: 38px;" onclick="window.openMediaPicker({ mode: 'single', module: 'product-setting', onSelect: function(item) { document.getElementById('products_banner_category_selected').value = item.path; document.getElementById('preview_img_products_banner_category').src = item.url; document.getElementById('preview_box_products_banner_category').classList.remove('d-none'); } })">
                                                <i class="ri-folder-image-line fs-15"></i>
                                                <span class="fw-semibold">Chọn từ Thư viện</span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-2 px-3 bg-white d-inline-flex align-items-center gap-1.5" style="height: 38px;" onclick="document.getElementById('file_products_banner_category').click();">
                                                <i class="ri-upload-2-line fs-15"></i>
                                                <span>Tải từ máy tính</span>
                                            </button>
                                        </div>
                                        <input type="file" id="file_products_banner_category" name="products_banner_category" class="d-none" accept="image/*">
                                        <input type="hidden" id="products_banner_category_selected" name="products_banner_category_selected" value="">
                                        <div id="container_products_banner_category" class="mt-2"></div>

                                        <div id="preview_box_products_banner_category" class="mt-3 p-2 border rounded bg-light {{ empty($settings['banner_category']) ? 'd-none' : '' }}">
                                            <small class="text-muted d-block mb-1">Banner danh mục:</small>
                                            <img id="preview_img_products_banner_category" src="{{ !empty($settings['banner_category']) ? asset($settings['banner_category']) : '' }}" alt="Banner Danh mục sản phẩm" class="img-fluid rounded shadow-xs" style="max-height: 140px; object-fit: cover;">
                                            @if (!empty($settings['banner_category']))
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" name="delete_products_banner_category" value="1" id="delete_products_banner_category">
                                                    <label class="form-check-label text-danger font-weight-semibold" for="delete_products_banner_category">
                                                        Xóa banner danh mục hiện tại
                                                    </label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Banner Chi tiết sản phẩm -->
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label font-weight-semibold" for="file_products_banner_detail">
                                            Banner Chi tiết sản phẩm (Khuyên dùng: 1920x400px)
                                        </label>
                                        <div class="d-flex align-items-center gap-1.5 mb-2">
                                            <button type="button" class="btn btn-sm btn-primary rounded-2 px-3 shadow-xs d-inline-flex align-items-center gap-1.5" style="height: 38px;" onclick="window.openMediaPicker({ mode: 'single', module: 'product-setting', onSelect: function(item) { document.getElementById('products_banner_detail_selected').value = item.path; document.getElementById('preview_img_products_banner_detail').src = item.url; document.getElementById('preview_box_products_banner_detail').classList.remove('d-none'); } })">
                                                <i class="ri-folder-image-line fs-15"></i>
                                                <span class="fw-semibold">Chọn từ Thư viện</span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-2 px-3 bg-white d-inline-flex align-items-center gap-1.5" style="height: 38px;" onclick="document.getElementById('file_products_banner_detail').click();">
                                                <i class="ri-upload-2-line fs-15"></i>
                                                <span>Tải từ máy tính</span>
                                            </button>
                                        </div>
                                        <input type="file" id="file_products_banner_detail" name="products_banner_detail" class="d-none" accept="image/*">
                                        <input type="hidden" id="products_banner_detail_selected" name="products_banner_detail_selected" value="">
                                        <div id="container_products_banner_detail" class="mt-2"></div>

                                        <div id="preview_box_products_banner_detail" class="mt-3 p-2 border rounded bg-light {{ empty($settings['banner_detail']) ? 'd-none' : '' }}">
                                            <small class="text-muted d-block mb-1">Banner chi tiết:</small>
                                            <img id="preview_img_products_banner_detail" src="{{ !empty($settings['banner_detail']) ? asset($settings['banner_detail']) : '' }}" alt="Banner Chi tiết sản phẩm" class="img-fluid rounded shadow-xs" style="max-height: 140px; object-fit: cover;">
                                            @if (!empty($settings['banner_detail']))
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" name="delete_products_banner_detail" value="1" id="delete_products_banner_detail">
                                                    <label class="form-check-label text-danger font-weight-semibold" for="delete_products_banner_detail">
                                                        Xóa banner chi tiết hiện tại
                                                    </label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- Column 4: Cấu hình Cam kết & Chính sách sản phẩm -->
                    <div class="col-12">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3 d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0 font-weight-bold text-emerald-900">
                                    <i class="ri-shield-check-line me-1 text-primary"></i> 4. Cấu hình Cam kết & Chính sách sản phẩm (Bảo hành, Hỗ trợ, Đổi trả,...)
                                </h5>
                                <span class="badge bg-primary-subtle text-primary fs-12 px-2 py-1">Hiển thị dưới phần Intro trang Chi tiết Sản phẩm</span>
                            </div>
                            <div class="card-body">
                                <p class="text-muted fs-13 mb-3">
                                    Các cam kết này sẽ tự động xuất hiện tại trang chi tiết tất cả sản phẩm (ngay dưới phần Giới thiệu / Intro và hộp Đặt hàng) giúp khách hàng tin tưởng và chốt đơn nhanh chóng.
                                </p>

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <div class="form-check form-switch form-switch-success py-2">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="products_show_commitments" name="products_show_commitments" value="1"
                                                {{ !empty($settings['show_commitments']) ? 'checked' : '' }}>
                                            <label class="form-check-label font-weight-semibold" for="products_show_commitments">
                                                Hiển thị khối Cam kết
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label font-weight-semibold fs-13" for="products_commitments_title">
                                            Tiêu đề khối Cam kết <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control rounded-2" id="products_commitments_title" name="products_commitments_title"
                                            value="{{ $settings['commitments_title'] ?? 'Chính sách bán hàng & Cam kết' }}"
                                            placeholder="Ví dụ: Chính sách bán hàng & Cam kết dịch vụ" style="height: 38px;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label font-weight-semibold fs-13" for="products_commitments_subtitle">
                                            Mô tả phụ ngắn
                                        </label>
                                        <input type="text" class="form-control rounded-2" id="products_commitments_subtitle" name="products_commitments_subtitle"
                                            value="{{ $settings['commitments_subtitle'] ?? '' }}"
                                            placeholder="Ví dụ: Áp dụng cho tất cả sản phẩm chính hãng tại hệ thống" style="height: 38px;">
                                    </div>
                                </div>

                                <div class="border rounded-2 p-3 bg-light">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="font-weight-bold text-dark mb-0 fs-14">
                                            <i class="ri-list-check-2 me-1"></i> Danh sách các điều khoản Cam kết
                                        </h6>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-2" id="btnResetDefaultCommitments">
                                                <i class="ri-restart-line me-1"></i> Nạp cam kết mẫu
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary rounded-2" id="btnAddCommitmentRow">
                                                <i class="ri-add-line me-1"></i> Thêm cam kết mới
                                            </button>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover bg-white align-middle mb-0" id="commitmentsTable">
                                            <thead class="table-light text-muted fs-12">
                                                <tr>
                                                    <th class="text-center" style="width: 50px;">#</th>
                                                    <th style="width: 220px;">Icon (FontAwesome/Remix)</th>
                                                    <th style="width: 280px;">Tiêu đề cam kết <span class="text-danger">*</span></th>
                                                    <th>Mô tả chi tiết / Diễn giải</th>
                                                    <th class="text-center" style="width: 70px;">Xóa</th>
                                                </tr>
                                            </thead>
                                            <tbody id="commitmentsTableBody">
                                                @php
                                                    $commitmentsList = $settings['commitments'] ?? [];
                                                    if (is_string($commitmentsList)) {
                                                        $commitmentsList = json_decode($commitmentsList, true) ?? [];
                                                    }
                                                @endphp

                                                @if(!empty($commitmentsList) && is_array($commitmentsList))
                                                    @foreach($commitmentsList as $index => $cItem)
                                                        <tr class="commitment-row">
                                                            <td class="text-center fw-semibold text-muted row-number">{{ $index + 1 }}</td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <span class="input-group-text icon-preview bg-light text-primary">
                                                                        <i class="{{ !empty($cItem['icon']) ? $cItem['icon'] : 'fa-solid fa-circle-check' }}"></i>
                                                                    </span>
                                                                    <input type="text" class="form-control commitment-icon-input" name="commitment_icons[]"
                                                                        value="{{ $cItem['icon'] ?? 'fa-solid fa-circle-check' }}" placeholder="fa-solid fa-shield-halved">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm fw-bold" name="commitment_titles[]"
                                                                    value="{{ $cItem['title'] ?? '' }}" placeholder="Ví dụ: Bảo hành 1 đổi 1" required>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="commitment_descs[]"
                                                                    value="{{ $cItem['desc'] ?? '' }}" placeholder="Ví dụ: Đổi mới trong 30 ngày nếu có lỗi từ nhà sản xuất">
                                                            </td>
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-delete-commitment" title="Xóa dòng này">
                                                                    <i class="ri-delete-bin-line fs-14"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-text fs-11 text-muted mt-2">
                                        <i class="ri-information-line me-1"></i> Gợi ý icon phổ biến: <code>fa-solid fa-rotate</code> (Bảo hành 1-1), <code>fa-solid fa-headset</code> (Hỗ trợ 24/7), <code>fa-solid fa-shield-halved</code> (Bảo hành/Chính hãng), <code>fa-solid fa-truck-fast</code> (Giao hàng), <code>fa-solid fa-award</code> (Cam kết chất lượng), <code>fa-solid fa-box-open</code> (Đổi trả).
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success px-4 py-2 waves-effect waves-light shadow-sm" style="transition: all 0.3s ease;">
                            <i class="ri-save-line align-bottom me-1"></i> Lưu cấu hình
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof previewImage === 'function') {
            previewImage('file_products_banner_category', 'container_products_banner_category');
            previewImage('file_products_banner_detail', 'container_products_banner_detail');
        }

        // ===================================
        // PRODUCT COMMITMENTS REPEATER SCRIPT
        // ===================================
        const tbody = document.getElementById('commitmentsTableBody');
        const btnAdd = document.getElementById('btnAddCommitmentRow');
        const btnReset = document.getElementById('btnResetDefaultCommitments');

        function updateRowNumbers() {
            if (!tbody) return;
            const rows = tbody.querySelectorAll('.commitment-row');
            rows.forEach((row, index) => {
                const numCell = row.querySelector('.row-number');
                if (numCell) numCell.textContent = index + 1;
            });
        }

        function createRowHtml(icon = 'fa-solid fa-circle-check', title = '', desc = '') {
            const tr = document.createElement('tr');
            tr.className = 'commitment-row';
            tr.innerHTML = `
                <td class="text-center fw-semibold text-muted row-number"></td>
                <td>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text icon-preview bg-light text-primary">
                            <i class="${icon}"></i>
                        </span>
                        <input type="text" class="form-control commitment-icon-input" name="commitment_icons[]"
                            value="${icon}" placeholder="fa-solid fa-shield-halved">
                    </div>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm fw-bold" name="commitment_titles[]"
                        value="${title}" placeholder="Ví dụ: Bảo hành 1 đổi 1" required>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="commitment_descs[]"
                        value="${desc}" placeholder="Ví dụ: Đổi mới trong 30 ngày nếu có lỗi từ nhà sản xuất">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-delete-commitment" title="Xóa dòng này">
                        <i class="ri-delete-bin-line fs-14"></i>
                    </button>
                </td>
            `;
            return tr;
        }

        // Thêm dòng mới
        if (btnAdd && tbody) {
            btnAdd.addEventListener('click', function() {
                const newRow = createRowHtml('fa-solid fa-circle-check', '', '');
                tbody.appendChild(newRow);
                updateRowNumbers();
                newRow.querySelector('input[name="commitment_titles[]"]').focus();
            });
        }

        // Xóa dòng & Cập nhật icon preview
        if (tbody) {
            tbody.addEventListener('click', function(e) {
                const deleteBtn = e.target.closest('.btn-delete-commitment');
                if (deleteBtn) {
                    const row = deleteBtn.closest('.commitment-row');
                    if (row) {
                        row.remove();
                        updateRowNumbers();
                    }
                }
            });

            tbody.addEventListener('input', function(e) {
                if (e.target.classList.contains('commitment-icon-input')) {
                    const val = e.target.value.trim() || 'fa-solid fa-circle-check';
                    const iconPreview = e.target.closest('.input-group').querySelector('.icon-preview i');
                    if (iconPreview) {
                        iconPreview.className = val;
                    }
                }
            });
        }

        // Nạp cam kết mẫu
        if (btnReset && tbody) {
            btnReset.addEventListener('click', function() {
                if (confirm('Bạn có muốn nạp lại danh sách cam kết mẫu chuẩn (Bảo hành 1-1, Hỗ trợ 24/7, Cam kết chính hãng, Giao hàng toàn quốc)?')) {
                    tbody.innerHTML = '';
                    const sampleItems = [
                        { icon: 'fa-solid fa-rotate', title: 'Bảo hành 1 đổi 1', desc: 'Đổi mới trong 30 ngày nếu có lỗi từ nhà sản xuất' },
                        { icon: 'fa-solid fa-headset', title: 'Hỗ trợ kỹ thuật 24/7', desc: 'Tư vấn nhiệt tình, hỗ trợ kỹ thuật trọn đời sản phẩm' },
                        { icon: 'fa-solid fa-shield-halved', title: 'Cam kết chính hãng 100%', desc: 'Đền gấp 10 lần nếu phát hiện hàng không chính hãng' },
                        { icon: 'fa-solid fa-truck-fast', title: 'Giao hàng hỏa tốc', desc: 'Miễn phí giao hàng toàn quốc, đồng kiểm khi nhận hàng' },
                    ];
                    sampleItems.forEach(item => {
                        tbody.appendChild(createRowHtml(item.icon, item.title, item.desc));
                    });
                    updateRowNumbers();
                }
            });
        }

        updateRowNumbers();
    });
</script>
@endpush
