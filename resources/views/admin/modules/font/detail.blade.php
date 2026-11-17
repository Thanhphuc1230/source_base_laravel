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
                                <li class="breadcrumb-item"><a href="{{ route('admin.' . $nameClass . '.index') }}">Font chữ</a></li>
                                <li class="breadcrumb-item active">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @include('admin.partials.error-banner')

            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }}
                                {{ $nameItem }}</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{ route('admin.'.$nameClass.($action == 'create' ? '.store' : '.update'), $action == 'edit' ? $page->id : []) }}" method="POST" enctype="multipart/form-data">

                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="fontType" class="form-label font-weight-semibold">Loại Font <span class="text-danger">*</span></label>
                                                <select name="type" id="fontType" class="form-select @error('type') is-invalid @enderror" required>
                                                    <option value="">-- Chọn loại --</option>
                                                    <option value="system" {{ old('type', $page->type ?? '') == 'system' ? 'selected' : '' }}>System Font</option>
                                                    <option value="google" {{ old('type', $page->type ?? '') == 'google' ? 'selected' : '' }}>Google Font</option>
                                                    <option value="upload" {{ old('type', $page->type ?? '') == 'upload' ? 'selected' : '' }}>Upload Font</option>
                                                </select>
                                                @error('type')
                                                    <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label font-weight-semibold">Tên Font <span class="text-danger">*</span></label>
                                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                                                    placeholder="VD: Arial, Roboto"
                                                    value="{{ old('name', $page->name ?? '') }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="family" class="form-label font-weight-semibold">Font Family CSS <span class="text-danger">*</span></label>
                                                <input type="text" id="family" name="family" class="form-control @error('family') is-invalid @enderror"
                                                    placeholder="VD: 'Arial', sans-serif"
                                                    value="{{ old('family', $page->family ?? '') }}" required>
                                                <small class="form-text text-muted">Font family sử dụng trong CSS</small>
                                                @error('family')
                                                    <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Google Font URL -->
                                        <div class="col-md-12" id="googleUrlField" style="display: none;">
                                            <div class="mb-3">
                                                <label for="css_url" class="form-label font-weight-semibold">Google Font URL <span class="text-danger">*</span></label>
                                                <input type="url" id="css_url" name="css_url" class="form-control @error('css_url') is-invalid @enderror"
                                                    placeholder="https://fonts.googleapis.com/css2?family=Roboto"
                                                    value="{{ old('css_url', $page->css_url ?? '') }}">
                                                <small class="form-text text-muted">Lấy từ Google Fonts</small>
                                                @error('css_url')
                                                    <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Upload Font File -->
                                        <div class="col-md-12" id="uploadFileField" style="display: none;">
                                            <div class="mb-3">
                                                <label for="font_file" class="form-label font-weight-semibold">File Font <span class="text-danger">*</span></label>
                                                <input type="file" id="font_file" name="font_file" class="form-control @error('font_file') is-invalid @enderror"
                                                    accept=".woff,.woff2,.ttf,.otf">
                                                <small class="form-text text-muted">Hỗ trợ: .woff, .woff2, .ttf, .otf (Max: 5MB)</small>
                                                @error('font_file')
                                                    <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sort_order" class="form-label font-weight-semibold">Thứ tự hiển thị</label>
                                                <input type="number" id="sort_order" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
                                                    value="{{ old('sort_order', $page->sort_order ?? 0) }}" min="0">
                                                @error('sort_order')
                                                    <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="is_active" class="form-label font-weight-semibold">Trạng thái</label>
                                                <select id="is_active" class="form-select @error('is_active') is-invalid @enderror" name="is_active">
                                                    <option value="1" {{ old('is_active', $page->is_active ?? 1) == 1 ? 'selected' : '' }}>Kích hoạt</option>
                                                    <option value="0" {{ old('is_active', $page->is_active ?? 1) == 0 ? 'selected' : '' }}>Vô hiệu hóa</option>
                                                </select>
                                                @error('is_active')
                                                    <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        @if($action == 'create')
                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <input type="submit" name="return_back" class="btn btn-primary" value="Lưu và tạo mới">
                                                <input type="submit" name="return_list" class="btn btn-primary" value="Lưu và về danh sách">
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <input type="submit" name="return_list" class="btn btn-primary" value="Cập nhật">
                                            </div>
                                        </div>
                                        @endif
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
        </div>
    </div>
@endsection
