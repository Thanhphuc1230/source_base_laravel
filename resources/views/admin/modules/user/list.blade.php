@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Danh sách')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Quản lý {{ $nameItem }}</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="listjs-table" id="customerList">
                                <div class="row g-3 mb-3 align-items-center">
                                    @hasPermission('user.create')
                                    <div class="col-sm-auto">
                                        <button type="button" id="btnOpenQuickCreate"
                                            class="btn btn-primary d-inline-flex align-items-center justify-content-center px-3 rounded-2"
                                            data-bs-toggle="modal" data-bs-target="#quickCreateModal"
                                            style="height: 38px; font-weight: 500; font-size: 13px;">
                                            <i class="ri-flashlight-line fs-16 me-1"></i> Tạo nhanh
                                        </button>
                                    </div>
                                    <div class="col-sm-auto">
                                        <a href="{{ route('admin.user.create') }}"
                                            class="btn btn-success d-inline-flex align-items-center justify-content-center px-3 rounded-2"
                                            style="height: 38px; font-weight: 500; font-size: 13px;">
                                            <i class="ri-add-line fs-16 me-1"></i> Thêm mới
                                        </a>
                                    </div>
                                    @endhasPermission
                                    @hasPermission('user.delete')
                                    <div class="col-sm-auto">
                                        <button type="button" id="deleteSelectedItems"
                                            class="btn btn-danger d-inline-flex align-items-center justify-content-center px-3 rounded-2"
                                            style="height: 38px; font-weight: 500; font-size: 13px;">
                                            <i class="ri-delete-bin-5-line fs-15 me-1"></i> Xóa đã chọn
                                        </button>
                                    </div>
                                    @endhasPermission
                                    <div class="col-sm">
                                        <div class="d-flex justify-content-sm-end align-items-center">
                                            <form action="{{ route('admin.user.index') }}" method="get"
                                                class="d-flex align-items-center gap-2 m-0 p-0 flex-wrap flex-sm-nowrap">
                                                <select class="form-select rounded-2" name="level" onchange="this.form.submit()"
                                                    style="width: 140px; max-width: 140px; height: 38px; font-size: 13px;">
                                                    <option value="">Cấp độ</option>
                                                    <option value="1" {{ request('level') == '1' ? 'selected' : '' }}>Admin</option>
                                                    <option value="2" {{ request('level') == '2' ? 'selected' : '' }}>Staff</option>
                                                    <option value="3" {{ request('level') == '3' ? 'selected' : '' }}>User</option>
                                                </select>
                                                <select class="form-select rounded-2" name="status" onchange="this.form.submit()"
                                                    style="width: 140px; max-width: 140px; height: 38px; font-size: 13px;">
                                                    <option value="">Trạng thái</option>
                                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Khóa</option>
                                                </select>
                                                <div class="admin-search-box">
                                                    <input type="text" class="form-control admin-search-input" name="search"
                                                        value="{{ request('search') }}" placeholder="Tìm kiếm... (Enter)">
                                                    <i class="ri-search-line search-icon"></i>
                                                    @if(request('search'))
                                                        <a href="{{ route('admin.user.index', array_filter(['level' => request('level'), 'status' => request('status')])) }}"
                                                           class="clear-btn" title="Xóa tìm kiếm">
                                                            <i class="ri-close-circle-fill fs-14"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive table-card mt-3 mb-1">
                                    <table class="table align-middle table-nowrap table-hover" id="customerTable">
                                        <thead class="table-light text-muted text-uppercase fs-11" style="background-color: #f8fafc;">
                                            <tr>
                                                <th class="text-center" style="width: 40px;"><input type="checkbox" class="form-check-input" id="checkAll"></th>
                                                <th class="text-center" style="width: 50px;">ID</th>
                                                <th class="text-start">Họ tên</th>
                                                <th class="text-start">Username</th>
                                                <th class="text-start">Email</th>
                                                <th class="text-start" style="width: 120px;">SĐT</th>
                                                <th class="text-center" style="width: 100px;">Cấp độ</th>
                                                <th class="text-center" style="width: 100px;">Trạng thái</th>
                                                <th class="text-center" style="width: 110px;">Ngày tạo</th>
                                                <th class="text-center" style="width: 100px;">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @if (count($list) > 0)
                                                <form id="delete-form-all"
                                                    action="{{ route('admin.user.bulk-delete') }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    @foreach ($list as $item)
                                                        <tr>
                                                            <td class="text-center"><input class="form-check-input" type="checkbox" name="uuids[]"
                                                                    value="{{ $item->uuid }}"></td>
                                                            <td class="text-center text-muted fw-semibold fs-12">{{ $item->id }}</td>
                                                            <td class="text-start fw-medium text-dark">{{ $item->fullname }}</td>
                                                            <td class="text-start text-muted fs-12">{{ $item->username }}</td>
                                                            <td class="text-start text-muted fs-12">{{ $item->email }}</td>
                                                            <td class="text-start text-muted fs-12">{{ $item->phone ?: '-' }}</td>
                                                            <td class="text-center">
                                                                @if($item->level == 1)
                                                                    <span class="badge bg-danger-subtle text-danger px-2 py-1">Admin</span>
                                                                @elseif($item->level == 2)
                                                                    <span class="badge bg-warning-subtle text-warning px-2 py-1">Staff</span>
                                                                @else
                                                                    <span class="badge bg-info-subtle text-info px-2 py-1">User</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if($item->status)
                                                                    <span class="badge bg-success-subtle text-success px-2 py-1">Hoạt động</span>
                                                                @else
                                                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1">Khóa</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center text-muted fs-12">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                                            <td class="text-center">
                                                                <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                                                    @hasPermission('user.edit')
                                                                    <a href="{{ route('admin.user.edit', $item->uuid) }}"
                                                                        class="action-btn-ghost action-edit" title="Chỉnh sửa">
                                                                        <i class="ri-pencil-line"></i>
                                                                    </a>
                                                                    @endhasPermission
                                                                    @hasPermission('user.delete')
                                                                    @if($item->level != 1 && $item->id != auth()->id())
                                                                    <button type="button" class="action-btn-ghost action-delete delete-item border-0 bg-transparent"
                                                                        data-uuid="{{ $item->uuid }}"
                                                                        data-name="{{ $item->fullname }}" title="Xóa">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </button>
                                                                    @endif
                                                                    @endhasPermission
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </form>
                                            @else
                                                <tr>
                                                    <td colspan="10" class="text-center py-4 text-muted">Không có dữ liệu</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                @if (count($list) > 0)
                                <div class="d-flex justify-content-end">
                                    <div class="pagination-wrap hstack gap-2" style="display: flex;">
                                        {{ $list->appends(request()->query())->links() }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div><!-- end row -->
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa người dùng <strong id="deleteUserName"></strong>?</p>
                    <p class="text-danger">Hành động này không thể hoàn tác!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Create User Modal -->
    <div class="modal fade" id="quickCreateModal" tabindex="-1" aria-labelledby="quickCreateModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-2 border-0 shadow">
                <div class="modal-header border-bottom py-3 px-4" style="background-color: #f8fafc;">
                    <div>
                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center" id="quickCreateModalLabel">
                            <i class="ri-flashlight-line text-primary me-2 fs-18"></i> Tạo nhanh tài khoản người dùng
                        </h5>
                        <p class="text-muted fs-12 mb-0 mt-1">Tự động sinh mật khẩu ngẫu nhiên an toàn, sao chép nhanh và gán vai trò quyền hạn</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="quickCreateUserForm" novalidate>
                    @csrf
                    <div class="modal-body p-4">
                        <div id="quickCreateErrorAlert" class="alert alert-danger d-none rounded-2 mb-3 py-2 px-3 fs-13"></div>

                        <!-- Row 1: Thông tin cơ bản -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="quick_fullname" class="form-label fw-semibold fs-13 mb-1">
                                    Họ và tên <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control rounded-2" id="quick_fullname" name="fullname"
                                    placeholder="Ví dụ: Nguyễn Văn An" style="height: 38px;" required>
                                <div class="invalid-feedback fs-12" id="err_quick_fullname"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="quick_username" class="form-label fw-semibold fs-13 mb-1">
                                    Tên đăng nhập <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control rounded-2" id="quick_username" name="username"
                                    placeholder="Ví dụ: nguyenvanan" style="height: 38px;" required>
                                <div class="invalid-feedback fs-12" id="err_quick_username"></div>
                            </div>
                        </div>

                        <!-- Row 2: Email & Số điện thoại -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="quick_email" class="form-label fw-semibold fs-13 mb-1">
                                    Email đăng nhập <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control rounded-2" id="quick_email" name="email"
                                    placeholder="Ví dụ: an.nguyen@gmail.com" style="height: 38px;" required>
                                <div class="invalid-feedback fs-12" id="err_quick_email"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="quick_phone" class="form-label fw-semibold fs-13 mb-1">
                                    Số điện thoại
                                </label>
                                <input type="text" class="form-control rounded-2" id="quick_phone" name="phone"
                                    placeholder="0xxxxxxxxx hoặc +84xxxxxxxxx" style="height: 38px;">
                                <div class="invalid-feedback fs-12" id="err_quick_phone"></div>
                            </div>
                        </div>

                        <!-- Row 3: Cấp độ & Trạng thái -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="quick_level" class="form-label fw-semibold fs-13 mb-1">
                                    Cấp độ tài khoản <span class="text-danger">*</span>
                                </label>
                                <select class="form-select rounded-2" id="quick_level" name="level" style="height: 38px;" required>
                                    <option value="3" selected>User (Người dùng / Khách hàng)</option>
                                    <option value="2">Staff (Nhân viên nội bộ)</option>
                                    <option value="1">Admin (Quản trị viên cấp cao)</option>
                                </select>
                                <div class="invalid-feedback fs-12" id="err_quick_level"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="quick_status" class="form-label fw-semibold fs-13 mb-1">
                                    Trạng thái hoạt động <span class="text-danger">*</span>
                                </label>
                                <select class="form-select rounded-2" id="quick_status" name="status" style="height: 38px;" required>
                                    <option value="1" selected>Hoạt động (Cho phép đăng nhập ngay)</option>
                                    <option value="0">Tạm khóa (Chưa kích hoạt)</option>
                                </select>
                                <div class="invalid-feedback fs-12" id="err_quick_status"></div>
                            </div>
                        </div>

                        <!-- Row 4: Mật khẩu ngẫu nhiên & Sao chép -->
                        <div class="p-3 rounded-2 mb-3 border" style="background-color: #f8fafc;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label for="quick_password" class="form-label fw-semibold fs-13 mb-0 text-dark">
                                    <i class="ri-key-2-line text-warning me-1"></i> Mật khẩu tự động (Random Password) <span class="text-danger">*</span>
                                </label>
                                <span class="badge bg-success-subtle text-success fs-11 px-2 py-1">Độ bảo mật cao</span>
                            </div>

                            <div class="input-group">
                                <input type="text" class="form-control font-monospace fw-semibold fs-14 bg-white" id="quick_password" name="password"
                                    placeholder="Mật khẩu tạo ngẫu nhiên" style="height: 38px; letter-spacing: 0.5px;" required>
                                <button class="btn btn-outline-secondary d-flex align-items-center px-3" type="button" id="btnToggleQuickPassword" title="Ẩn/Hiện mật khẩu">
                                    <i class="ri-eye-line fs-15"></i>
                                </button>
                                <button class="btn btn-outline-primary d-flex align-items-center px-3" type="button" id="btnRegeneratePassword" title="Tạo mật khẩu ngẫu nhiên khác">
                                    <i class="ri-refresh-line fs-15 me-1"></i> Đổi pass
                                </button>
                                <button class="btn btn-primary d-flex align-items-center px-3" type="button" id="btnCopyQuickPassword" title="Sao chép mật khẩu vào Clipboard">
                                    <i class="ri-file-copy-line fs-15 me-1"></i> <span id="copyPassBtnText">Copy pass</span>
                                </button>
                            </div>
                            <div class="invalid-feedback fs-12" id="err_quick_password"></div>
                            <div class="form-text fs-11 text-muted mt-1">
                                Mật khẩu gồm chữ hoa, chữ thường, chữ số và ký tự đặc biệt được sinh tự động. Bạn có thể bấm <strong>"Copy pass"</strong> để gửi ngay cho người dùng.
                            </div>
                        </div>

                        <!-- Row 5: Phân quyền / Vai trò (Roles) -->
                        <div class="border rounded-2 p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                <div>
                                    <label class="form-label fw-semibold fs-13 mb-0 text-dark">
                                        <i class="ri-shield-user-line text-info me-1"></i> Phân quyền / Gán vai trò (Roles)
                                    </label>
                                    <span class="text-muted fs-11 d-block">Tích chọn các vai trò phù hợp để cấp quyền thao tác trên hệ thống</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-light border py-1 px-2 fs-11 text-muted" id="btnSelectAllRoles">
                                        Chọn tất cả
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light border py-1 px-2 fs-11 text-muted" id="btnDeselectAllRoles">
                                        Bỏ chọn
                                    </button>
                                </div>
                            </div>

                            @if(isset($roles) && count($roles) > 0)
                                <div class="row g-2" style="max-height: 180px; overflow-y: auto;">
                                    @foreach($roles as $role)
                                        <div class="col-md-6">
                                            <div class="form-check p-2 border rounded-2 h-100 bg-white role-checkbox-item">
                                                <input class="form-check-input ms-0 me-2 quick-role-checkbox" type="checkbox"
                                                    name="roles[]" value="{{ $role->id }}" id="quick_role_{{ $role->id }}">
                                                <label class="form-check-label w-100 cursor-pointer" for="quick_role_{{ $role->id }}">
                                                    <span class="fw-semibold fs-13 text-dark d-block">{{ $role->display_name ?: $role->name }}</span>
                                                    @if(!empty($role->description))
                                                        <span class="text-muted fs-11 d-block text-truncate" title="{{ $role->description }}">{{ $role->description }}</span>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-2 text-muted fs-12 fst-italic">
                                    Chưa có vai trò nào được khởi tạo trong hệ thống.
                                </div>
                            @endif
                            <div class="invalid-feedback fs-12" id="err_quick_roles"></div>
                        </div>
                    </div>

                    <div class="modal-footer border-top py-2 px-4 bg-light d-flex justify-content-between align-items-center">
                        <span class="text-muted fs-11">
                            <i class="ri-information-line me-1"></i> Tài khoản sau khi tạo sẽ hiển thị ngay tại bảng danh sách.
                        </span>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light rounded-2 px-3" data-bs-dismiss="modal" style="height: 38px;">Hủy bỏ</button>
                            <button type="submit" id="btnSubmitQuickCreate" class="btn btn-primary rounded-2 px-4 d-inline-flex align-items-center justify-content-center" style="height: 38px; font-weight: 500;">
                                <i class="ri-check-line fs-16 me-1"></i> Tạo tài khoản
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Quick Create Success Modal -->
    <div class="modal fade" id="quickCreateSuccessModal" tabindex="-1" aria-labelledby="quickCreateSuccessLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-2 border-0 shadow">
                <div class="modal-header border-bottom py-3 px-4 bg-success-subtle">
                    <h5 class="modal-title fw-bold text-success d-flex align-items-center mb-0" id="quickCreateSuccessLabel">
                        <i class="ri-checkbox-circle-fill fs-20 me-2"></i> Tạo tài khoản thành công!
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted fs-13 mb-3">Tài khoản người dùng đã được kích hoạt thành công. Vui lòng sao chép lại thông tin đăng nhập để cung cấp cho người dùng:</p>

                    <div class="table-responsive border rounded-2 mb-3 bg-light p-3">
                        <table class="table table-sm table-borderless mb-0 fs-13">
                            <tbody>
                                <tr>
                                    <td class="text-muted fw-semibold" style="width: 130px;">Họ và tên:</td>
                                    <td class="fw-bold text-dark" id="success_res_fullname">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Tên đăng nhập:</td>
                                    <td class="font-monospace fw-bold text-primary" id="success_res_username">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Email:</td>
                                    <td class="text-dark" id="success_res_email">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Số điện thoại:</td>
                                    <td class="text-dark" id="success_res_phone">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Mật khẩu:</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="font-monospace fw-bold text-danger fs-14 bg-white px-2 py-1 border rounded" id="success_res_password">-</span>
                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 fs-11" id="btnCopySuccessPassOnly" title="Sao chép mật khẩu">
                                                <i class="ri-file-copy-line me-1"></i> <span id="copyOnlyPassBtnText">Chép pass</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Cấp độ:</td>
                                    <td id="success_res_level">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Vai trò (Quyền):</td>
                                    <td id="success_res_roles">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary d-flex align-items-center justify-content-center py-2 rounded-2 fw-semibold" id="btnCopyAllUserInfo">
                            <i class="ri-file-copy-2-line fs-16 me-2"></i> <span id="btnCopyAllText">Sao chép toàn bộ thông tin đăng nhập</span>
                        </button>
                    </div>
                </div>
                <div class="modal-footer border-top py-2 px-4 bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary rounded-2 px-3" id="btnCreateAnotherUser">
                        <i class="ri-user-add-line me-1"></i> Tạo tiếp tài khoản khác
                    </button>
                    <button type="button" class="btn btn-success rounded-2 px-4" data-bs-dismiss="modal" id="btnFinishAndReload">
                        <i class="ri-check-line me-1"></i> Hoàn tất & Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Check all functionality
    $('#checkAll').on('change', function() {
        $('.form-check-input[name="uuids[]"]').prop('checked', $(this).prop('checked'));
    });

    // Delete selected items
    $('#deleteSelectedItems').on('click', function() {
        const selectedItems = $('.form-check-input[name="uuids[]"]:checked');
        if (selectedItems.length === 0) {
            alert('Vui lòng chọn ít nhất một người dùng để xóa!');
            return;
        }

        if (confirm('Bạn có chắc chắn muốn xóa ' + selectedItems.length + ' người dùng đã chọn?')) {
            const form = $('#delete-form-all');
            form.find('input[name="uuids[]"]').remove();
            selectedItems.each(function() {
                form.append($('<input>').attr({
                    type: 'hidden',
                    name: 'uuids[]',
                    value: $(this).val()
                }));
            });
            form.submit();
        }
    });

    // Delete single item
    $('.delete-item').on('click', function() {
        const userUuid = $(this).data('uuid');
        const userName = $(this).data('name');

        $('#deleteUserName').text(userName);
        $('#deleteForm').attr('action', '{{ route("admin.user.destroy", ":uuid") }}'.replace(':uuid', userUuid));

        $('#deleteModal').modal('show');
    });

    // ==========================================
    // CHỨC NĂNG TẠO NHANH TÀI KHOẢN (QUICK CREATE)
    // ==========================================
    let lastCreatedUserInfo = null;

    // Hàm tạo ngẫu nhiên mật khẩu mạnh
    function generateStrongPassword(length = 10) {
        const uppercase = "ABCDEFGHJKLMNPQRSTUVWXYZ"; // loại bỏ I, O dễ nhầm
        const lowercase = "abcdefghijkmnpqrstuvwxyz"; // loại bỏ l, o dễ nhầm
        const numbers = "23456789";                   // loại bỏ 0, 1 dễ nhầm
        const symbols = "@#$%&*!";
        const allChars = uppercase + lowercase + numbers + symbols;

        let password = "";
        // Đảm bảo ít nhất mỗi loại 1 ký tự
        password += uppercase.charAt(Math.floor(Math.random() * uppercase.length));
        password += lowercase.charAt(Math.floor(Math.random() * lowercase.length));
        password += numbers.charAt(Math.floor(Math.random() * numbers.length));
        password += symbols.charAt(Math.floor(Math.random() * symbols.length));

        for (let i = password.length; i < length; i++) {
            password += allChars.charAt(Math.floor(Math.random() * allChars.length));
        }

        // Trộn ngẫu nhiên chuỗi
        return password.split('').sort(() => 0.5 - Math.random()).join('');
    }

    // Hàm chuyển đổi tiếng Việt có dấu thành không dấu để gợi ý username
    function removeVietnameseTones(str) {
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a"); 
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e"); 
        str = str.replace(/ì|í|ị|ỉ|ĩ/g,"i"); 
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o"); 
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u"); 
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y"); 
        str = str.replace(/đ/g,"d");
        str = str.replace(/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g, "A");
        str = str.replace(/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g, "E");
        str = str.replace(/Ì|Í|Ị|Ỉ|Ĩ/g, "I");
        str = str.replace(/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g, "O");
        str = str.replace(/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/g, "U");
        str = str.replace(/Ỳ|Ý|Ỵ|Ỷ|Ỹ/g, "Y");
        str = str.replace(/Đ/g, "D");
        str = str.replace(/[^a-zA-Z0-9]/g, "");
        return str.toLowerCase();
    }

    // Hàm copy to clipboard an toàn
    function copyTextToClipboard(text, onDone) {
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
        textArea.style.top = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            if (typeof onDone === 'function') onDone();
        } catch (err) {
            alert('Không thể sao chép, vui lòng sao chép thủ công.');
        }
        document.body.removeChild(textArea);
    }

    // Khi click mở Modal Tạo Nhanh: reset form và sinh pass ngẫu nhiên ngay
    $('#quickCreateModal').on('show.bs.modal', function () {
        $('#quickCreateUserForm')[0].reset();
        $('#quickCreateErrorAlert').addClass('d-none').html('');
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').html('');
        $('#quick_level').val('3');
        $('#quick_status').val('1');

        // Sinh mật khẩu ngẫu nhiên
        const randPass = generateStrongPassword(10);
        $('#quick_password').val(randPass).attr('type', 'text');
        $('#btnToggleQuickPassword i').removeClass('ri-eye-off-line').addClass('ri-eye-line');
    });

    // Nút đổi mật khẩu ngẫu nhiên khác
    $('#btnRegeneratePassword').on('click', function() {
        const randPass = generateStrongPassword(10);
        $('#quick_password').val(randPass);
        $(this).find('i').addClass('ri-spin');
        setTimeout(() => {
            $('#btnRegeneratePassword i').removeClass('ri-spin');
        }, 400);
    });

    // Nút Toggle ẩn/hiện mật khẩu trong Modal
    $('#btnToggleQuickPassword').on('click', function() {
        const input = $('#quick_password');
        const icon = $(this).find('i');
        if (input.attr('type') === 'text') {
            input.attr('type', 'password');
            icon.removeClass('ri-eye-line').addClass('ri-eye-off-line');
        } else {
            input.attr('type', 'text');
            icon.removeClass('ri-eye-off-line').addClass('ri-eye-line');
        }
    });

    // Nút Copy mật khẩu trong Modal
    $('#btnCopyQuickPassword').on('click', function() {
        const pass = $('#quick_password').val();
        if (!pass) return;

        copyTextToClipboard(pass, function() {
            const btnText = $('#copyPassBtnText');
            const original = btnText.text();
            btnText.text('Đã copy!');
            $('#btnCopyQuickPassword').removeClass('btn-primary').addClass('btn-success');
            setTimeout(function() {
                btnText.text(original);
                $('#btnCopyQuickPassword').removeClass('btn-success').addClass('btn-primary');
            }, 1500);
        });
    });

    // Tự động gợi ý username khi gõ fullname nếu username đang rỗng
    let usernameManuallyChanged = false;
    $('#quick_username').on('input', function() {
        if ($(this).val().trim() !== '') {
            usernameManuallyChanged = true;
        } else {
            usernameManuallyChanged = false;
        }
    });

    $('#quick_fullname').on('input', function() {
        if (!usernameManuallyChanged) {
            const suggested = removeVietnameseTones($(this).val());
            $('#quick_username').val(suggested);
        }
    });

    // Nút chọn tất cả / bỏ chọn roles
    $('#btnSelectAllRoles').on('click', function() {
        $('.quick-role-checkbox').prop('checked', true);
    });
    $('#btnDeselectAllRoles').on('click', function() {
        $('.quick-role-checkbox').prop('checked', false);
    });

    // Submit form Quick Create qua AJAX
    $('#quickCreateUserForm').on('submit', function(e) {
        e.preventDefault();

        // Clear error states
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').html('');
        $('#quickCreateErrorAlert').addClass('d-none').html('');

        const submitBtn = $('#btnSubmitQuickCreate');
        const originalBtnHtml = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Đang tạo...');

        const formData = $(this).serialize();

        $.ajax({
            url: '{{ route("admin.user.quick-store") }}',
            type: 'POST',
            data: formData,
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                submitBtn.prop('disabled', false).html(originalBtnHtml);

                if (response.success && response.data) {
                    lastCreatedUserInfo = response.data;

                    // Ẩn modal tạo nhanh
                    const quickModalEl = document.getElementById('quickCreateModal');
                    const quickModal = bootstrap.Modal.getInstance(quickModalEl);
                    if (quickModal) {
                        quickModal.hide();
                    }

                    // Điền thông tin vào modal thành công
                    $('#success_res_fullname').text(lastCreatedUserInfo.fullname || '-');
                    $('#success_res_username').text(lastCreatedUserInfo.username || '-');
                    $('#success_res_email').text(lastCreatedUserInfo.email || '-');
                    $('#success_res_phone').text(lastCreatedUserInfo.phone || '-');
                    $('#success_res_password').text(lastCreatedUserInfo.password || '-');
                    $('#success_res_level').html(`<span class="badge bg-${lastCreatedUserInfo.level == 1 ? 'danger' : (lastCreatedUserInfo.level == 2 ? 'warning' : 'info')}-subtle text-${lastCreatedUserInfo.level == 1 ? 'danger' : (lastCreatedUserInfo.level == 2 ? 'warning' : 'info')} px-2 py-1">${lastCreatedUserInfo.level_label}</span>`);

                    if (lastCreatedUserInfo.roles && lastCreatedUserInfo.roles.length > 0) {
                        const rolesHtml = lastCreatedUserInfo.roles.map(r => `<span class="badge bg-primary-subtle text-primary me-1 mb-1">${r}</span>`).join('');
                        $('#success_res_roles').html(rolesHtml);
                    } else {
                        $('#success_res_roles').html('<span class="text-muted fst-italic">Không có</span>');
                    }

                    // Mở modal thành công
                    const successModal = new bootstrap.Modal(document.getElementById('quickCreateSuccessModal'));
                    successModal.show();
                } else {
                    $('#quickCreateErrorAlert').removeClass('d-none').text(response.message || 'Có lỗi xảy ra.');
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalBtnHtml);

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        for (const key in errors) {
                            const inputField = $(`#quick_${key}`);
                            const errorContainer = $(`#err_quick_${key}`);
                            if (inputField.length) {
                                inputField.addClass('is-invalid');
                            }
                            if (errorContainer.length) {
                                errorContainer.html(errors[key][0]).show();
                            }
                        }
                    }
                } else {
                    const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Có lỗi xảy ra trong quá trình xử lý.';
                    $('#quickCreateErrorAlert').removeClass('d-none').text(msg);
                }
            }
        });
    });

    // Nút copy chỉ mật khẩu ở Modal Thành Công
    $('#btnCopySuccessPassOnly').on('click', function() {
        if (!lastCreatedUserInfo || !lastCreatedUserInfo.password) return;
        copyTextToClipboard(lastCreatedUserInfo.password, function() {
            const btnText = $('#copyOnlyPassBtnText');
            btnText.text('Đã chép!');
            setTimeout(() => btnText.text('Chép pass'), 1500);
        });
    });

    // Nút copy toàn bộ thông tin đăng nhập ở Modal Thành Công
    $('#btnCopyAllUserInfo').on('click', function() {
        if (!lastCreatedUserInfo) return;

        const rolesStr = (lastCreatedUserInfo.roles && lastCreatedUserInfo.roles.length > 0) 
            ? lastCreatedUserInfo.roles.join(', ') 
            : 'Mặc định';

        const fullInfoText = `==============================\n` +
            `THÔNG TIN TÀI KHOẢN NGƯỜI DÙNG\n` +
            `==============================\n` +
            `• Họ và tên: ${lastCreatedUserInfo.fullname}\n` +
            `• Tên đăng nhập: ${lastCreatedUserInfo.username}\n` +
            `• Email: ${lastCreatedUserInfo.email}\n` +
            `• Số điện thoại: ${lastCreatedUserInfo.phone}\n` +
            `• Mật khẩu: ${lastCreatedUserInfo.password}\n` +
            `• Cấp độ: ${lastCreatedUserInfo.level_label}\n` +
            `• Vai trò (Quyền): ${rolesStr}\n` +
            `• Đăng nhập tại: {{ url('/admin') }}\n` +
            `==============================`;

        copyTextToClipboard(fullInfoText, function() {
            const btn = $('#btnCopyAllUserInfo');
            const originalHtml = $('#btnCopyAllText').text();
            $('#btnCopyAllText').text('Đã sao chép toàn bộ thông tin!');
            btn.removeClass('btn-primary').addClass('btn-success');
            setTimeout(function() {
                $('#btnCopyAllText').text(originalHtml);
                btn.removeClass('btn-success').addClass('btn-primary');
            }, 2000);
        });
    });

    // Nút Tạo tiếp tài khoản khác
    $('#btnCreateAnotherUser').on('click', function() {
        const successModalEl = document.getElementById('quickCreateSuccessModal');
        const successModal = bootstrap.Modal.getInstance(successModalEl);
        if (successModal) {
            successModal.hide();
        }
        const quickModal = new bootstrap.Modal(document.getElementById('quickCreateModal'));
        quickModal.show();
    });

    // Khi đóng modal thành công -> reload trang để hiển thị user mới
    $('#quickCreateSuccessModal').on('hidden.bs.modal', function () {
        window.location.reload();
    });
});
</script>
@endpush