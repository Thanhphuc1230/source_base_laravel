<?php

namespace App\Repositories\Interfaces;

use App\Models\Role;
use App\Models\User;

interface RoleRepositoryInterface extends RepositoryInterface
{
    /**
     * Get paginated users with roles for RBAC listing.
     */
    public function getUsersWithRolesPaginated(?string $search = null, int $perPage = 15);

    /**
     * Get all roles with their permissions eager-loaded.
     */
    public function getAllRolesWithPermissions();

    /**
     * Get all permissions ordered by group_name and display_name.
     */
    public function getAllPermissionsOrdered();

    /**
     * Find role with permissions or fail.
     */
    public function findRoleWithPermissionsOrFail($id): Role;

    /**
     * Sync user roles and direct permissions in a single workflow.
     */
    public function syncUserRolesAndPermissions(User $user, array $roleIds, array $permissionIds): void;

    /**
     * Bulk sync roles for multiple user IDs without N+1 queries.
     */
    public function bulkSyncUserRoles(array $userIds, array $roleIds): int;
}
