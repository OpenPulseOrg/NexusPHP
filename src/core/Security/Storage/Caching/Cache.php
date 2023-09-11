<?php

namespace Nxp\Core\Security\Storage\Caching;

use Nxp\Core\Config\ConfigurationManager;
use Nxp\Core\Security\Cryptography\Hash\Hasher;
use Nxp\Core\Utils\Service\Locator\Locator;
use Nxp\Core\Security\Storage\Caching\Redis\RedisHandler; // Import the RedisHandler class

class Cache
{
    private $storageHandler;  // Either RedisHandler or filesystem
    private $hasher;
    private $cacheDir;
    private $useRedis = true;  // Default to file-based cache

    public function __construct()
    {
        $this->hasher = new Hasher(ConfigurationManager::get("keys", "CIPHER_KEY"));

        // Check if Redis should be used
        $this->useRedis = ConfigurationManager::get("app", "redis.use");
        if ($this->useRedis) {
            $ip = ConfigurationManager::get("app", "redis.ip");
            $port = ConfigurationManager::get("app", "redis.port");
            $password = ConfigurationManager::get("app", "redis.password");
            $database = ConfigurationManager::get("app", "redis.database");
            $this->storageHandler = new RedisHandler($ip, $port, $password, $database);  // Initialize RedisHandler
        } else {
            $locator = Locator::getInstance();
            $cacheDir = __DIR__ . "/../../../../cache";

            if (!is_dir($cacheDir)) {
                throw new \Exception("Cache directory does not exist.");
            }

            $this->cacheDir = rtrim($cacheDir, '/');
        }
    }

    private function getCacheFilePath($key)
    {
        return $this->cacheDir . '/' . md5($key) . '.cache';
    }

    public function set($key, $data, $expiration = 3600)
    {
        $cacheData = [
            'data' => $data,
            'expiration' => time() + $expiration,
        ];

        $cacheContent = serialize($cacheData);
        $hashedContent = $this->hasher->hash($cacheContent);

        if ($this->useRedis) {
            $this->storageHandler->set($key, $hashedContent, $expiration);
        } else {
            file_put_contents($this->getCacheFilePath($key), $hashedContent);
        }
    }

    public function get($key)
    {
        $hashedContent = $this->useRedis ? $this->storageHandler->get($key) : file_get_contents($this->getCacheFilePath($key));

        if (!$hashedContent) {
            return null; // Cache miss
        }

        $unhashedContent = $this->hasher->unhash($hashedContent);
        $cacheData = unserialize($unhashedContent);

        if ($cacheData['expiration'] < time()) {
            $this->delete($key); // Delete expired data
            return null;
        }

        return $cacheData['data'];
    }

    public function delete($key)
    {
        if ($this->useRedis) {
            $this->storageHandler->delete($key);
        } else {
            @unlink($this->getCacheFilePath($key));
        }
    }

    public function clear()
    {
        if ($this->useRedis) {
            $this->storageHandler->clear();
        } else {
            array_map('unlink', glob($this->cacheDir . '/*.cache'));
        }
    }

    public function getAll()
    {
        if ($this->useRedis) {
            $data = $this->storageHandler->getAll();
            foreach ($data as $key => $hashedContent) {
                $unhashedContent = $this->hasher->unhash($hashedContent);
                if ($unhashedContent === false) {
                    continue; // Skip this entry if decryption failed
                }

                $cacheData = unserialize($unhashedContent);

                // Exclude expired data
                if ($cacheData['expiration'] < time()) {
                    unset($data[$key]);
                    continue;
                }

                $data[$key] = $cacheData['data'];
            }
        } else {
            $data = [];
            $files = glob($this->cacheDir . '/*.cache');
            foreach ($files as $file) {
                $key = basename($file, '.cache');
                $hashedContent = file_get_contents($file);
                $unhashedContent = $this->hasher->unhash($hashedContent);
                $cacheData = unserialize($unhashedContent);

                // Exclude expired data
                if ($cacheData['expiration'] < time()) {
                    continue;
                }

                $data[$key] = $cacheData['data'];
            }
        }
        return $data;
    }
}
