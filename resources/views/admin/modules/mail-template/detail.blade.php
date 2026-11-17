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
                                <li class="breadcrumb-item"><a href="{{ route('admin.mail-template.index') }}">Template mail</a></li>
                                <li class="breadcrumb-item active">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }} template mail
                                </li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            @include('admin.partials.error-banner')

            <form
                action="{{ route('admin.mail-template.' . ($action == 'create' ? 'store' : 'update'), ['id' => $template->id ?? '']) }}"
                method="POST">
                @csrf
                @if($action == 'edit')
                    @method('PUT')
                @endif
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Thông tin template mail</h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label font-weight-semibold">Tên template <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                                   value="{{ old('name', $template->name ?? '') }}" placeholder="Nhập tên template" required>
                                            @error('name')
                                                <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="type" class="form-label font-weight-semibold">Loại template <span class="text-danger">*</span></label>
                                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                                <option value="">Chọn loại template</option>
                                                @foreach($types as $key => $value)
                                                    <option value="{{ $key }}" {{ old('type', $template->type ?? '') == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="subject" class="form-label font-weight-semibold">Tiêu đề email <span class="text-danger">*</span></label>
                                    <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror"
                                           value="{{ old('subject', $template->subject ?? '') }}" placeholder="Nhập tiêu đề email" required>
                                    @error('subject')
                                        <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="content" class="form-label font-weight-semibold">Nội dung template <span class="text-danger">*</span></label>
                                    <textarea name="content" id="content" data-ckeditor="true"
                                              class="form-control @error('content') is-invalid @enderror"
                                              rows="15">{{ old('content', $template->content ?? '') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Sử dụng biến trong nội dung: {customer_name}, {order_id}, {order_total}, v.v.
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label for="variables" class="form-label font-weight-semibold">Danh sách biến (mỗi biến trên một dòng)</label>
                                    <textarea name="variables[]" id="variables" class="form-control @error('variables') is-invalid @enderror" rows="5" placeholder="customer_name&#10;order_id&#10;order_total&#10;...">{{ old('variables', isset($template->variables) && is_array($template->variables) ? implode("\n", $template->variables) : '') }}</textarea>
                                    @error('variables')
                                        <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Các biến sẽ được sử dụng trong template. Mỗi biến trên một dòng.
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label for="is_active" class="form-label font-weight-semibold">Trạng thái</label>
                                    <div class="form-check form-switch form-switch-success">
                                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                               {{ old('is_active', $template->is_active ?? 1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Kích hoạt</label>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Hành động</h5>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> {{ $action == 'create' ? 'Thêm mới' : 'Cập nhật' }}
                                    </button>

                                    <a href="{{ route('admin.mail-template.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Quay lại
                                    </a>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div><!-- end row -->
            </form>
        </div>
    </div>
    @include('admin.partials.ckeditor')
@endsection
