@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Chi tiết')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Thông tin bình luận</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            @include('admin.partials.error-banner')
                            <div class="live-preview">
                                <form action="{{ route('admin.'.$nameClass.'.update', $page->uuid) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label font-weight-semibold">Tên người bình luận</label>
                                                <input type="text" id="name" name="name" class="form-control"
                                                    value="{{ old('name', $page->name ?? '') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label font-weight-semibold">Email</label>
                                                <input type="text" id="email" name="email" class="form-control"
                                                    value="{{ old('email', $page->email ?? '') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="id_post" class="form-label font-weight-semibold">ID Bài viết</label>
                                                <input type="text" id="id_post" name="id_post" class="form-control"
                                                    value="{{ old('id_post', $page->id_post ?? '') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="type_post" class="form-label font-weight-semibold">Loại bài viết</label>
                                                <input type="text" id="type_post" name="type_post" class="form-control"
                                                    value="{{ old('type_post', $page->type_post_name ?? '') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="content" class="form-label font-weight-semibold">Nội dung bình luận</label>
                                                <textarea id="content" class="form-control" name="content" rows="6" readonly>{{ old('content', $page->content ?? '') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="status" class="form-label font-weight-semibold">Trạng thái duyệt</label>
                                                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                                                    <option value="1" {{ old('status', $page->status ?? 0) == 1 ? 'selected' : '' }}>Đã duyệt</option>
                                                    <option value="0" {{ old('status', $page->status ?? 0) == 0 ? 'selected' : '' }}>Chưa duyệt</option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="created_at" class="form-label font-weight-semibold">Ngày tạo</label>
                                                <input type="text" id="created_at" name="created_at" class="form-control"
                                                    value="{{ $page->created_at ? $page->created_at->format('d-m-Y H:i:s') : '' }}" readonly>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <a href="{{ route('admin.'.$nameClass.'.index') }}" class="btn btn-primary me-2">Quay lại danh sách</a>
                                                <button type="submit" class="btn btn-success">Cập nhật</button>
                                            </div>
                                        </div>
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
