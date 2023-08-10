<?php

namespace Nxp\Core\Security\Detection;

use Nxp\Core\Utils\Error\ErrorFactory;
use Nxp\Core\Utils\Service\Container;

class SQLDetection
{
    private $errorHandler;
    private $sqlCommands = array(
        "select", "drop", "update", "delete", "insert", "union", "--",
        "null", "like", "where", "truncate", "alter", "create", "execute",
        "exec", "declare", "savepoint", "rollback", "set", "show", "use",
        "index", "between", "group by", "order by", "having", "limit",
        "into", "join", "inner join", "left join", "right join", "full join",
        "cross join", "self join", "case", "end", "when", "then", "else",
        "replace", "distinct", "exists", "all", "any", "some", "view", "top",
        "asc", "desc", "cast", "convert", "avg", "count", "first", "last",
        "min", "max", "sum", "group_concat", "len", "char", "varchar",
        "date", "datetime", "timestamp", "password", "load_file", "outfile",
        "character", "session_user", "system_user", "current_user", "user",
        "lock", "keys", "privileges", "procedure", "function", "database",
        "grant", "revoke", "flush"
    );
    private $specialCharacters = array("<", ">", "\\");

    private function logAttempt($message)
    {

        $container = Container::getInstance();

        $factory = new ErrorFactory($container);
        $this->errorHandler = $factory->createErrorHandler();

        $this->errorHandler->handleError(
            "Possible SQL Infection Detected",
            null,
            [
                "Message" => $message
            ],
            "CRITICAL"
        );
    }

    private function scan($string)
    {
        $string = urldecode($string);

        foreach ($this->sqlCommands as $sqlCommand) {
            if (stripos($string, $sqlCommand) !== false) {
                $this->logAttempt("Possible SQL Injection detected: " . $string);
                return true;
            }
        }

        foreach ($this->specialCharacters as $specialCharacter) {
            if (strpos($string, $specialCharacter) !== false) {
                $this->logAttempt("Suspicious character detected: " . $string);
                return true;
            }
        }

        return false;
    }

    public function detectSqlInjectionInURL()
    {
        $url = $_SERVER['REQUEST_URI'];
        return $this->scan($url);
    }

    public function detectSqlInjectionInPostData()
    {
        foreach ($_POST as $postData) {
            if ($this->scan($postData)) {
                return true;
            }
        }
        return false;
    }

    public function detectSqlInjectionInGetData()
    {
        foreach ($_GET as $getData) {
            if ($this->scan($getData)) {
                return true;
            }
        }
        return false;
    }

    public function detectSqlInjectionInHeaders()
    {
        if (function_exists('getallheaders')) {
            foreach (getallheaders() as $name => $value) {
                if ($this->scan($value)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function detectSqlInjectionInCookies()
    {
        foreach ($_COOKIE as $cookieData) {
            if ($this->scan($cookieData)) {
                return true;
            }
        }
        return false;
    }
}
