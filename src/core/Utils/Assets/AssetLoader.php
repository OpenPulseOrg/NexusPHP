<?php

namespace Nxp\Core\Utils\Assets;

use Nxp\Core\Utils\Service\Locator\Locator;use Nxp\Core\Utils\Validation\Validation;

class AssetLoader
{
    private static function generateTag($type, $filename, $attributes = [])
    {
        $sanitizedFilename = Validation::sanitizeString($filename);

        // Using Locator to get the path dynamically
        $locator = Locator::getInstance();
        $path = $locator->getPath("assets", $type);

        switch ($type) {
            case 'css':
                return '<link rel="stylesheet" type="text/css" href="' . $path . $sanitizedFilename . '">' . PHP_EOL;

            case 'js':
            case 'json':
                return '<script src="' . $path . $sanitizedFilename . '"></script>' . PHP_EOL;

            case 'img':
                $alt = isset($attributes['alt']) ? Validation::sanitizeString($attributes['alt']) : '';
                $tag = '<img src="' . $path . $sanitizedFilename . '" alt="' . $alt . '"';
                foreach ($attributes as $key => $value) {
                    $sanitizedKey = Validation::sanitizeString($key);
                    $sanitizedValue = Validation::sanitizeString($value);
                    $tag .= ' ' . $sanitizedKey . '="' . $sanitizedValue . '"';
                }
                return $tag . '>' . PHP_EOL;

            case 'fonts':
                return '@import url("' . $path . $sanitizedFilename . '");' . PHP_EOL;

            case 'media':
            case 'audio':
            case 'videos':
                $typeAttr = isset($attributes['type']) ? Validation::sanitizeString($attributes['type']) : '';
                return '<source src="' . $path . $sanitizedFilename . '" type="' . $typeAttr . '">' . PHP_EOL;

            case 'favicons':
                // Handle favicon logic here
                break;

            default:
                return '';
        }
    }

    public static function load($type, $filename, $attributes = [])
    {
        echo self::generateTag($type, $filename, $attributes);
    }

    public static function loadMultiple($type, $filenames, $attributes = [])
    {
        foreach ($filenames as $filename) {
            self::load($type, $filename, $attributes);
        }
    }

    public static function loadExternalResource($type, $url)
    {
        $sanitizedUrl = Validation::sanitizeString($url);
        if ($type === 'css') {
            echo '<link rel="stylesheet" type="text/css" href="' . $sanitizedUrl . '">' . PHP_EOL;
        } elseif ($type === 'js') {
            echo '<script src="' . $sanitizedUrl . '"></script>' . PHP_EOL;
        }
    }
}
