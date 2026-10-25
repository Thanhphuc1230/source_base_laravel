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
                                                <label for="firstNameinput" class="form-label">Họ và tên</label>
                                                <input type="text" name="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    placeholder="Enter your title page"
                                                    value="{{ old('name', $page->name ?? '') }}">
                                                @error('name')
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
                                                <label for="created_at" class="form-label">Ngày đăng</label>
                                                <input type="datetime-local" id="created_at" name="created_at"
                                                    class="form-control @error('created_at') is-invalid @enderror"
                                                    value="{{ old('created_at', isset($page->created_at) ? \Carbon\Carbon::parse($page->created_at)->format('Y-m-d\TH:i') : '') }}">
                                            </div>
                                        </div>
                                    
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Nội dung</label>
                                                <textarea type="text" name="message"
                                                    class="form-control @error('message') is-invalid @enderror">{{ old('message', $page->message ?? '') }} </textarea>
                                                @error('message')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Trạng thái</label>
                                                <select name="status" class="form-control">
                                                    <option value="1"  {{ (old('status') ?: $page->status ?? '') == 1 ? 'selected' : '' }}>Hiển thị</option>
                                                    <option value="0"  {{ (old('status') ?: $page->status ?? '') == 1 ? 'selected' : '' }}>Ẩn</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label font-weight-semibold">Hình ảnh Avatar <span class="text-muted font-weight-normal">(Khuyến nghị: 200x200 px - Tỉ lệ 1:1)</span></label>
                                                <div class="d-flex align-items-center gap-1.5 mb-2">
                                                    <button type="button" class="btn btn-sm btn-primary rounded-2 px-3 shadow-xs d-inline-flex align-items-center gap-1.5" style="height: 38px;" onclick="window.openMediaPicker({ mode: 'single', module: 'feedback', onSelect: function(item) { document.getElementById('feedback_image_hidden').value = item.path; document.getElementById('feedback_preview_img').src = item.url; document.getElementById('feedback_preview_box').classList.remove('d-none'); } })">
                                                        <i class="ri-folder-image-line fs-15"></i>
                                                        <span class="fw-semibold">Chọn từ Thư viện</span>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-2 px-3 bg-white d-inline-flex align-items-center gap-1.5" style="height: 38px;" onclick="document.getElementById('feedback_image_input').click();">
                                                        <i class="ri-upload-2-line fs-15"></i>
                                                        <span>Tải từ máy tính</span>
                                                    </button>
                                                </div>
                                                <input type="file" id="feedback_image_input" name="image" class="d-none @error('image') is-invalid @enderror" accept="image/*" onchange="if(this.files[0]){ document.getElementById('feedback_preview_img').src = URL.createObjectURL(this.files[0]); document.getElementById('feedback_preview_box').classList.remove('d-none'); }">
                                                <input type="hidden" id="feedback_image_hidden" name="image" value="{{ $page->image ?? '' }}">
                                                @error('image')
                                                    <span class="text-danger small d-block mb-2">{{ $message }}</span>
                                                @enderror
                                                <div id="feedback_preview_box" class="mt-2 p-2 border rounded bg-light d-inline-block {{ empty($page->image) ? 'd-none' : '' }}" style="max-width: 200px;">
                                                    <img id="feedback_preview_img" src="{{ !empty($page->image) ? asset($page->image) : '' }}" alt="" class="img-fluid rounded-1" style="max-height: 160px; object-fit: contain;">
                                                </div>
                                            </div>
                                        </div>


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
