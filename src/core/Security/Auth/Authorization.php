<?php

namespace Nxp\Core\Security\Auth;

use Nxp\Core\Database\Factories\Query;
use Nxp\Core\Security\Cleaning\Sanitizer;
use Nxp\Core\Utils\Service\Container;
use Nxp\Core\Utils\Session\Manager;

class Authorization
{
    private $query;
    private $session;
    private $usersTable = 'users';

    /**
     * @return Authentication
     */
    public function __construct(Container $container)
    {
        $this->session = Manager::getInstance();
        $this->query = new Query($container);
    }

    /**
     * Check if a user has a specific role.
     *
     * @param array|string $roles A single role or an array of roles to check against.
     * @return bool Returns true if the user has the role(s), false otherwise.
     */
    public function hasRole($roles)
    {
        $user = $this->getCurrentUser();
        if (!$user || !isset($user['roles']) || empty($user['roles'])) {
            return false;
        }

        if (!is_array($roles)) {
            $roles = [$roles];
        }

        foreach ($roles as $role) {
            if (in_array($role, $user['roles'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a user has a specific permission.
     *
     * @param string $permission The permission to check for.
     * @return bool Returns true if the user has the permission, false otherwise.
     */
    public function hasPermission($permission)
    {
        $user = $this->getCurrentUser();
        if (!$user || !isset($user['permissions']) || empty($user['permissions'])) {
            return false;
        }

        return in_array($permission, $user['permissions']);
    }

    /**
     * Get the currently logged-in user.
     *
     * @return array|null Returns the user data if logged in, null otherwise.
     */
    public function getCurrentUser()
    {
        $user = $this->session->get('user');
        // Validate and sanitize user data before returning it to prevent security issues.
        return $this->validateUserData($user);
    }

    /**
     * Validate and sanitize user data to prevent security issues.
     *
     * @param array|null $userData The user data to validate.
     * @return array|null Returns the validated and sanitized user data.
     */
    private function validateUserData($userData)
    {
        if (!$userData || !is_array($userData)) {
            return null;
        }

        // Sanitize user data to prevent HTML tags and special characters
        $sanitizedUserData = Sanitizer::sanitizeInput($userData, false);

        // Additional validation and sanitization can be added here if needed

        // Return the validated and sanitized user data
        return $sanitizedUserData;
    }

    /**
     * Set the user data for the currently logged-in user.
     *
     * @param array $userData The user data to set.
     */
    public function setCurrentUser($userData)
    {
        $this->session->set('user', $userData);
    }

    /**
     * Clear the user data for the currently logged-in user (log out).
     */
    public function clearCurrentUser()
    {
        $this->session->delete('user');
    }

    /**
     * Grant permissions to a user.
     *
     * @param string $username The user's username.
     * @param array $permissions The permissions to grant.
     * @return bool Returns true on successful update or false on failure.
     */
    public function grantPermissions($username, $permissions = [])
    {
        // Update permissions in the database
        $result = $this->query->update($this->usersTable, ['permissions' => $permissions], ['username' => $username]);

        if ($result) {
            // Update successful, update the user data in the session
            $user = $this->session->get('user');
            $user['permissions'] = $permissions;
            $this->session->set('user', $user);
        }

        return $result;
    }

    /**
     * Revoke permissions from a user.
     *
     * @param string $username The user's username.
     * @param array $permissions The permissions to revoke.
     * @return bool Returns true on successful update or false on failure.
     */
    public function revokePermissions($username, $permissions = [])
    {
        // Revoke permissions in the database
        $user = $this->query->select($this->usersTable, ['permissions'], ['username' => $username])->first();
        if (!$user) {
            return false;
        }

        $updatedPermissions = array_diff($user['permissions'], $permissions);

        $result = $this->query->update($this->usersTable, ['permissions' => $updatedPermissions], ['username' => $username]);

        if ($result) {
            // Update successful, update the user data in the session
            $user['permissions'] = $updatedPermissions;
            $this->session->set('user', $user);
        }

        return $result;
    }
}
