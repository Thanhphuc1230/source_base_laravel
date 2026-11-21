@extends('admin.master')
@section('module', $nameItem)
@section('action', $action == 'create' ? 'Thêm' : 'Chỉnh sửa')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }} {{ $nameItem }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.' . $nameClass . '.index') }}">Danh mục sản phẩm</a></li>
                                <li class="breadcrumb-item active">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @include('admin.partials.error-banner')

            <form action="{{ route('admin.' . $nameClass . ($action == 'create' ? '.store' : '.update'), ['uuid' => $page->uuid ?? '']) }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if ($action == 'edit')
                    <input type="hidden" name="currentPage" value="{{ $currentPage }}">
                @endif

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light py-3 border-0">
                                <h5 class="card-title mb-0 font-weight-bold">Thông tin danh mục</h5>
                            </div>
                            <div class="card-body">
                                @include('admin.partials.localized-fields', [
                                    'fields' => [
                                        ['base' => 'name', 'label' => 'Tiêu đề', 'col' => 'col-md-12'],
                                    ],
                                    'model' => $page ?? null,
                                ])
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light py-3 border-0">
                                <h5 class="card-title mb-0 font-weight-bold">Hình ảnh & Banner danh mục</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($adminLanguages as $locale)
                                        @include('admin.partials.image-upload', [
                                            'locale' => $locale,
                                            'imageFolder' => $imageFolder,
                                            'model' => $page ?? null,
                                            'dimensions' => '600x600 px (Icon/Vuông) hoặc 1200x400 px (Banner)',
                                        ])
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="text-end mb-3">
                            @if ($action == 'create')
                                <input type="submit" name="return_back" class="btn btn-primary" value="Lưu và tạo mới">
                                <input type="submit" name="return_list" class="btn btn-primary" value="Lưu và về danh sách">
                            @else
                                <input type="submit" name="return_list" class="btn btn-primary" value="Cập nhật">
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light py-3 border-0">
                                <h5 class="card-title mb-0 font-weight-bold">Hiển thị</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="parent_id" class="form-label font-weight-semibold">Danh mục cha</label>
                                    <select id="parent_id" class="form-select @error('parent_id') is-invalid @enderror mb-1" name="parent_id">
                                        <option value="0" {{ old('parent_id', $page->parent_id ?? 0) == 0 ? 'selected' : '' }} style="font-weight: bold;">
                                            Trang chính (Cấp cao nhất)
                                        </option>
                                        @php
                                            renderCategoryOptions(
                                                $category,
                                                0,
                                                old('parent_id', $page->parent_id ?? 0),
                                                'id_cate_product',
                                            );
                                        @endphp
                                    </select>
                                    @error('parent_id')
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
                        </div>

                        @include('admin.partials.seo-fields', ['model' => $page ?? null])
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('#parent_id').select2();
        }
    });
</script>
@endpush
