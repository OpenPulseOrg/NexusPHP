<?php

namespace Nxp\Core\Security\Storage\Caching\Redis;

use Redis;
use Exception;

class RedisHandler
{
    private $redis;

    public function __construct($host = '127.0.0.1', $port = 6379, $password = null, $database = 0)
    {
        $this->redis = new Redis();

        if (!$this->redis->connect($host, $port)) {
            throw new Exception("Failed to connect to Redis server at $host:$port");
        }

        if ($password && !$this->redis->auth($password)) {
            throw new Exception("Failed to authenticate with the provided password");
        }

        if ($database && !$this->redis->select($database)) {
            throw new Exception("Failed to select database $database");
        }
    }

    public function set($key, $data, $expiration = 0)
    {
        return $this->executeCommand(function () use ($key, $data, $expiration) {
            return $this->redis->setex($key, $expiration, $data);
        });
    }

    public function get($key)
    {
        return $this->executeCommand(function () use ($key) {
            return $this->redis->get($key);
        });
    }

    public function delete($key)
    {
        return $this->executeCommand(function () use ($key) {
            return $this->redis->del($key);
        });
    }

    public function clear()
    {
        return $this->executeCommand(function () {
            return $this->redis->flushDB();
        });
    }

    /**
     * Helper function to execute Redis commands and handle exceptions.
     *
     * @param callable $callback The Redis command to execute.
     * @return mixed The result of the Redis command.
     * @throws Exception If there's an error executing the Redis command.
     */
    private function executeCommand(callable $callback)
    {
        try {
            return $callback();
        } catch (Exception $e) {
            throw new Exception("Redis error: " . $e->getMessage());
        }
    }

    public function getAll()
    {
        $allKeys = $this->redis->keys('*');
        $data = [];
        foreach ($allKeys as $key) {
            $data[$key] = $this->redis->get($key);
        }
        return $data;
    }
}
