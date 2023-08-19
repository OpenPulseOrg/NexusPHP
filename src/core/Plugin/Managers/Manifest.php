<?php

namespace Nxp\Core\Plugin\Managers;

use Exception;

class Manifest
{
    public function loadManifestData(string $pluginDirectory): array
    {
        $manifestPath = $pluginDirectory . '/manifest.json';
        if (is_file($manifestPath)) {
            $data = json_decode(file_get_contents($manifestPath), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->validateManifestData($data, basename($pluginDirectory));
                return $data;
            } else {
                throw new Exception("Invalid manifest.json file for plugin $pluginDirectory");
            }
        } else {
            throw new Exception("Manifest file not found for plugin $pluginDirectory");
        }
        return [];
    }

    private function validateManifestData(array $manifestData, string $pluginName): void
    {
        $requiredKeys = ['name', 'version', 'author', 'description', 'entryPoint'];
        foreach ($requiredKeys as $key) {
            if (!isset($manifestData[$key]) || empty($manifestData[$key])) {
                throw new Exception("Required key '$key' is missing or empty in manifest for plugin $pluginName");
            }
        }
    }
}
