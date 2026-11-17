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
                                <li class="breadcrumb-item"><a href="{{ route('admin.' . $nameClass . '.index') }}">Trang đơn</a></li>
                                <li class="breadcrumb-item active">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }} trang
                                </li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            @include('admin.partials.error-banner')

            <form
                action="{{ route('admin.' . $nameClass . ($action == 'create' ? '.store' : '.update'), ['uuid' => $page->uuid ?? '']) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                @include('admin.partials.localized-fields', [
                                    'fields' => [
                                        ['base' => 'name', 'label' => 'Tiêu đề', 'col' => 'col-md-12'],
                                        ['base' => 'content', 'label' => 'Nội dung', 'col' => 'col-md-12', 'rows' => 10, 'type' => 'textarea', 'ckeditor' => true],
                                    ],
                                    'model' => $page ?? null,
                                ])
                            </div>
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Hình ảnh trang</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($adminLanguages as $locale)
                                        @include('admin.partials.image-upload', [
                                            'locale' => $locale,
                                            'imageFolder' => $imageFolder,
                                            'model' => $page ?? null,
                                            'dimensions' => '800x600 px (Tỉ lệ 4:3)',
                                        ])
                                    @endforeach
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

                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

            </form>

        </div>
        <!-- container-fluid -->
    </div>
    @include('admin.partials.ckeditor')
@endsection
