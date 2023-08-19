<?php

namespace Nxp\Core\Utils\Session;

use Nxp\Core\Common\Patterns\Singleton;

/**
 * Class for managing session data.
 *
 * @package Nxp\Core\Utils\Session
 */
class Manager extends Singleton
{
    /**
     * @var bool Read-only flag for flash messages.
     */
    private $readOnly = false;

    /**
     * @var bool Global read-only flag for session.
     */
    private $globalReadOnly = false;

    /**
     * @var array<string> List of read-only keys in the session.
     */
    private $readOnlyKeys = [];

    /**
     * @var Manager|null Singleton instance of the Manager class
     */
    private static ?Manager $instance = null;

    /**
     * Starts a session, handles flash messages, and manages session timeout.
     *
     * @return void
     */
    public function start(): void
    {
        ob_start();
        if (session_status() === PHP_SESSION_NONE || session_id() == '') {
            session_start();
        }

        // Automatically delete old flash messages
        if (isset($_SESSION['_flash_keys'])) {
            foreach ($_SESSION['_flash_keys'] as $key => $count) {
                if ($count <= 1) {
                    unset($_SESSION["_flash_{$key}"], $_SESSION['_flash_keys'][$key]);
                } else {
                    $_SESSION['_flash_keys'][$key]--;
                }
            }
        }

        // Handle session timeout
        if (isset($_SESSION['_last_activity']) && (time() - $_SESSION['_last_activity'] > self::getSessionTimeout())) {
            self::destroy();
        }
        $_SESSION['_last_activity'] = time();
    }

    /**
     * Retrieves a value from the session.
     *
     * @param string $key     The key to retrieve.
     * @param mixed  $default The default value if the key doesn't exist.
     * @param string $segment The segment of the session.
     *
     * @return mixed
     */
    public function get(string $key, $default = null, string $segment = 'default')
    {
        return $_SESSION[$segment][$key] ?? $default;
    }

    /**
     * Sets a value in the session.
     *
     * @param string $key      The key to set.
     * @param mixed  $value    The value to set.
     * @param string $segment  The segment of the session.
     * @param bool   $readOnly Whether the key should be read-only.
     *
     * @return void
     */
    public function set(string $key, $value, string $segment = 'default', bool $readOnly = false): void
    {
        $_SESSION[$segment][$key] = $value;

        // Set the read-only state of the key
        $fullKey = $segment . '.' . $key;
        if ($readOnly) {
            $this->readOnlyKeys[] = $fullKey;
        } else {
            $this->readOnlyKeys = array_diff($this->readOnlyKeys, [$fullKey]);
        }
    }

    /**
     * Deletes a value from the session.
     *
     * @param string $key     The key to delete.
     * @param string $segment The segment of the session.
     *
     * @return void
     */
    public function delete(string $key, string $segment = 'default'): void
    {
        if ($this->globalReadOnly || in_array($segment . '.' . $key, $this->readOnlyKeys)) {
            return; // Skip deletion if global read-only or the key is marked as read-only
        }
        unset($_SESSION[$segment][$key]);
    }


    /**
     * Sets a flash message in the session.
     *
     * @param string $key        The key for the flash message.
     * @param mixed  $value      The value of the flash message.
     * @param int    $persistFor The number of requests to persist the message.
     *
     * @return void
     */
    public function flash(string $key, $value, int $persistFor = 1): void
    {
        if ($this->readOnly) return;
        $_SESSION["_flash_{$key}"] = $value;
        $_SESSION['_flash_keys'][$key] = $persistFor;
    }

    /**
     * Destroys the current session.
     *
     * @return void
     */
    public function destroy(): void
    {
        session_destroy();
    }

    /**
     * Dumps the session data.
     *
     * @return void
     */
    public function varDump(): void
    {
        var_dump($_SESSION);
    }

    /**
     * Regenerates the session ID.
     *
     * @return void
     */
    public function regenerateId(): void
    {
        if (session_status() !== PHP_SESSION_NONE) {
            session_regenerate_id(true);
        }
    }

    /**
     * Generates a CSRF token.
     *
     * @return string The generated CSRF token.
     */
    public function generateCsrfToken(): string
    {
        $token = bin2hex(random_bytes(32));
        self::set('_csrf_token', $token);
        return $token;
    }

    /**
     * Validates a given CSRF token.
     *
     * @param string $token The CSRF token to validate.
     *
     * @return bool Whether the token is valid.
     */
    public function validateCsrfToken(string $token): bool
    {
        return hash_equals(self::get('_csrf_token'), $token);
    }

    /**
     * Retrieves the current session ID.
     *
     * @return string The current session ID.
     */
    public function getSessionId(): string
    {
        return session_id();
    }

    /**
     * Retrieves the session timeout value.
     *
     * @return int The session timeout in seconds.
     */
    public function getSessionTimeout(): int
    {
        return (int)ini_get('session.gc_maxlifetime');
    }

    /**
     * Sets the global read-only state for the session.
     *
     * @param bool $readOnly The read-only state to set.
     *
     * @return void
     */
    public function setGlobalReadOnly(bool $readOnly): void
    {
        $this->globalReadOnly = $readOnly;
    }

    /**
     * Sets the read-only state for a specific key in the session.
     *
     * @param string $key     The key to set as read-only.
     * @param string $segment The segment of the session.
     * @param bool   $readOnly Whether the key should be read-only.
     *
     * @return void
     */
    public function setReadOnlyKey(string $key, string $segment = 'default', bool $readOnly = true): void
    {
        $fullKey = $segment . '.' . $key;
        if ($readOnly) {
            $this->readOnlyKeys[] = $fullKey;
        } else {
            $this->readOnlyKeys = array_diff($this->readOnlyKeys, [$fullKey]);
        }
    }
}
