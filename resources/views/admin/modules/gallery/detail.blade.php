@extends('admin.master')
@section('module', $nameItem)
@section('action', $action == 'create' ? 'Thêm' : 'Chỉnh sửa')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }}
                                {{ $nameItem }}</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            @include('admin.partials.error-banner')
                            <div class="live-preview">
                                <form
                                    action="{{ route('admin.' . $nameClass . ($action == 'create' ? '.store' : '.update'), ['uuid' => $page->uuid ?? '']) }}"
                                    method="POST" enctype="multipart/form-data">

                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name_vn" class="form-label font-weight-semibold">Tên hình ảnh</label>
                                                <input type="text" id="name_vn" name="name_vn"
                                                    class="form-control @error('name_vn') is-invalid @enderror"
                                                    placeholder="Nhập tên hình ảnh"
                                                    value="{{ old('name_vn', $page->name_vn ?? '') }}">
                                                @error('name_vn')
                                                    <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="stt" class="form-label font-weight-semibold">Số thứ tự</label>
                                                <input type="number" id="stt" name="stt"
                                                    class="form-control @error('stt') is-invalid @enderror"
                                                    placeholder="Nhập số thứ tự"
                                                    value="{{ old('stt', $page->stt ?? '1') }}">
                                                @error('stt')
                                                    <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="gallery_image_input" class="form-label font-weight-semibold">Hình ảnh <span class="text-muted font-weight-normal">(Khuyến nghị: 800x600 px hoặc 1200x800 px)</span></label>
                                                <input type="file" name="image"
                                                    class="d-none @error('image') is-invalid @enderror" id="gallery_image_input" accept="image/*" onchange="if(this.files[0]){ document.getElementById('gallery_preview_img').src = URL.createObjectURL(this.files[0]); document.getElementById('gallery_preview_box').classList.remove('d-none'); }">
                                                <input type="hidden" id="gallery_image_hidden" name="image" value="{{ old('image', $page->image ?? '') }}">
                                                <div class="d-flex align-items-center gap-1.5 mb-2">
                                                    <button type="button" class="btn btn-sm btn-primary rounded-2 px-3 shadow-xs d-inline-flex align-items-center gap-1.5" style="height: 38px;" onclick="window.openMediaPicker({ mode: 'single', module: 'gallery', onSelect: function(item) { document.getElementById('gallery_image_hidden').value = item.path; document.getElementById('gallery_preview_img').src = item.url; document.getElementById('gallery_preview_box').classList.remove('d-none'); } })">
                                                        <i class="ri-folder-image-line fs-15"></i>
                                                        <span class="fw-semibold">Chọn từ Thư viện</span>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-2 px-3 bg-white d-inline-flex align-items-center gap-1.5" style="height: 38px;" onclick="document.getElementById('gallery_image_input').click();">
                                                        <i class="ri-upload-2-line fs-15"></i>
                                                        <span>Tải từ máy tính</span>
                                                    </button>
                                                </div>
                                                @error('image')
                                                    <div class="invalid-feedback d-block fw-semibold mb-2">{{ $message }}</div>
                                                @enderror
                                                <div id="gallery_preview_box" class="mt-2 p-2 border rounded bg-light d-inline-block {{ empty(old('image', $page->image ?? '')) ? 'd-none' : '' }} {{ $errors->has('image') ? 'border-danger' : '' }}" style="max-width: 240px;">
                                                    <img id="gallery_preview_img" src="{{ !empty(old('image', $page->image ?? '')) ? (str_starts_with(old('image', $page->image ?? ''), 'http') ? old('image', $page->image ?? '') : asset(old('image', $page->image ?? ''))) : '' }}" alt="" class="img-fluid rounded-1" style="max-height: 180px; object-fit: contain;">
                                                </div>
                                            </div>
                                        </div>

                                        @if ($action == 'create')
                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <input type="submit" name="return_back" class="btn btn-primary"
                                                        value="Lưu và tạo mới">
                                                    <input type="submit" name="return_list" class="btn btn-primary"
                                                        value="Lưu và về danh sách">
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <input type="submit" name="return_list" class="btn btn-primary"
                                                        value="Cập nhật">
                                                </div>
                                            </div>
                                        @endif
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
