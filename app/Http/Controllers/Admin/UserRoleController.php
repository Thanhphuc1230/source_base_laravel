<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    protected $nameItem = 'phân quyền người dùng';

    protected $nameClass = 'user-role';

    protected RoleRepositoryInterface $roleRepository;

    protected UserRepositoryInterface $userRepository;

    public function __construct(
        RoleRepositoryInterface $roleRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $list = $this->roleRepository->getUsersWithRolesPaginated($request->input('search'), 15);

        return view('admin.modules.user-role.list', compact('list'))
            ->with('nameItem', $this->nameItem)
            ->with('nameClass', $this->nameClass);
    }

    public function edit($id)
    {
        $user = $this->userRepository->findOrFail($id);
        $roles = $this->roleRepository->getAllRolesWithPermissions();
        $permissions = $this->roleRepository->getAllPermissionsOrdered();
        $permissionGroups = $permissions->groupBy('group_name');
        $userRoles = $user->roles->pluck('id')->toArray();
        $userDirectPermissions = $user->directPermissions->pluck('id')->toArray();

        return view('admin.modules.user-role.edit', compact(
            'user', 'roles', 'permissions', 'permissionGroups',
            'userRoles', 'userDirectPermissions'
        ))
            ->with('nameItem', $this->nameItem)
            ->with('nameClass', $this->nameClass);
    }

    public function updateRoles(Request $request, $id)
    {
        $user = $this->userRepository->findOrFail($id);
        $roles = $request->input('roles', []);
        $permissions = $request->input('permissions', []);

        $this->roleRepository->syncUserRolesAndPermissions($user, $roles, $permissions);

        toast('Cập nhật phân quyền thành công!', 'success');

        return redirect()->route('admin.user-role.index');
    }

    public function bulkUpdateRoles(Request $request)
    {
        $userIds = $request->input('user_ids', []);
        $roles = $request->input('roles', []);

        if (empty($userIds)) {
            toast('Vui lòng chọn người dùng!', 'error');

            return redirect()->back();
        }

        if (empty($roles)) {
            toast('Vui lòng chọn vai trò!', 'error');

            return redirect()->back();
        }

        $successCount = $this->roleRepository->bulkSyncUserRoles($userIds, $roles);

        if ($successCount > 0) {
            toast('Cập nhật phân quyền cho ' . $successCount . ' người dùng thành công!', 'success');
        } else {
            toast('Không có người dùng nào được cập nhật!', 'warning');
        }

        return redirect()->route('admin.user-role.index');
    }

    public function roleIndex()
    {
        $roles = $this->roleRepository->getAllRolesWithPermissions();

        return view('admin.modules.user-role.roles.index', compact('roles'))
            ->with('nameItem', 'quản lý vai trò')
            ->with('nameClass', 'role-management');
    }

    public function roleCreate()
    {
        $permissions = $this->roleRepository->getAllPermissionsOrdered();
        $permissionGroups = $permissions->groupBy('group_name');

        return view('admin.modules.user-role.roles.create', compact('permissions', 'permissionGroups'))
            ->with('nameItem', 'tạo vai trò mới')
            ->with('nameClass', 'role-create');
    }

    public function roleStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tp_roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array',
        ]);

        $role = $this->roleRepository->create($request->only(['name', 'display_name', 'description']));

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        toast('Tạo vai trò thành công!', 'success');

        return redirect()->route('admin.user-role.roles.index');
    }

    public function roleEdit($id)
    {
        $role = $this->roleRepository->findRoleWithPermissionsOrFail($id);
        $permissions = $this->roleRepository->getAllPermissionsOrdered();
        $permissionGroups = $permissions->groupBy('group_name');
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.modules.user-role.roles.edit', compact('role', 'permissions', 'permissionGroups', 'rolePermissions'))
            ->with('nameItem', 'chỉnh sửa vai trò')
            ->with('nameClass', 'role-edit');
    }

    public function roleUpdate(Request $request, $id)
    {
        $role = $this->roleRepository->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:tp_roles,name,' . $id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array',
        ]);

        $role->update($request->only(['name', 'display_name', 'description']));

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        toast('Cập nhật vai trò thành công!', 'success');

        return redirect()->route('admin.user-role.roles.index');
    }

    public function roleDestroy($id)
    {
        $role = $this->roleRepository->findOrFail($id);

        if ($role->users()->count() > 0) {
            toast('Không thể xóa vai trò này vì đang được sử dụng!', 'error');

            return redirect()->back();
        }

        $role->delete();
        toast('Xóa vai trò thành công!', 'success');

        return redirect()->route('admin.user-role.roles.index');
    }
}
