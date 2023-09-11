<?php

namespace Nxp\Core\Security\Auth\Repositories;

use Nxp\Core\Database\Query;

class RoleRepository
{
    private $query;
    private $rolesTable = 'roles';
    private $userRolesTable = 'user_roles';
    private $permissionsTable = 'permissions';
    private $rolePermissionsTable = 'role_permissions';

    public function __construct()
    {
        // Assuming the container provides a way to initialize the database connection
        // If not, you might want to adjust this initialization
        $this->query = new Query();
    }

    public function getRolesForUser($userId)
    {
        return $this->query
            ->select($this->userRolesTable, 'role_id')
            ->where('user_id = :userId', [':userId' => $userId])
            ->fetchAll();
    }

    public function getPermissionsForRole($roleId)
    {
        return $this->query
            ->select($this->rolePermissionsTable, 'permission_id')
            ->where('role_id = :roleId', [':roleId' => $roleId])
            ->fetchAll();
    }

    // Additional methods related to roles and permissions can be added here as required
}
