<?php

namespace Nxp\Core\Security\Auth;

use Nxp\Core\Database\Factories\Query;
use Nxp\Core\Database\Factories\Table;
use Nxp\Core\Database\Factories\Transaction;
use Nxp\Core\Utils\Service\Container;
use Nxp\Core\Utils\Session\Manager;

class Authentication
{
    private $query;
    private $table;
    private $transaction;
    private $session;
    private $usersTable = 'users';

    /**
     * @return Authentication
     */
    public function __construct(Container $container)
    {
        $this->query = new Query($container);
        $this->table = new Table($container);
        $this->transaction = new Transaction($container);
        $this->session = Manager::getInstance();
    }

    public function createUsersTableIfNotExists()
    {
        try {
            // Begin the transaction
            $this->transaction->beginTransaction();

            if (!$this->table->tableExists($this->usersTable)) {
                // Create the users table if it doesn't exist
                $this->table->createTable($this->usersTable, [
                    'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
                    'username' => 'VARCHAR(255) NOT NULL',
                    'password' => 'VARCHAR(255) NOT NULL',
                    // Add more columns as needed, e.g., email, etc.
                ]);
            }

            // Create the user_details table if it doesn't exist
            $this->table->createTable('user_details', [
                'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
                'user_id' => 'INT NOT NULL',
                'first_name' => 'VARCHAR(255) NOT NULL',
                'last_name' => 'VARCHAR(255) NOT NULL',
                'email' => 'VARCHAR(255) NOT NULL',
            ]);

            // Add foreign key constraint to link user_details table with users table
            $this->table->addForeignKey('user_details', 'user_id', 'users', 'id', 'CASCADE', 'CASCADE');

            // Create the user_roles table if it doesn't exist
            $this->table->createTable('user_roles', [
                'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
                'user_id' => 'INT NOT NULL',
                'role' => 'VARCHAR(50) NOT NULL',
            ]);

            // Add foreign key constraint to link user_roles table with users table
            $this->table->addForeignKey('user_roles', 'user_id', 'users', 'id', 'CASCADE', 'CASCADE');

            // Create the user_permissions table if it doesn't exist
            $this->table->createTable('user_permissions', [
                'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
                'user_id' => 'INT NOT NULL',
                'permission' => 'VARCHAR(50) NOT NULL',
            ]);

            // Add foreign key constraint to link user_permissions table with users table
            $this->table->addForeignKey('user_permissions', 'user_id', 'users', 'id', 'CASCADE', 'CASCADE');

            // Commit the transaction
            $this->transaction->commitTransaction();
        } catch (\Exception $e) {
            // Rollback the transaction in case of an exception
            $this->transaction->rollbackTransaction();
            throw $e;
        }
    }




    /**
     * Authenticates a user based on the provided credentials.
     *
     * @param string $username The user's username.
     * @param string $password The user's password.
     * @return array|bool Returns the user's data on successful authentication or false on failure.
     */
    public function authenticate($username, $password)
    {
        // Retrieve user data from the database based on the provided username
        $user = $this->query->select($this->usersTable, '*', ['username' => $username])->first();

        if ($user && $user['password'] === $password) {
            // Authentication successful
            $this->session->set('user', $user); // Set the user data in the session
            return $user;
        }

        // Authentication failed
        return false;
    }

    /**
     * TO-DO
     * Registers a new user.
     *
     * @param array $userData An array containing the new user's data, including username, password, first name, last name, email, and user roles.
     * @return bool Returns true on successful registration or false on failure.
     */
    public function register($userData = [])
    {
        // Check if a user is already logged in
        if ($this->isLoggedIn()) {
            return false; // Or throw an exception if registration is not allowed for authenticated users.
        }


        // Check if required user data is present
        $requiredFields = ['username', 'password', 'first_name', 'last_name', 'email'];
        foreach ($requiredFields as $field) {
            if (!isset($userData[$field])) {
                throw new \InvalidArgumentException("Missing required user data: $field");
            }
        }

        // Hash the password using a secure algorithm (e.g., bcrypt)
        $hashedPassword = password_hash($userData['password'], PASSWORD_BCRYPT);

        // Prepare the user data for insertion into the 'users' table
        $userDataToInsert = [
            'username' => $userData['username'],
            'password' => $hashedPassword,
        ];

        // Insert the user details into the 'user_details' table
        $userDetailsData = [
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'email' => $userData['email'],
        ];

        // After inserting the user data into the 'users' table, retrieve the last inserted ID
        $userId = $this->query->insert('users', $userDataToInsert);

        // Insert the user details into the 'user_details' table using the correct user_id value
        $userDetailsData['user_id'] = $userId;
        $this->query->insert('user_details', $userDetailsData);


        // Insert the new user into the 'users' table
        $result = $this->query->insert($this->usersTable, $userDataToInsert);

        if ($result) {
            // Insert user roles into the 'user_roles' table
            foreach ($userData['roles'] as $role) {
                $this->query->insert('user_roles', ['user_id' => $userId, 'role' => $role]);
            }

            // Registration successful, set the user data in the session
            $this->session->set('user', $userDataToInsert);
        } else {
            // Registration failed, handle errors appropriately
        }

        return $result;
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
        // Check if the user exists
        $user = $this->query->select($this->usersTable, '*', ['username' => $username])->first();
        if (!$user) {
            return false;
        }

        // Hash the new password before updating it
        // In production, you should use a secure hashing algorithm like bcrypt.
        $hashedNewPassword = $newPassword;

        // Update the password in the database
        $result = $this->query->update($this->usersTable, ['password' => $hashedNewPassword], ['username' => $username]);

        if ($result) {
            // Password change successful, update the user data in the session
            $this->session->set('user', $user);
        }

        return $result;
    }

    /**
     * Updates user roles.
     *
     * @param string $username The user's username.
     * @param array $roles The roles to assign to the user.
     * @return bool Returns true on successful update or false on failure.
     */
    public function updateRoles($username, $roles = [])
    {
        // Update roles in the database
        $result = $this->query->update($this->usersTable, ['roles' => $roles], ['username' => $username]);

        if ($result) {
            // Update successful, update the user data in the session
            $user = $this->session->get('user');
            $user['roles'] = $roles;
            $this->session->set('user', $user);
        }

        return $result;
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
        $conditions = ['username' => $username];
        if ($email !== null) {
            $conditions['email'] = $email;
        }

        // Check if any user matches the provided username or email
        return $this->query->select($this->usersTable, 'id', $conditions)->count() > 0;
    }

    /**
     * Checks if a user is logged in.
     *
     * @return bool Returns true if a user is logged in, false otherwise.
     */
    public function isLoggedIn()
    {
        return $this->session->get('user') !== null;
    }

    /**
     * Logs out the currently logged-in user.
     */
    public function logout()
    {
        $this->session->destroy();
    }



    /**
     * Retrieve user roles from the database.
     *
     * @param int $userId The user ID.
     * @return array Returns an array of user roles.
     */
    private function getUserRoles($userId)
    {
        // Replace this with your logic to fetch user roles from the database
        // For example, you might have a separate roles table and a user_roles table
        // that links users to their roles.
        $roles = ['user']; // Default role for all users

        // Fetch user roles from the database and return them
        return $roles;
    }

    /**
     * Retrieve user permissions from the database.
     *
     * @param int $userId The user ID.
     * @return array Returns an array of user permissions.
     */
    private function getUserPermissions($userId)
    {
        // Replace this with your logic to fetch user permissions from the database
        // For example, you might have a separate permissions table and a user_permissions table
        // that links users to their permissions.
        $permissions = []; // Default permissions for all users

        // Fetch user permissions from the database and return them
        return $permissions;
    }
}
