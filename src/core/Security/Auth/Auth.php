<?php

namespace Nxp\Core\Security\Auth;

use Nxp\Core\Utils\Service\Container\Container;

class Auth
{
    private $authentication;
    private $authorization;

    /**
     * Auth constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->authentication = new Authentication($container);
        $this->authorization = new Authorization($container);
    }

    /**
     * Authenticates a user based on the provided credentials.
     *
     * @param string $username The user's username.
     * @param string $password The user's password.
     * @return array|bool Returns the user's data on successful authentication or false on failure.
     */
    public function login($username, $password)
    {
        return $this->authentication->authenticate($username, $password);
    }

    /**
     * Registers a new user.
     *
     * @param string $username The new user's username.
     * @param string $password The new user's password.
     * @param array $userData Additional user data (e.g., email, roles, etc.).
     * @return bool Returns true on successful registration or false on failure.
     */
    public function register($userData = [])
    {
        return $this->authentication->register($userData);
    }

    /**
     * Changes the password for a user.
     *
     * @param string $username The user's username.
     * @param string $newPassword The new password for the user.
     * @return bool Returns true on successful password change or false on failure.
     */
    public function changePassword($username, $newPassword)
    {
        return $this->authentication->changePassword($username, $newPassword);
    }

    /**
     * Checks if a user with a specific username or email already exists.
     *
     * @param string $username The username to check.
     * @param string|null $email The email to check (optional).
     * @return bool Returns true if the user already exists, false otherwise.
     */
    public function userExists($username, $email = null)
    {
        return $this->authentication->userExists($username, $email);
    }

    /**
     * Checks if a user is logged in.
     *
     * @return bool Returns true if a user is logged in, false otherwise.
     */
    public function isLoggedIn()
    {
        return $this->authentication->isLoggedIn();
    }

    /**
     * Logs out the currently logged-in user.
     */
    public function logout()
    {
        $this->authentication->logout();
    }

    /**
     * Check if a user has a specific role.
     *
     * @param array|string $roles A single role or an array of roles to check against.
     * @return bool Returns true if the user has the role(s), false otherwise.
     */
    public function hasRole($roles)
    {
        return $this->authorization->hasRole($roles);
    }

    /**
     * Check if a user has a specific permission.
     *
     * @param string $permission The permission to check for.
     * @return bool Returns true if the user has the permission, false otherwise.
     */
    public function hasPermission($permission)
    {
        return $this->authorization->hasPermission($permission);
    }

    /**
     * Get the currently logged-in user.
     *
     * @return array|null Returns the user data if logged in, null otherwise.
     */
    public function getCurrentUser()
    {
        return $this->authorization->getCurrentUser();
    }

    /**
     * Set the user data for the currently logged-in user.
     *
     * @param array $userData The user data to set.
     */
    public function setCurrentUser($userData)
    {
        $this->authorization->setCurrentUser($userData);
    }

    /**
     * Clear the user data for the currently logged-in user (log out).
     */
    public function clearCurrentUser()
    {
        $this->authorization->clearCurrentUser();
    }

    /**
     * Grant permissions to the currently logged-in user.
     *
     * @param array $permissions The permissions to grant.
     * @return bool Returns true on successful update or false on failure.
     */
    public function grantPermissions($permissions = [])
    {
        $user = $this->authorization->getCurrentUser();
        if (!$user) {
            return false;
        }

        return $this->authorization->grantPermissions($user['username'], $permissions);
    }

    /**
     * Revoke permissions from the currently logged-in user.
     *
     * @param array $permissions The permissions to revoke.
     * @return bool Returns true on successful update or false on failure.
     */
    public function revokePermissions($permissions = [])
    {
        $user = $this->authorization->getCurrentUser();
        if (!$user) {
            return false;
        }

        return $this->authorization->revokePermissions($user['username'], $permissions);
    }
}
