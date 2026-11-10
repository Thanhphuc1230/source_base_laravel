<?php

namespace App\Repositories\Eloquent;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Interfaces\RoleRepositoryInterface;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getUsersWithRolesPaginated(?string $search = null, int $perPage = 15)
    {
        $query = User::query()->with('roles');

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function getAllRolesWithPermissions()
    {
        return $this->model->with('permissions')->orderBy('id')->get();
    }

    /**
     * @inheritDoc
     */
    public function getAllPermissionsOrdered()
    {
        return Permission::orderBy('group_name')->orderBy('display_name')->get();
    }

    /**
     * @inheritDoc
     */
    public function findRoleWithPermissionsOrFail($id): Role
    {
        return $this->model->with('permissions')->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function syncUserRolesAndPermissions(User $user, array $roleIds, array $permissionIds): void
    {
        $user->syncRoles($roleIds);

        $rolePermissionIds = Permission::whereHas('roles', function ($q) use ($roleIds) {
            $q->whereIn('id', $roleIds);
        })->pluck('id')->toArray();

        $directPermissions = array_diff($permissionIds, $rolePermissionIds);
        $user->syncDirectPermissions($directPermissions);
        $user->forgetCachedPermissions();
    }

    /**
     * @inheritDoc
     */
    public function bulkSyncUserRoles(array $userIds, array $roleIds): int
    {
        $users = User::whereIn('id', $userIds)->get();
        $successCount = 0;

        foreach ($users as $user) {
            $user->syncRoles($roleIds);
            $successCount++;
        }

        return $successCount;
    }
}
