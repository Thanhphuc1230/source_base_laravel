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
                                <div class="row align-items-end mb-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <label class="form-label fw-semibold mb-0" for="password">Mật khẩu <span class="text-danger">*</span></label>
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 fs-11" id="btnDetailRandomPass" title="Tạo mật khẩu ngẫu nhiên">
                                                    <i class="ri-refresh-line me-1"></i> Tạo ngẫu nhiên
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 fs-11" id="btnDetailCopyPass" title="Sao chép mật khẩu">
                                                    <i class="ri-file-copy-line me-1"></i> <span id="detailCopyPassText">Copy</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="input-group">
                                            <input type="password" id="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                placeholder="Nhập hoặc tạo mật khẩu ngẫu nhiên" required>
                                            <button class="btn btn-outline-secondary" type="button" id="btnToggleDetailPass" title="Hiện/Ẩn mật khẩu">
                                                <i class="ri-eye-line fs-14"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold mb-1" for="password_confirmation">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" id="password_confirmation"
                                                class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation"
                                                placeholder="Nhập lại mật khẩu" required>
                                            <button class="btn btn-outline-secondary" type="button" id="btnToggleDetailConfirmPass" title="Hiện/Ẩn mật khẩu">
                                                <i class="ri-eye-line fs-14"></i>
                                            </button>
                                        </div>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                                        @enderror
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

@push('scripts')
<script>
$(document).ready(function() {
    function generateStrongPassword(length = 10) {
        const uppercase = "ABCDEFGHJKLMNPQRSTUVWXYZ";
        const lowercase = "abcdefghijkmnpqrstuvwxyz";
        const numbers = "23456789";
        const symbols = "@#$%&*!";
        const allChars = uppercase + lowercase + numbers + symbols;

        let password = "";
        password += uppercase.charAt(Math.floor(Math.random() * uppercase.length));
        password += lowercase.charAt(Math.floor(Math.random() * lowercase.length));
        password += numbers.charAt(Math.floor(Math.random() * numbers.length));
        password += symbols.charAt(Math.floor(Math.random() * symbols.length));

        for (let i = password.length; i < length; i++) {
            password += allChars.charAt(Math.floor(Math.random() * allChars.length));
        }

        return password.split('').sort(() => 0.5 - Math.random()).join('');
    }

    function copyToClipboard(text, onDone) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(function() {
                if (typeof onDone === 'function') onDone();
            }).catch(function() {
                fallbackCopy(text, onDone);
            });
        } else {
            fallbackCopy(text, onDone);
        }
    }

    function fallbackCopy(text, onDone) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            if (typeof onDone === 'function') onDone();
        } catch (err) {}
        document.body.removeChild(textArea);
    }

    // Nút tạo mật khẩu ngẫu nhiên trên trang Detail
    $('#btnDetailRandomPass').on('click', function() {
        const rand = generateStrongPassword(10);
        $('#password').val(rand).attr('type', 'text');
        $('#password_confirmation').val(rand).attr('type', 'text');
        $('#btnToggleDetailPass i, #btnToggleDetailConfirmPass i').removeClass('ri-eye-line').addClass('ri-eye-off-line');

        // Animation visual
        const btn = $(this);
        btn.addClass('btn-primary').removeClass('btn-outline-primary');
        setTimeout(() => {
            btn.addClass('btn-outline-primary').removeClass('btn-primary');
        }, 500);
    });

    // Nút Copy mật khẩu trên trang Detail
    $('#btnDetailCopyPass').on('click', function() {
        const val = $('#password').val();
        if (!val) {
            alert('Chưa có mật khẩu để sao chép!');
            return;
        }

        copyToClipboard(val, function() {
            const btnText = $('#detailCopyPassText');
            btnText.text('Đã chép!');
            $('#btnDetailCopyPass').removeClass('btn-outline-secondary').addClass('btn-success');
            setTimeout(() => {
                btnText.text('Copy');
                $('#btnDetailCopyPass').removeClass('btn-success').addClass('btn-outline-secondary');
            }, 1500);
        });
    });

    // Toggle hiện/ẩn mật khẩu Detail
    $('#btnToggleDetailPass').on('click', function() {
        const input = $('#password');
        const icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('ri-eye-line').addClass('ri-eye-off-line');
        } else {
            input.attr('type', 'password');
            icon.removeClass('ri-eye-off-line').addClass('ri-eye-line');
        }
    });

    $('#btnToggleDetailConfirmPass').on('click', function() {
        const input = $('#password_confirmation');
        const icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('ri-eye-line').addClass('ri-eye-off-line');
        } else {
            input.attr('type', 'password');
            icon.removeClass('ri-eye-off-line').addClass('ri-eye-line');
        }
    });
});
</script>
@endpush
