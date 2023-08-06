<?php

namespace Nxp\Core\Security\Storage\Caching;

use Exception;
use Nxp\Core\Config\ConfigHandler;
use Nxp\Core\Security\Cryptography\Hash\Hasher;

/**
 * The Cache class provides functionality for caching and retrieving data securely.
 * Data is serialized, hashed, and stored in cache files in the cache directory.
 *
 * @package Nxp\Core\Security\Storage\Caching
 */
class Cache
{
    private $cacheDir;

    /**
     * Cache constructor.
     *
     * Initializes the cache directory and ensures it exists.
     *
     * @throws Exception If the cache directory does not exist or cannot be created.
     */
    public function __construct()
    {
        $cacheDir = __DIR__ . "/../../../../cache";

        if (!is_dir($cacheDir)) {
            throw new Exception("Cache directory does not exist.");
        }

        $this->cacheDir = rtrim($cacheDir, '/');
    }

    /**
     * Get the cache file path for a given cache key.
     *
     * @param string $key The cache key for which the cache file path is needed.
     * @return string The cache file path.
     */
    private function getCacheFilePath($key)
    {
        return $this->cacheDir . '/' . md5($key) . '.cache';
    }

    /**
     * Set data into the cache.
     *
     * @param string $key        The cache key for identifying the data.
     * @param mixed  $data       The data to be cached.
     * @param int    $expiration The expiration time for the cached data in seconds. Default is 3600 seconds (1 hour).
     * @throws Exception If there is an error while storing the data into the cache.
     * @return void
     */
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

    /**
     * Get cached data by a given cache key.
     *
     * @param string $key The cache key for retrieving the cached data.
     * @return mixed|null The cached data if found and not expired, null otherwise (cache miss).
     */
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
            // For simplicity, here we just return null to indicate a cache miss.
            return null;
        }

        if ($cacheData['expiration'] < time()) {
            // Cache has expired, delete the file and return null
            unlink($cacheFilePath);
            return null;
        }

        return $cacheData['data']; // Cache hit and not expired
    }

    /**
     * Delete cached data by a given cache key.
     *
     * @param string $key The cache key for identifying the data to be deleted.
     * @return void
     */
    public function delete($key)
    {
        $cacheFilePath = $this->getCacheFilePath($key);
        if (file_exists($cacheFilePath)) {
            unlink($cacheFilePath);
        }
    }

    /**
     * Clear all cached data by removing all cache files in the cache directory.
     *
     * @return void
     */
    public function clear()
    {
        $files = glob($this->cacheDir . '/*.cache');
        foreach ($files as $file) {
            unlink($file);
        }
    }
}
