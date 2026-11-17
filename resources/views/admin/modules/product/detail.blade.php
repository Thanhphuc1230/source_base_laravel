@extends('admin.master')
@section('module', $nameItem)
@section('action', $action == 'create' ? 'Thêm' : 'Chỉnh sửa')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }}
                            {{ $nameItem }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">Sản phẩm</a></li>
                                <li class="breadcrumb-item active">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }} sản phẩm
                                </li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            @include('admin.partials.error-banner')

            <form
                action="{{ route('admin.' . ($nameClass ?? $imageFolder ?? 'product') . ($action == 'create' ? '.store' : '.update'), ['uuid' => $page->uuid ?? '']) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                @include('admin.partials.localized-fields', [
                                    'fields' => [
                                        ['base' => 'name', 'label' => 'Tiêu đề', 'col' => 'col-md-12'],
                                        ['base' => 'intro', 'label' => 'Giới thiệu', 'col' => 'col-md-12', 'rows' => 4, 'type' => 'textarea'],
                                        ['base' => 'content', 'label' => 'Nội dung', 'col' => 'col-md-12', 'rows' => 6, 'type' => 'textarea', 'ckeditor' => true],
                                    ],
                                    'model' => $page ?? null,
                                ])
                            </div>
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#addproduct-general-info"
                                            role="tab" aria-selected="true">
                                            Thông tin tổng quan
                                            @if($errors->has('price') || $errors->has('price_old'))
                                                <span class="badge bg-danger ms-1">Lỗi</span>
                                            @endif
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link text-primary fw-bold" data-bs-toggle="tab" href="#addproduct-attributes"
                                            role="tab" aria-selected="false">
                                            <i class="ri-equalizer-line me-1"></i> Thuộc tính & Phân loại (Kích thước, Màu sắc)
                                            @if($errors->has('product_attributes') || $errors->has('product_variants'))
                                                <span class="badge bg-danger ms-1">Lỗi</span>
                                            @endif
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- end card header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="addproduct-general-info" role="tabpanel">
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label font-weight-semibold" for="product-price-input">Giá tiền (nhập 0 sẽ hiện liên hệ)</label>
                                                    <div class="input-group has-validation mb-1">
                                                        <span class="input-group-text" id="product-price-addon">{{ currency_unit() }}</span>
                                                        <input type="number"
                                                            class="form-control @error('price') is-invalid @enderror"
                                                            placeholder="Nhập giá sản phẩm" name="price"
                                                            value="{{ old('price', $page->price ?? '') }}">
                                                    </div>
                                                    @error('price')
                                                        <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label font-weight-semibold" for="product-discount-input">Giá cũ</label>
                                                    <div class="input-group mb-1">
                                                        <span class="input-group-text" id="product-price-addon">{{ currency_unit() }}</span>
                                                        <input type="text" class="form-control @error('price_old') is-invalid @enderror"
                                                            placeholder="Nhập giá cũ" name="price_old"
                                                            value="{{ old('price_old', $page->price_old ?? '') }}">
                                                    </div>
                                                    @error('price_old')
                                                        <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- end col -->
                                        </div>
                                        <!-- end row -->
                                    </div>
                                    <!-- end tab-pane -->

                                    <div class="tab-pane" id="addproduct-attributes" role="tabpanel">
                                        @include('admin.modules.product.partials.attributes-tab')
                                    </div>
                                    <!-- end tab-pane -->
                                </div>
                                <!-- end tab content -->
                            </div>
                            <!-- end card body -->
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Hình ảnh sản phẩm</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($adminLanguages as $locale)
                                        @include('admin.partials.image-upload', [
                                            'locale' => $locale,
                                            'imageFolder' => $imageFolder,
                                            'model' => $page ?? null,
                                            'dimensions' => '800x800 px (Tỉ lệ 1:1)',
                                        ])
                                    @endforeach
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="mb-3">
                                        <label for="compnayNameinput" class="form-label fw-bold">Bộ sưu tập Media chi tiết (Hình ảnh & Video clip xen kẽ) <span class="text-muted fw-normal">(Khuyến nghị: 800x800 px - 1200x1200 px tỉ lệ 1:1)</span></label>
                                        @error('image_detail')
                                            <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                        @enderror
                                        @include('admin.partials.multiImage')

                                        @php
                                            $detailMediaList = [];
                                            if (!empty($page->image_detail)) {
                                                if (is_array($page->image_detail)) {
                                                    $detailMediaList = $page->image_detail;
                                                } elseif (is_string($page->image_detail)) {
                                                    $decoded = json_decode($page->image_detail, true);
                                                    $detailMediaList = is_array($decoded) ? $decoded : [];
                                                }
                                            }
                                        @endphp

                                        @if (!empty($detailMediaList))
                                            <div class="mt-3">
                                                <label class="form-label fw-semibold text-dark">Danh sách Media hiện tại:</label>

                                                <div class="row g-2" id="existing-images-container">
                                                    @foreach ($detailMediaList as $index => $img_detail)
                                                        @php
                                                            $isVideo = preg_match('/\.(mp4|webm|mov|ogg)$/i', $img_detail);
                                                            $mediaSrc = str_starts_with($img_detail, 'uploads/') ? asset($img_detail) : asset('uploads/' . $imageFolder . '/' . $img_detail);
                                                        @endphp
                                                        <div class="col-6 col-sm-4 col-md-3 existing-image-item" data-image="{{ $img_detail }}">
                                                            <div class="position-relative border p-1 bg-white shadow-2xs text-center" style="border-radius: 4px;">
                                                                @if($isVideo)
                                                                    <video src="{{ $mediaSrc }}#t=0.5"
                                                                        class="rounded-1"
                                                                        style="width: 100%; height: 110px; object-fit: cover; background: #000;"
                                                                        preload="metadata"
                                                                        muted playsinline></video>
                                                                    <span class="badge bg-danger text-white position-absolute" style="bottom: 8px; left: 8px; font-size: 9px; padding: 2px 5px; border-radius: 2px;">
                                                                        <i class="ri-video-fill me-0.5"></i> Video
                                                                    </span>
                                                                @else
                                                                    <img src="{{ $mediaSrc }}"
                                                                        alt="Product detail"
                                                                        style="width: 100%; height: 110px; object-fit: contain;"
                                                                        class="rounded-1">
                                                                @endif
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm remove-existing-image"
                                                                    title="Xóa mục này"
                                                                    style="position: absolute; top: 5px; right: 5px; width: 22px; height: 22px; padding: 0; line-height: 22px; font-size: 13px; border-radius: 3px;">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                
                                                <!-- Hidden input to track retained images -->
                                                <input type="hidden" name="kept_images" id="kept-images-input" 
                                                       value="{{ json_encode(array_values($detailMediaList)) }}">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end card -->
                        <div class="text-end mb-3">
                            @if ($action == 'create')
                                <input type="submit" name="return_back" class="btn btn-primary" value="Lưu và tạo mới">
                                <input type="submit" name="return_list" class="btn btn-primary"
                                    value="Lưu và về danh sách">
                            @else
                                <input type="submit" name="return_list" class="btn btn-primary" value="Cập nhật">
                            @endif
                        </div>
                    </div>
                    <!-- end col -->

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Hiển thị</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="choices-publish-status-input" class="form-label font-weight-semibold">Trạng thái</label>

                                    <select class="form-select @error('status') is-invalid @enderror" name="status">
                                        <option value="1"
                                            {{ old('status', $page->status ?? 1) == 1 ? 'selected' : '' }}>Bật
                                        </option>
                                        <option value="0"
                                            {{ old('status', $page->status ?? 1) == 0 ? 'selected' : '' }}>Ẩn</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="choices-publish-hot-input" class="form-label font-weight-semibold">Nổi bật</label>

                                    <select class="form-select @error('hot') is-invalid @enderror" name="hot">
                                        <option value="0"
                                            {{ old('hot', $page->hot ?? 0) == 0 ? 'selected' : '' }}>
                                            Ẩn</option>
                                        <option value="1"
                                            {{ old('hot', $page->hot ?? 0) == 1 ? 'selected' : '' }}>
                                            Bật</option>
                                    </select>
                                    @error('hot')
                                        <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                    @enderror
                                </div>

                                @include('admin.partials.publishing-fields', ['model' => $page ?? null, 'col' => 'col-md-12'])

                                @include('admin.partials.localized-fields', [
                                    'fields' => [
                                        ['base' => 'slug', 'label' => 'Slug', 'col' => 'col-md-12', 'type' => 'text'],
                                    ],
                                    'model' => $page ?? null,
                                ])

                                @include('admin.partials.preview-link', ['model' => $page ?? null])

                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        @include('admin.partials.seo-fields', ['model' => $page ?? null])
                        <!-- end card -->

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Chủ đề sản phẩm</h5>
                            </div>
                            <div class="card-body">
                                <select class="form-select @error('category_id') is-invalid @enderror" id="choices-category-input" name="category_id">
                                    <option value="">-- Chọn danh mục sản phẩm --</option>
                                    @php
                                        renderCategoryOptions(
                                            $category,
                                            0,
                                            old('category_id', $page->category_id ?? ''),
                                            'id_cate_product',
                                        );
                                    @endphp
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Thương hiệu</h5>
                            </div>
                            <div class="card-body">
                                <select class="form-select @error('brand_id') is-invalid @enderror" id="choices-brand-input" name="brand_id">
                                    <option value="">-- Chọn thương hiệu --</option>
                                    @if (isset($brands))
                                        @foreach ($brands as $b)
                                            <option value="{{ $b->id_brand }}"
                                                {{ old('brand_id', $page->brand_id ?? '') == $b->id_brand ? 'selected' : '' }}>
                                                {{ $b->name_vn }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

            </form>

        </div>
        <!-- container-fluid -->
    </div>

    @include('admin.partials.ckeditor')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle removing existing detail images
    const existingContainer = document.getElementById('existing-images-container');
    const keptInput = document.getElementById('kept-images-input');

    if (existingContainer && keptInput) {
        existingContainer.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-existing-image');
            if (!btn) return;

            const item = btn.closest('.existing-image-item');
            if (!item) return;

            if (confirm('Bạn có chắc muốn xóa ảnh này khỏi sản phẩm?')) {
                item.remove();
                updateKeptImages();
            }
        });

        function updateKeptImages() {
            const remaining = [];
            document.querySelectorAll('.existing-image-item').forEach(el => {
                const img = el.getAttribute('data-image');
                if (img) remaining.push(img);
            });
            keptInput.value = JSON.stringify(remaining);
        }
    }
});
</script>
@endpush
@endsection
