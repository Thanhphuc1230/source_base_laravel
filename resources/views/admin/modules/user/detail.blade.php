@extends('admin.master')
@section('module', $nameItem)
@section('action', $action == 'create' ? 'Thêm' : 'Chỉnh sửa')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }} {{ $nameItem }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Người dùng</a></li>
                                <li class="breadcrumb-item active">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            @include('admin.partials.error-banner')

            <form action="{{ route('admin.user.' . ($action == 'create' ? 'store' : 'update'), ['uuid' => $user->uuid ?? '']) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if($action == 'edit')
                    @method('PUT')
                @endif
                
                <div class="row">
                    <!-- Left Column: User Info -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0 text-dark">Thông tin người dùng</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold" for="fullname">Họ và tên <span class="text-danger">*</span></label>
                                            <input type="text" id="fullname"
                                                class="form-control @error('fullname') is-invalid @enderror" name="fullname"
                                                value="{{ old('fullname', $user->fullname ?? '') }}"
                                                placeholder="Nhập họ và tên" required>
                                            @error('fullname')
                                                <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold" for="username">Tên đăng nhập <span class="text-danger">*</span></label>
                                            <input type="text" id="username"
                                                class="form-control @error('username') is-invalid @enderror" name="username"
                                                value="{{ old('username', $user->username ?? '') }}"
                                                placeholder="Nhập tên đăng nhập" required>
                                            @error('username')
                                                <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold" for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email" id="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email', $user->email ?? '') }}"
                                                placeholder="Nhập email" required>
                                            @error('email')
                                                <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold" for="phone">Số điện thoại</label>
                                            <input type="tel" id="phone"
                                                class="form-control @error('phone') is-invalid @enderror" name="phone"
                                                value="{{ old('phone', $user->phone ?? '') }}"
                                                placeholder="0xxxxxxxxx hoặc +84xxxxxxxxx">
                                            @error('phone')
                                                <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                @if($action == 'create')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold" for="password">Mật khẩu <span class="text-danger">*</span></label>
                                            <input type="password" id="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                placeholder="Nhập mật khẩu" required>
                                            @error('password')
                                                <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold" for="password_confirmation">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                            <input type="password" id="password_confirmation"
                                                class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation"
                                                placeholder="Nhập lại mật khẩu" required>
                                            @error('password_confirmation')
                                                <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold" for="level">Cấp độ <span class="text-danger">*</span></label>
                                            <select id="level" name="level" class="form-select @error('level') is-invalid @enderror" required>
                                                <option value="">Chọn cấp độ</option>
                                                <option value="1" {{ old('level', $user->level ?? '') == 1 ? 'selected' : '' }}>Quản trị viên (Level 1)</option>
                                                <option value="2" {{ old('level', $user->level ?? '') == 2 ? 'selected' : '' }}>Quản lý (Level 2)</option>
                                                <option value="3" {{ old('level', $user->level ?? '') == 3 ? 'selected' : '' }}>Nhân viên (Level 3)</option>
                                            </select>
                                            @error('level')
                                                <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold" for="status">Trạng thái <span class="text-danger">*</span></label>
                                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                                <option value="">Chọn trạng thái</option>
                                                <option value="1" {{ old('status', isset($user) ? (string)$user->status : '1') === '1' ? 'selected' : '' }}>Hoạt động</option>
                                                <option value="0" {{ old('status', isset($user) ? (string)$user->status : '1') === '0' ? 'selected' : '' }}>Không hoạt động</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 d-flex justify-content-end gap-2 border-top pt-3">
                                    <a href="{{ route('admin.user.index') }}" class="btn btn-light">Hủy</a>
                                    <button type="submit" class="btn btn-primary">{{ $action == 'create' ? 'Tạo mới' : 'Cập nhật' }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Role assignment -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0 text-dark">Phân quyền chức vụ</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small">Chọn vai trò thích hợp để cấp quyền cho tài khoản người dùng này.</p>
                                @error('roles')
                                    <div class="invalid-feedback d-block fw-semibold mb-2">{{ $message }}</div>
                                @enderror
                                @if(isset($roles) && count($roles) > 0)
                                    @php
                                        $selectedRoles = old('roles', isset($user) ? $user->roles->pluck('id')->toArray() : []);
                                    @endphp
                                    @foreach($roles as $role)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input @error('roles') is-invalid @enderror" type="checkbox" name="roles[]" 
                                                   value="{{ $role->id }}" id="role_{{ $role->id }}"
                                                   {{ in_array($role->id, $selectedRoles) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role_{{ $role->id }}">
                                                <span class="fw-semibold">{{ $role->name }}</span>
                                                @if(!empty($role->description))
                                                    <span class="d-block text-muted text-xs">{{ $role->description }}</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted fst-italic">Chưa có vai trò nào được thiết lập.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
