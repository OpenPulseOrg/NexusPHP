<?php

namespace Nxp\Core\Security\Auth;

use Nxp\Core\Security\Auth\Repositories\UserRepository;
use Nxp\Core\Security\Auth\Services\TokenService;
use Nxp\Core\Security\Auth\Services\PasswordService;

class Auth
{
    private $userRepository;
    private $tokenService;
    private $passwordService;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->tokenService = new TokenService();
        $this->passwordService = new PasswordService();
    }

    public function register($username, $email, $password)
    {
        if ($this->userRepository->doesUserExist($username, $email)) {
            throw new \Exception("User with that username or email already exists.");
        }

        $hashedPassword = $this->passwordService->hash($password);
        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword
        ];

        return $this->userRepository->createUser($userData);
    }

    public function login($username, $password)
    {
        $user = $this->userRepository->findUserByUsername($username);

        if (!$user) {
            throw new \Exception("User not found.");
        }

        if ($this->passwordService->verify($password, $user['password'])) {
            // Create a token for the user
            $token = $this->tokenService->generateToken(['id' => $user['id'], 'username' => $username]);
            return $token;
        } else {
            throw new \Exception("Invalid password.");
        }
    }

    public function logout($token)
    {
        // Blacklist the token
        $this->tokenService->blacklistToken($token);
    }

    // Additional methods for password reset, email verification, etc. can be added here as needed
}
