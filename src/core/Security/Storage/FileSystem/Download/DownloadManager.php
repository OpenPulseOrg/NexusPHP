<?php

namespace Nxp\Core\Security\Storage\FileSystem\Download;

/**
 * DownloadManager class for downloading files from URLs and managing downloads.
 *
 * @package Nxp\Core\Security\Storage\FileSystem\Download
 */
class DownloadManager
{
    /**
     * Downloads a file from a given URL and saves it to the specified location.
     *
     * @param string $url      The URL of the file to download.
     * @param string $savePath The path where the file should be saved.
     * @return bool            True on success, false on failure.
     */
    public static function downloadFile($url, $savePath)
    {
        $fileContents = file_get_contents($url);
        if ($fileContents === false) {
            return false;
        }

        $result = file_put_contents($savePath, $fileContents);
        return $result !== false;
    }

    /**
     * Downloads a file from a given URL and saves it to a temporary location.
     * The temporary file path is returned on success, or false on failure.
     *
     * @param string $url The URL of the file to download.
     * @return string|bool The temporary file path on success, or false on failure.
     */
    public static function downloadFileToTemp($url)
    {
        $tempFilePath = tempnam(sys_get_temp_dir(), 'download_');
        if (!$tempFilePath) {
            return false;
        }

        $result = self::downloadFile($url, $tempFilePath);
        if ($result) {
            return $tempFilePath;
        }

        unlink($tempFilePath); // Clean up the temporary file on failure
        return false;
    }

    /**
     * Downloads a base64-encoded image and saves it to the specified location.
     *
     * @param string $base64Image The base64-encoded image data.
     * @param string $savePath    The path where the image should be saved.
     * @param int|null $width     The desired width of the image (optional).
     * @param int|null $height    The desired height of the image (optional).
     * @return bool               True on success, false on failure or if the image data is empty.
     */
    public static function downloadBase64Image($base64Image, $savePath, $width = null, $height = null)
    {
        $imageData = base64_decode($base64Image);
        if ($imageData === false || empty($imageData)) {
            return false;
        }

        if ($width !== null && $height !== null) {
            $resizedImageData = self::resizeImage($imageData, $width, $height);
            if ($resizedImageData !== false) {
                $imageData = $resizedImageData;
            }
        }

        $result = file_put_contents($savePath, $imageData);
        return $result !== false;
    }


    /**
     * Resizes the given image data to the specified size.
     *
     * @param string $imageData The image data to resize.
     * @param int $width The desired width of the image.
     * @param int $height The desired height of the image.
     * @return string|bool The resized image data on success, or false on failure.
     */
    private static function resizeImage($imageData, $width, $height)
    {
        // Resize the image using your preferred image manipulation library or algorithm
        // Replace the following code with your actual implementation

        $image = imagecreatefromstring($imageData);
        if ($image === false) {
            return false;
        }

        $resizedImage = imagescale($image, $width, $height);
        if ($resizedImage === false) {
            return false;
        }

        ob_start();
        imagepng($resizedImage);
        $resizedImageData = ob_get_clean();

        imagedestroy($image);
        imagedestroy($resizedImage);

        return $resizedImageData;
    }
}
