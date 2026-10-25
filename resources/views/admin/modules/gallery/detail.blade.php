@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Add')
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
                            <div class="live-preview">
                                <form
                                    action="{{ route('admin.' . $nameClass . ($action == 'create' ? '.store' : '.update'), ['uuid' => $page->uuid ?? '']) }}"
                                    method="POST" enctype="multipart/form-data">

                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Tên hình ảnh</label>
                                                <input type="text" name="name_vn"
                                                    class="form-control @error('name_vn') is-invalid @enderror"
                                                    placeholder="Nhập tên hình ảnh"
                                                    value="{{ old('name_vn', $page->name_vn ?? '') }}">
                                                @error('name_vn')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="compnayNameinput" class="form-label">Số thứ tự</label>
                                                <input type="number" name="stt"
                                                    class="form-control @error('stt') is-invalid @enderror"
                                                    placeholder="Enter your title page"
                                                    value="{{ old('stt', $page->stt ?? '') }}">
                                                @error('stt')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label font-weight-semibold">Hình ảnh <span class="text-muted font-weight-normal">(Khuyến nghị: 800x600 px hoặc 1200x800 px)</span></label>
                                                <input type="file" name="image"
                                                    class="d-none @error('image') is-invalid @enderror" id="gallery_image_input" accept="image/*" onchange="if(this.files[0]){ document.getElementById('gallery_preview_img').src = URL.createObjectURL(this.files[0]); document.getElementById('gallery_preview_box').classList.remove('d-none'); }">
                                                <input type="hidden" id="gallery_image_hidden" name="image" value="{{ $page->image ?? '' }}">
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
                                                <div id="gallery_preview_box" class="mt-2 p-2 border rounded bg-light d-inline-block {{ empty($page->image) ? 'd-none' : '' }}" style="max-width: 240px;">
                                                    <img id="gallery_preview_img" src="{{ !empty($page->image) ? asset($page->image) : '' }}" alt="" class="img-fluid rounded-1" style="max-height: 180px; object-fit: contain;">
                                                </div>
                                                @error('image')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        @if (!empty($page->image))
                                            <div class="col-md-6">
                                                <div class="mb-3" style="display:none;">
                                                    <label for="firstNameinput" class="form-label">Hình ảnh hiện tại</label>
                                                     <img src="{{ $page->image }}" alt=""
                                                         width="200px" height="auto">
                                                </div>
                                            </div>
                                        @endif
                                        <!--end col-->
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
    @include('admin.partials.ckeditor')
@endsection
