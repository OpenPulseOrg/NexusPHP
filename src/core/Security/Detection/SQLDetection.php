<?php

namespace Nxp\Core\Security\Detection;

/**
 * Class SQLDetection
 * 
 * This class is responsible for detecting possible SQL injection attempts in various parts of the application, such as URL, POST data, GET data, headers, and cookies.
 */
class SQLDetection
{
    /**
     * List of dangerous SQL commands to be checked against the input string.
     *
     * @var array
     */
    private $sqlCommands = array("select", "drop", "update", "delete", "insert", "union", "--", "null", "like", "where");

    /**
     * List of dangerous special characters to be checked against the input string.
     *
     * @var array
     */
    private $specialCharacters = array("=", "<", ">", "\\");

    /**
     * Scan the given string for dangerous SQL commands and special characters.
     *
     * @param string $string The input string to be scanned for SQL injection attempts.
     * @return bool True if any dangerous SQL command or special character is found, otherwise false.
     */
    private function scan($string)
    {
        $string = urldecode($string);

        foreach ($this->sqlCommands as $sqlCommand) {
            if (stripos($string, $sqlCommand) !== false) {
                return true;
            }
        }

        foreach ($this->specialCharacters as $specialCharacter) {
            if (stripos($string, $specialCharacter) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check the URL for possible SQL injection attempts.
     *
     * @return bool True if a possible SQL injection attempt is detected in the URL, otherwise false.
     */
    public function detectSqlInjectionInURL()
    {
        $url = $_SERVER['REQUEST_URI'];

        if ($this->scan($url)) {
            echo "Possible SQL Injection";
            exit();
        }

        return false;
    }

    /**
     * Check POST data for possible SQL injection attempts.
     *
     * @return bool True if a possible SQL injection attempt is detected in any POST data, otherwise false.
     */
    public function detectSqlInjectionInPostData()
    {
        foreach ($_POST as $postData) {
            if ($this->scan($postData)) {
                echo "Possible SQL Injection";
                exit();
            }
        }

        return false;
    }

    /**
     * Check GET data for possible SQL injection attempts.
     *
     * @return bool True if a possible SQL injection attempt is detected in any GET data, otherwise false.
     */
    public function detectSqlInjectionInGetData()
    {
        foreach ($_GET as $getData) {
            if ($this->scan($getData)) {
                echo "Possible SQL Injection";
                exit();
            }
        }

        return false;
    }

    /**
     * Check headers for possible SQL injection attempts.
     *
     * @return bool True if a possible SQL injection attempt is detected in any header value, otherwise false.
     */
    public function detectSqlInjectionInHeaders()
    {
        foreach (getallheaders() as $name => $value) {
            if ($this->scan($value)) {
                echo "Possible SQL Injection.";
                exit();
            }
        }

        return false;
    }

    /**
     * Check cookies for possible SQL injection attempts.
     *
     * @return bool True if a possible SQL injection attempt is detected in any cookie data, otherwise false.
     */
    public function detectSqlInjectionInCookies()
    {
        foreach ($_COOKIE as $cookieData) {
            if ($this->scan($cookieData)) {
                echo "Possible SQL Injection";
                exit();
            }
        }

        return false;
    }
}
