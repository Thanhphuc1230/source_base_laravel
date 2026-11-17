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
                                                <label for="name" class="form-label font-weight-semibold">Họ và tên</label>
                                                <input type="text" id="name" name="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    placeholder="Nhập họ và tên khách hàng"
                                                    value="{{ old('name', $page->name ?? '') }}">
                                                @error('name')
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
                                                <label for="created_at" class="form-label font-weight-semibold">Ngày đăng</label>
                                                <input type="datetime-local" id="created_at" name="created_at"
                                                    class="form-control @error('created_at') is-invalid @enderror"
                                                    value="{{ old('created_at', isset($page->created_at) ? \Carbon\Carbon::parse($page->created_at)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                                                @error('created_at')
                                                    <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="message" class="form-label font-weight-semibold">Nội dung đánh giá</label>
                                                <textarea id="message" name="message" rows="3"
                                                    class="form-control @error('message') is-invalid @enderror"
                                                    placeholder="Nhập nội dung đánh giá của khách hàng">{{ old('message', $page->message ?? '') }}</textarea>
                                                @error('message')
                                                    <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="status" class="form-label font-weight-semibold">Trạng thái</label>
                                                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                                                    <option value="1" {{ old('status', $page->status ?? 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
                                                    <option value="0" {{ old('status', $page->status ?? 1) == 0 ? 'selected' : '' }}>Ẩn</option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label font-weight-semibold">Hình ảnh Avatar <span class="text-muted font-weight-normal">(Khuyến nghị: 200x200 px - Tỉ lệ 1:1)</span></label>
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
                                                <input type="hidden" id="feedback_image_hidden" name="image" value="{{ old('image', $page->image ?? '') }}">
                                                @error('image')
                                                    <div class="invalid-feedback d-block fw-semibold mb-2">{{ $message }}</div>
                                                @enderror
                                                <div id="feedback_preview_box" class="mt-2 p-2 border rounded bg-light d-inline-block {{ empty(old('image', $page->image ?? '')) ? 'd-none' : '' }} {{ $errors->has('image') ? 'border-danger' : '' }}" style="max-width: 200px;">
                                                    <img id="feedback_preview_img" src="{{ !empty(old('image', $page->image ?? '')) ? (str_starts_with(old('image', $page->image ?? ''), 'http') ? old('image', $page->image ?? '') : asset(old('image', $page->image ?? ''))) : '' }}" alt="" class="img-fluid rounded-1" style="max-height: 160px; object-fit: contain;">
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
