@extends('admin.master')

@section('module', 'Media')
@section('action', 'Thư viện')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-weight-bold">Thư viện Media & Tệp tin</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary btn-sm rounded-2 px-3 shadow-xs d-inline-flex align-items-center gap-1.5" style="height: 38px;" onclick="window.openMediaPicker({ mode: 'multiple', module: 'all' })">
                            <i class="ri-folder-image-line fs-16"></i>
                            <span class="fw-semibold">Mở Thư viện Media</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4 text-center">
                <div class="py-4">
                    <span class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle mb-3" style="width: 64px; height: 64px;">
                        <i class="ri-gallery-upload-line display-6"></i>
                    </span>
                    <h5 class="fw-bold text-dark mb-2">Quản lý Hình ảnh & Video tập trung</h5>
                    <p class="text-muted text-sm max-w-xl mx-auto mb-4" style="max-width: 540px;">
                        Hệ thống tự động nén định dạng WebP, lưu trữ thống nhất tại thư mục <code>public/uploads/</code> và cho phép tái sử dụng hình ảnh ở tất cả các module trong website.
                    </p>
                    <button type="button" class="btn btn-primary px-4 py-2 rounded-2 shadow-xs d-inline-flex align-items-center gap-2" onclick="window.openMediaPicker({ mode: 'multiple', module: 'all' })">
                        <i class="ri-folder-open-line fs-18"></i>
                        <span class="fw-semibold">Truy cập Thư viện ngay</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
