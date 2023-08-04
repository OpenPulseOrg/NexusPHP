<?php

namespace Nxp\Core\Utils\Assets;

use Nxp\Core\Config\ConfigHandler;
use Nxp\Core\Utils\Validation\Validation;

/**
 * The AssetLoader class provides methods for loading various types of assets 
 * (CSS, JavaScript, images, fonts, external resources) and generating a favicon HTML tag.
 *
 * @package Nxp\Core\Utils\Assets
 */
class AssetLoader
{
    /**
     * Loads a CSS file by outputting the corresponding HTML link tag.
     *
     * @param string $filename The filename of the CSS file.
     * @return void
     */
    public static function loadCSS($filename)
    {
        $sanitizedFilename = Validation::sanitizeString($filename);

        $root_install = ConfigHandler::get("app", "ROOT_INSTALL");
        echo '<link rel="stylesheet" type="text/css" href="' .  $root_install . "/storage/assets/css/" . $sanitizedFilename . '">' . PHP_EOL;
    }

    /**
     * Loads a JavaScript file by outputting the corresponding HTML script tag.
     *
     * @param string $filename The filename of the JavaScript file.
     * @return void
     */
    public static function loadJS($filename)
    {
        $sanitizedFilename = Validation::sanitizeString($filename);

        $root_install = ConfigHandler::get("app", "ROOT_INSTALL");
        echo '<script src="' .  $root_install . "/storage/assets/js/" . $sanitizedFilename . '"></script>' . PHP_EOL;
    }

    /**
     * Loads an image file by outputting the corresponding HTML image tag.
     *
     * @param string $filename The filename of the image file.
     * @param string|null $alt The alternate text for the image (optional).
     * @return void
     */
    public static function loadImage($filename, $alt = null, $options = [])
    {
        $sanitizedFilename = Validation::sanitizeString($filename);
        $sanitizedAlt = Validation::sanitizeString($alt);

        $root_install = ConfigHandler::get("app", "ROOT_INSTALL");

        // Start building the image tag with required attributes
        $imageTag = '<img src="' . $root_install . '/storage/assets/img/' . $sanitizedFilename . '" alt="' . $sanitizedAlt . '"';

        // Process additional options
        foreach ($options as $attribute => $value) {
            // Sanitize attribute and value for security
            $sanitizedAttribute = Validation::sanitizeString($attribute);
            $sanitizedValue = Validation::sanitizeString($value);

            // Append the attribute and value to the image tag
            $imageTag .= ' ' . $sanitizedAttribute . '="' . $sanitizedValue . '"';
        }

        // Close the image tag
        $imageTag .= '>';

        echo $imageTag . PHP_EOL;
    }


    /**
     * Loads a font file by outputting the corresponding CSS import rule.
     *
     * @param string $filename The filename of the font file.
     * @return void
     */
    public static function loadFont($filename)
    {
        $sanitizedFilename = Validation::sanitizeString($filename);

        $root_install = ConfigHandler::get("app", "ROOT_INSTALL");
        echo '@import url("' . $root_install . '/storage/assets/fonts/' . $sanitizedFilename . '");' . PHP_EOL;
    }

    /**
     * Loads an external resource (e.g., a stylesheet or script) by outputting the corresponding HTML tag.
     *
     * @param string $type The type of the resource ("css" or "js").
     * @param string $url The URL of the external resource.
     * @return void
     */
    public static function loadExternalResource($type, $url)
    {
        $sanitizedType = Validation::sanitizeString($type);
        $sanitizedUrl = Validation::sanitizeString($url);

        if ($sanitizedType === 'css') {
            echo '<link rel="stylesheet" type="text/css" href="' . $sanitizedUrl . '">' . PHP_EOL;
        } elseif ($sanitizedType === 'js') {
            echo '<script src="' . $sanitizedUrl . '"></script>' . PHP_EOL;
        }
    }

    /**
     * Loads multiple CSS files by outputting the corresponding HTML link tags.
     *
     * @param array $filenames An array of CSS file names.
     * @return void
     */
    public static function loadMultipleCSS(array $filenames)
    {
        foreach ($filenames as $filename) {
            self::loadCSS($filename);
        }
    }

    /**
     * Loads multiple JavaScript files by outputting the corresponding HTML script tags.
     *
     * @param array $filenames An array of JavaScript file names.
     * @return void
     */
    public static function loadMultipleJS(array $filenames)
    {
        foreach ($filenames as $filename) {
            self::loadJS($filename);
        }
    }

    /**
     * Loads multiple image files by outputting the corresponding HTML image tags.
     *
     * @param array $filenames An array of image file names.
     * @param string|null $alt The alternate text for the images (optional).
     * @return void
     */
    public static function loadMultipleImages(array $filenames, $alt = null)
    {
        foreach ($filenames as $filename) {
            self::loadImage($filename, $alt);
        }
    }

    /**
     * Loads multiple font files by outputting the corresponding CSS import rules.
     *
     * @param array $filenames An array of font file names.
     * @return void
     */
    public static function loadMultipleFonts(array $filenames)
    {
        foreach ($filenames as $filename) {
            self::loadFont($filename);
        }
    }

    /**
     * Loads multiple external resources (e.g., stylesheets or scripts) and returns the corresponding HTML tags as an array.
     *
     * @param array $resources An associative array where the keys represent the types ("css" or "js") and the values are arrays of URLs.
     * @return array An array containing the HTML tags for the loaded resources.
     */
    public static function loadMultipleExternalResources(array $resources)
    {
        $htmlTags = [];

        foreach ($resources as $type => $urls) {
            foreach ($urls as $url) {
                $htmlTags[] = self::loadExternalResource($type, $url);
            }
        }

        return $htmlTags;
    }


    /**
     * Generates a favicon HTML tag.
     *
     * @param string $filename The filename of the favicon file.
     * @param int|null $size The size of the favicon in pixels (optional).
     * @return void
     */
    public static function generateFavicon($filename, $size = null)
    {
        $sanitizedFilename = Validation::sanitizeString($filename);
        $root_install = ConfigHandler::get("app", "ROOT_INSTALL");

        // Get the file extension from the filename
        $fileExtension = strtolower(pathinfo($sanitizedFilename, PATHINFO_EXTENSION));

        // Determine the appropriate type attribute based on the file extension
        $typeAttribute = 'image/png'; // Default type for PNG files
        if ($fileExtension === 'ico') {
            $typeAttribute = 'image/x-icon'; // Type for .ico files
        } elseif ($fileExtension === 'jpg' || $fileExtension === 'jpeg') {
            $typeAttribute = 'image/jpeg'; // Type for .jpg or .jpeg files
        }

        $faviconTag = '<link rel="icon" type="' . $typeAttribute . '" href="' . $root_install . '/storage/assets/favicons/' . $sanitizedFilename . '"';

        if ($size !== null) {
            $sizeValue = (int)$size;
            $faviconTag .= ' sizes="' . $sizeValue . 'x' . $sizeValue . '"';
        }

        $faviconTag .= '>';

        echo $faviconTag . PHP_EOL;
    }


    /**
     * Loads a media file by outputting the corresponding HTML media tag.
     *
     * @param string $filename The filename of the media file.
     * @param string $type The MIME type of the media file.
     * @return void
     */
    public static function loadMedia($filename, $type)
    {
        $sanitizedFilename = Validation::sanitizeString($filename);
        $sanitizedType = Validation::sanitizeString($type);

        $root_install = ConfigHandler::get("app", "ROOT_INSTALL");
        echo '<source src="' . $root_install . '/storage/assets/media/' . $sanitizedFilename . '" type="' . $sanitizedType . '">' . PHP_EOL;
    }

    /**
     * Loads multiple media files by outputting the corresponding HTML media tags.
     *
     * @param array $files An array of media file names and their corresponding MIME types.
     *                    The keys represent the filenames and the values represent the MIME types.
     * @return void
     */
    public static function loadMultipleMedia(array $files)
    {
        foreach ($files as $filename => $type) {
            self::loadMedia($filename, $type);
        }
    }

    /**
     * Loads a video file by outputting the corresponding HTML video tag.
     *
     * @param string $filename The filename of the video file.
     * @param string|null $poster The URL of an image to be shown while the video is downloading, or before the user starts playback (optional).
     * @return void
     */
    public static function loadVideo($filename, $poster = null)
    {
        $sanitizedFilename = Validation::sanitizeString($filename);
        $sanitizedPoster = Validation::sanitizeString($poster);

        $root_install = ConfigHandler::get("app", "ROOT_INSTALL");
        echo '<video src="' . $root_install . '/storage/assets/videos/' . $sanitizedFilename . '"';

        if ($sanitizedPoster !== null) {
            echo ' poster="' . $sanitizedPoster . '"';
        }

        echo '></video>' . PHP_EOL;
    }

    /**
     * Loads an audio file by outputting the corresponding HTML audio tag.
     *
     * @param string $filename The filename of the audio file.
     * @return void
     */
    public static function loadAudio($filename)
    {
        $sanitizedFilename = Validation::sanitizeString($filename);

        $root_install = ConfigHandler::get("app", "ROOT_INSTALL");
        echo '<audio src="' . $root_install . '/storage/assets/audio/' . $sanitizedFilename . '"></audio>' . PHP_EOL;
    }

    /**
     * Loads multiple audio files by outputting the corresponding HTML audio tags.
     *
     * @param array $filenames An array of audio file names.
     * @return void
     */
    public static function loadMultipleAudio(array $filenames)
    {
        foreach ($filenames as $filename) {
            self::loadAudio($filename);
        }
    }

    /**
     * Loads a JSON file by outputting the corresponding HTML script tag.
     *
     * @param string $filename The filename of the JSON file.
     * @return void
     */
    public static function loadJSON($filename)
    {
        $sanitizedFilename = Validation::sanitizeString($filename);

        $root_install = ConfigHandler::get("app", "ROOT_INSTALL");
        echo '<script src="' . $root_install . '/storage/json/' . $sanitizedFilename . '"></script>' . PHP_EOL;
    }

    /**
     * Loads multiple JSON files by outputting the corresponding HTML script tags.
     *
     * @param array $filenames An array of JSON file names.
     * @return void
     */
    public static function loadMultipleJSON(array $filenames)
    {
        foreach ($filenames as $filename) {
            self::loadJSON($filename);
        }
    }

    /**
     * Loads a remote CSS file by outputting the corresponding HTML link tag.
     *
     * @param string $url The URL of the remote CSS file.
     * @return void
     */
    public static function loadRemoteCSS($url)
    {
        $sanitizedUrl = Validation::sanitizeUrl($url);

        echo '<link rel="stylesheet" type="text/css" href="' . $sanitizedUrl . '">' . PHP_EOL;
    }

    /**
     * Loads a remote JavaScript file by outputting the corresponding HTML script tag.
     *
     * @param string $url The URL of the remote JavaScript file.
     * @return void
     */
    public static function loadRemoteJS($url)
    {
        $sanitizedUrl = Validation::sanitizeUrl($url);

        echo '<script src="' . $sanitizedUrl . '"></script>' . PHP_EOL;
    }

    /**
     * Loads a remote image by outputting the corresponding HTML image tag.
     *
     * @param string $url The URL of the remote image.
     * @param string|null $alt The alternate text for the image (optional).
     * @return void
     */
    public static function loadRemoteImage($url, $alt = null)
    {
        $sanitizedUrl = Validation::sanitizeUrl($url);
        $sanitizedAlt = Validation::sanitizeString($alt);

        echo '<img src="' . $sanitizedUrl . '" alt="' . $sanitizedAlt . '">' . PHP_EOL;
    }
}
