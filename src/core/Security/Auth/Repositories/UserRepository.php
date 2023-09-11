<?php

namespace Nxp\Core\Security\Auth\Repositories;

use Nxp\Core\Database\Query;

class UserRepository
{
    private $query;
    private $usersTable = 'users';
    private $userRolesTable = 'user_roles';
    
    public function __construct()
    {
        // Assuming the container provides a way to initialize the database connection
        // If not, you might want to adjust this initialization
        $this->query = new Query();
    }

    public function findUserByUsername($username)
    {
        return $this->query
            ->select($this->usersTable)
            ->where('username = :username', [':username' => $username])
            ->fetch();
    }

    public function createUser($userData)
    {
        return $this->query->insert($this->usersTable, $userData);
    }

    public function updateUser($username, $data)
    {
        return $this->query
            ->update($this->usersTable, $data)
            ->where('username = :username', [':username' => $username])
            ->execute();
    }

    public function doesUserExist($username, $email = null)
    {
        $result = $this->query
            ->select($this->usersTable)
            ->where('username = :username OR email = :email', [':username' => $username, ':email' => $email])
            ->fetch();

        return $result !== false;
    }
    public function getUserById($userId)
    {
        return $this->query
            ->select($this->usersTable)
            ->where('id = :userId', [':userId' => $userId])
            ->fetch();
    }

    public function deleteUser($userId)
    {
        return $this->query
            ->delete($this->usersTable)
            ->where('id = :userId', [':userId' => $userId])
            ->execute();
    }

    public function getAllUsers()
    {
        return $this->query
            ->select($this->usersTable)
            ->fetchAll();
    }

    public function getUserByEmail($email)
    {
        return $this->query
            ->select($this->usersTable)
            ->where('email = :email', [':email' => $email])
            ->fetch();
    }

    public function assignRoleToUser($userId, $roleId)
    {
        return $this->query
            ->insert($this->userRolesTable, ['user_id' => $userId, 'role_id' => $roleId]);
    }
}
