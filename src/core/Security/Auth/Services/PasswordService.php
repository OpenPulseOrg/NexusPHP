<?php

namespace Nxp\Core\Security\Auth\Services;

use Nxp\Core\Utils\Options\OptionsManager;
use Nxp\Core\Utils\Randomization\Generator;

class PasswordService
{
    private $optionsManager;

    public function __construct(OptionsManager $optionsManager)
    {
        $this->optionsManager = $optionsManager;
    }

    public function hash(string $password): string
    {
        $hashOptions = $this->optionsManager->getOption('passwordHashOptions', [
            'cost' => 12, // Default options if not set in OptionsManager
        ]);

        if (!$this->isStrongPassword($password)) {
            throw new \Exception("Password does not meet strength requirements.");
        }

        return password_hash($password, PASSWORD_DEFAULT, $hashOptions);
    }

    public function verify(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }

    public function needsRehash(string $hashedPassword): bool
    {
        $hashOptions = $this->optionsManager->getOption('passwordHashOptions', [
            'cost' => 12, // Default options if not set in OptionsManager
        ]);

        return password_needs_rehash($hashedPassword, PASSWORD_DEFAULT, $hashOptions);
    }

    public function isStrongPassword(string $password): bool
    {
        $minLength = $this->optionsManager->getOption('passwordMinLength', 8);

        if (strlen($password) < $minLength) {
            return false;
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }

        if (!preg_match('/\\d/', $password)) {
            return false;
        }

        if (!preg_match('/[@$!%*?&#]/', $password)) {
            return false;
        }

        return true;
    }

    public function generateSecurePassword(int $length = 12): string
    {
        return Generator::generatePassword(16);
    }

    public function generatePasswordResetToken(): string
    {
        return bin2hex(random_bytes(20));
    }

    public function validatePasswordResetToken(string $token): bool
    {
        return !empty($token);
    }
}
