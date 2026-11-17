@extends('admin.master')
@section('module', 'Profile')
@section('action', 'Thông tin cá nhân')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            @include('admin.partials.error-banner')
            <div class="position-relative mx-n4 mt-n4">
                <div class="profile-wid-bg profile-setting-img">
                    <img src="{{ asset('admin_style/assets/images/profile-bg.jpg') }}" class="profile-wid-img" alt="">
                    <div class="overlay-content">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-3">
                    <div class="card mt-n5">
                        <div class="card-body p-4">
                            <div class="text-center">
                                <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                                    <img src="{{ Auth::user()->avatar ? (str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset(Auth::user()->avatar)) : asset('admin/images/users/avatar-1.jpg') }}"
                                        class="rounded-circle avatar-xl img-thumbnail user-profile-image"
                                        alt="user-profile-image">
                                </div>
                                <h5 class="fs-16 mb-1">{{ Auth::user()->fullname }}</h5>
                                <p class="text-muted mb-0">{{ Auth::user()->level == 1 ? 'Admin' : (Auth::user()->level == 2 ? 'Staff' : 'User') }}</p>
                            </div>
                        </div>
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->
                <div class="col-xxl-9">
                    <div class="card mt-xxl-n5">
                        <div class="card-header">
                            <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ $errors->has('old_password') || $errors->has('new_password') || $errors->has('confirm_password') ? '' : 'active' }}" data-bs-toggle="tab" href="#personalDetails"
                                        role="tab">
                                        <i class="fas fa-home me-1"></i>Thông tin
                                        @if($errors->has('fullname') || $errors->has('email') || $errors->has('username') || $errors->has('avatar'))
                                            <span class="badge bg-danger ms-1">Lỗi</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $errors->has('old_password') || $errors->has('new_password') || $errors->has('confirm_password') ? 'active' : '' }}" data-bs-toggle="tab" href="#changePassword" role="tab">
                                        <i class="far fa-user me-1"></i>Đổi mật khẩu
                                        @if($errors->has('old_password') || $errors->has('new_password') || $errors->has('confirm_password'))
                                            <span class="badge bg-danger ms-1">Lỗi</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-4">
                            <div class="tab-content">
                                <div class="tab-pane {{ $errors->has('old_password') || $errors->has('new_password') || $errors->has('confirm_password') ? '' : 'active' }}" id="personalDetails" role="tabpanel">
                                    <form action="{{ route('admin.profile.update',['uuid'=>Auth::user()->uuid]) }}" method="POST" enctype="multipart/form-data">
                                       @csrf
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="firstnameInput" class="form-label font-weight-semibold">Họ và tên <span class="text-danger">*</span></label>
                                                    <input type="text" name="fullname" class="form-control @error('fullname') is-invalid @enderror"
                                                        id="firstnameInput" placeholder="Nhập họ và tên"
                                                        value="{{ old('fullname', Auth::user()->fullname) }}">
                                                    @error('fullname')
                                                        <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="emailInput" class="form-label font-weight-semibold">Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                                        id="emailInput" placeholder="Nhập địa chỉ email"
                                                        value="{{ old('email', Auth::user()->email) }}">
                                                    @error('email')
                                                        <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!--end col-->
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="usernameInput" class="form-label font-weight-semibold">Username <span class="text-danger">*</span></label>
                                                    <input type="text" id="usernameInput" class="form-control @error('username') is-invalid @enderror"
                                                        name="username" value="{{ old('username', Auth::user()->username) }}" />
                                                    @error('username')
                                                        <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="fileInput" class="form-label font-weight-semibold">Avatar mới</label>
                                                    <input type="file" id="fileInput" name="avatar"
                                                        class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                                                    <div id="imageContainer" class="mt-2"></div>
                                                    @error('avatar')
                                                        <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!--end col-->
                                            <div class="col-lg-12">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </form>
                                </div>
                                <!--end tab-pane-->

                                <div class="tab-pane {{ $errors->has('old_password') || $errors->has('new_password') || $errors->has('confirm_password') ? 'active' : '' }}" id="changePassword" role="tabpanel">
                                    <form action="{{ route('admin.profile.changePassword') }}" method="POST">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-lg-4">
                                                <div>
                                                    <label for="oldpasswordInput" class="form-label font-weight-semibold">Mật khẩu cũ <span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control @error('old_password') is-invalid @enderror" id="oldpasswordInput"
                                                        placeholder="Nhập mật khẩu hiện tại" name="old_password">
                                                    @error('old_password')
                                                        <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-4">
                                                <div>
                                                    <label for="newpasswordInput" class="form-label font-weight-semibold">Mật khẩu mới <span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="newpasswordInput"
                                                        placeholder="Nhập mật khẩu mới" name="new_password">
                                                    @error('new_password')
                                                        <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-4">
                                                <div>
                                                    <label for="confirmpasswordInput" class="form-label font-weight-semibold">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control @error('confirm_password') is-invalid @enderror" id="confirmpasswordInput"
                                                        placeholder="Nhập lại mật khẩu mới" name="confirm_password">
                                                    @error('confirm_password')
                                                        <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-success">Đổi mật khẩu</button>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

        </div>
        <!-- container-fluid -->
    </div><!-- End Page-content -->
@endsection
