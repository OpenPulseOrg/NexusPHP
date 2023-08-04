<?php

namespace Nxp\Core\Security\Storage\Caching;

use Exception;
use Nxp\Core\Config\ConfigHandler;
use Nxp\Core\Security\Cryptography\Hash\Hasher;

/**
 * CacheHandler class provides functionality for caching and retrieving data.
 *
 * @package Nxp\Core\Security\Storage\Caching
 */
class Cache
{
    private $cacheDir;

    public function __construct()
    {
        $cacheDir = __DIR__ . "/../../../../cache";

        if (!is_dir($cacheDir)) {
            throw new Exception("Cache directory does not exist.");
        }

        $this->cacheDir = rtrim($cacheDir, '/');
    }

    private function getCacheFilePath($key)
    {
        return $this->cacheDir . '/' . md5($key) . '.cache';
    }

    public function set($key, $data, $expiration = 3600)
    {
        $cacheFilePath = $this->getCacheFilePath($key);
        $cacheData = array(
            'data' => $data,
            'expiration' => time() + $expiration,
        );

        $cacheContent = serialize($cacheData);

        $hasher = new Hasher(ConfigHandler::get("keys", "CIPHER_KEY"));

        $hashedContent = $hasher->hash($cacheContent);

        // Create the cache directory if it doesn't exist
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }

        try {
            file_put_contents($cacheFilePath, $hashedContent);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function get($key)
    {
        $hasher = new Hasher(ConfigHandler::get("keys", "CIPHER_KEY"));

        $cacheFilePath = $this->getCacheFilePath($key);

        if (!file_exists($cacheFilePath)) {
            return null; // Cache miss or data was never cached
        }

        $cacheContent = file_get_contents($cacheFilePath);

        $unhashedContent = $hasher->unhash($cacheContent);

        $cacheData = unserialize($unhashedContent);
        
        if ($cacheData === false || !is_array($cacheData)) {
            // Log an error or throw an exception here
            // to help you figure out why $unhashedContent is not unserializing properly
            echo "false";
            return null;
        }

        if ($cacheData['expiration'] < time()) {
            // Cache has expired, delete the file and return null
            unlink($cacheFilePath);
            return null;
        }

        return $cacheData['data']; // Cache hit and not expired
    }



    public function delete($key)
    {
        $cacheFilePath = $this->getCacheFilePath($key);
        if (file_exists($cacheFilePath)) {
            unlink($cacheFilePath);
        }
    }

    public function clear()
    {
        $files = glob($this->cacheDir . '/*.cache');
        foreach ($files as $file) {
            unlink($file);
        }
    }
}
