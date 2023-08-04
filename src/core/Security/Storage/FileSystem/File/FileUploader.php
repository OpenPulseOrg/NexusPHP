<?php

namespace Nxp\Core\Security\Storage\FileSystem\File;

use Exception;
use Nxp\Core\Config\ConfigHandler;
use Nxp\Core\Database\Factories\Query;
use Nxp\Core\Security\Logging\Logger;
use Nxp\Core\Utils\Navigation\Redirects;
use Nxp\Core\Utils\Service\Container;

/**
 * FileUploader class for handling file uploads.
 *
 * @package Nxp\Core\Security\Storage\FileSystem\File
 */
class FileUploader
{
    private $target_dir;
    private $return_path;
    private $allowed_extensions;
    private $max_file_size;

    /**
     * Class constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->allowed_extensions = ConfigHandler::get("app", "ALLOWED_FILE_TYPES");
        $this->max_file_size = $this->getMaxFileSize();
    }

    /**
     * Sets the target directory for file uploads.
     *
     * @param string $dir The target directory for file uploads.
     *
     * @return $this
     */
    public function setDir($dir)
    {
        $this->target_dir = $dir;
        return $this;
    }

    public function setReturn($path)
    {
        $this->return_path = $path;
        return $this;
    }
    /**
     * Uploads a file to the target directory.
     *
     * @return string|bool The filename of the uploaded file, or false if the upload failed.
     */
    public function upload()
    {
        if (!isset($_FILES['file'])) {
            return false;
        }

        $file = $_FILES['file'];
        $filename = $file['name'];

        return $this->uploadFile($file, $filename);
    }

    /**
     * Uploads a file to the target directory.
     *
     * @param array $file The $_FILES array for the file to be uploaded.
     * @param string $filename The original filename of the file to be uploaded.
     *
     * @return string|bool The filename of the uploaded file, or false if the upload failed.
     */
    private function uploadFile($file, $filename)
    {
        $target_path = $this->target_dir . '/' . $filename;

        $validationResult = $this->validateFile($file, $filename);
        if (!$validationResult['valid']) {
            $errorCode = $validationResult['error'];
            return $errorCode;
        }

        try {
            $uploadedFile = move_uploaded_file($file["tmp_name"], $target_path);
            if ($uploadedFile) {
                return true;
            } else {
                Redirects::redirectToLocation($this->return_path);
            }
        } catch (Exception $e) {
            throw new Exception($e);
        }

        return $filename;
    }


    /**
     * Validates the uploaded file.
     *
     * @param array $file The $_FILES array for the file to be uploaded.
     * @param string $filename The filename of the file to be uploaded.
     *
     * @return bool Whether or not the file is valid.
     */
    private function validateFile($file, $filename)
    {
        if ($file['error'] == UPLOAD_ERR_INI_SIZE) {
            $this->logError("File exceeds the 'upload_max_filesize' setting within php.ini", $file, $filename);
            return ["error" => "fileSizeExceedsPHP", "valid" => false];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->logError("An error occurred while uploading the file", $file, $filename);
            return ["error" => "errorOccurred", "valid" => false];
        }

        if ($file['size'] > $this->max_file_size) {
            $this->logError("File size exceeds the maximum allowed size", $file, $filename);
            return ["error" => "fileSizeExceeds", "valid" => false];
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $allowed_extensions = ConfigHandler::get("app", "ALLOWED_FILE_TYPES");
        if (!in_array($extension, $allowed_extensions)) {
            $this->logError("asdasddas", $file, $filename);
            return ["error" => "extensionNotAllowed", "valid" => false];
        }

        return ["valid" => true];
    }



    /**
     * Logs an error when uploading a file.
     *
     * @param string $message The error message to be logged.
     * @param array $file The $_FILES array for the file being uploaded.
     * @param string $filename The filename of the file being uploaded.
     *
     * @return void
     */
    private function logError($message, $file, $filename)
    {
        $queryFactory = new Query(Container::getInstance());
        $logger = new Logger($queryFactory);

        $logger->log("CRITICAL", "Error Uploading File", [
            "Message" => $message,
            "Error Code" => $file['error'],
            "File" => $file["name"],
            "Type" => $file["type"],
            "Extension" => pathinfo($filename, PATHINFO_EXTENSION),
            "Allowed Extensions" => json_encode($this->allowed_extensions),
            "Target Directory" => $this->target_dir,
        ]);
    }

    /**
     * Gets the maximum allowed file size for uploads.
     *
     * @return int The maximum allowed file size in bytes.
     */
    private function getMaxFileSize()
    {
        $max_file_size_ini = ini_get('upload_max_filesize');
        $max_file_size_config = ConfigHandler::get("app", "MAX_ALLOWED_FILE_SIZE");

        if ($max_file_size_ini === false) {
            // php.ini does not define upload_max_filesize, fallback to configuration
            $max_file_size = $max_file_size_config;
        } else {
            $max_file_size = $this->convertToBytes($max_file_size_ini);
            if ($max_file_size_config > 0 && $max_file_size_config < $max_file_size) {
                // Use the lower value between php.ini and configuration file
                $max_file_size = $max_file_size_config;
            }
        }

        return $max_file_size;
    }


    /**
     * Converts a string representation of a file size to bytes.
     *
     * @param string $value The string representation of the file size.
     *
     * @return int The file size in bytes.
     */
    private function convertToBytes($value)
    {
        $value = trim($value);
        $last = strtolower($value[strlen($value) - 1]);
        $value = (int) $value;
        switch ($last) {
            case 'g':
                $value *= 1024;
                // no break (cumulative multiplier)
            case 'm':
                $value *= 1024;
                // no break (cumulative multiplier)
            case 'k':
                $value *= 1024;
        }
        return $value;
    }
}
