<?php

namespace Nxp\Core\Utils\Session;

/**
 * Session class for managing session data.
 *
 * @package Nxp\Core\Utils\SessionManagement
 */
class Session
{

  /**
   * Starts the session and output buffering.
   *
   * @return void
   */
  public static function start()
  {
    ob_start(); // Start output buffering
    if (session_status() === PHP_SESSION_NONE || session_id() == '') {
      session_start();
    }
  }

  /**
   * Sets a value in the session.
   *
   * @param string $key The key to set.
   * @param mixed $value The value to set.
   *
   * @return void
   */
  public static function set($key, $value)
  {
    self::start();
    $_SESSION[$key] = $value;
  }

  /**
   * Gets a value from the session.
   *
   * @param string $key The key to get.
   *
   * @return mixed|null The value associated with the key or null if it does not exist.
   */
  public static function get($key)
  {
    self::start();
    return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
  }

  /**
   * Deletes a key and its associated value from the session.
   *
   * @param string $key The key to delete.
   *
   * @return void
   */
  public static function delete($key)
  {
    self::start();
    if (isset($_SESSION[$key])) {
      unset($_SESSION[$key]);
    }
  }

  /**
   * Destroys the session.
   *
   * @return void
   */
  public static function destroy()
  {
    self::start();
    session_destroy();
  }

  /**
   * Dumps the contents of the session.
   *
   * @return void
   */
  public static function varDump()
  {
    self::start();
    var_dump($_SESSION);
  }
}
