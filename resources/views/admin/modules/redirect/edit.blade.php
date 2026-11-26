@extends('admin.master')
@section('module', 'Redirect & 404 Monitor')
@section('action', 'Cập nhật')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Cập nhật Chuyển hướng</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.redirect.update', $redirect->uuid) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">URL Nguồn <span class="text-danger">*</span></label>
                            <input type="text" name="source_url" value="{{ old('source_url', $redirect->source_url) }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">URL Đích</label>
                            <input type="text" name="target_url" value="{{ old('target_url', $redirect->target_url) }}" class="form-control">
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Loại HTTP</label>
                                <select name="status_code" class="form-select">
                                    <option value="301" {{ old('status_code', $redirect->status_code) == 301 ? 'selected' : '' }}>301 - Chuyển hướng vĩnh viễn</option>
                                    <option value="302" {{ old('status_code', $redirect->status_code) == 302 ? 'selected' : '' }}>302 - Chuyển hướng tạm thời</option>
                                    <option value="404" {{ old('status_code', $redirect->status_code) == 404 ? 'selected' : '' }}>404 - Monitor</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Trạng thái</label>
                                <select name="is_active" class="form-select">
                                    <option value="1" {{ old('is_active', $redirect->is_active) ? 'selected' : '' }}>Kích hoạt</option>
                                    <option value="0" {{ ! old('is_active', $redirect->is_active) ? 'selected' : '' }}>Tắt</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <input type="text" name="note" value="{{ old('note', $redirect->note) }}" class="form-control">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('admin.redirect.index') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
