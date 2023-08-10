<?php

namespace Nxp\Core\Security\Storage\FileSystem\Directory;

use DateTime;
use Exception;
use Nxp\Core\Utils\Error\ErrorFactory;
use Nxp\Core\Utils\Randomization\Generator;
use Nxp\Core\Utils\Service\Container;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * DirectoryManager class for managing directories.
 *
 * @package Nxp\Core\Security\Storage\FileSystem\Directory
 */
class DirectoryManager
{
    protected $directory;
    protected $errorHandler;

    public function __construct()
    {
        $container = Container::getInstance();
        $factory = new ErrorFactory($container);
        $this->errorHandler = $factory->createErrorHandler();
    }


    /**
     * Creates a folder or directory.
     *
     * @param string $directory The directory to create the subdirectories in.
     * @param string|array $subdirs The name of the subdirectory to create, or an array of subdirectory names to create.
     * @param int $permissions The permissions to set on the created directories. Defaults to 0777.
     * @param bool $recursive Whether to create the subdirectories recursively. Defaults to true.
     *
     * @return bool True if the directories were created successfully.
     *
     * @throws Exception If unable to create the directory.
     */
    public function createFolder($directory, $subdirs, $permissions = 0777, $recursive = true)
    {
       if (!is_array($subdirs)) {
            $subdirs = array($subdirs);
        }

        $dir = rtrim($directory, DIRECTORY_SEPARATOR);

        foreach ($subdirs as $subdir) {
            $newdir = $dir . DIRECTORY_SEPARATOR . $subdir;

            if (!is_dir($newdir)) {
                if (!mkdir($newdir, $permissions, $recursive)) {
                    $this->errorHandler->handleError(
                        "Unable to create directory",
                        null,
                        [
                            "Message" => "Unable to create directory",
                            "Directory" => $newdir
                        ],
                        "WARNING"
                    ); 
                    throw new Exception("Unable to create directory: $newdir");
                }
                $this->errorHandler->handleError(
                    "Folder Created",
                    null,
                    [
                        "Name" => "System",
                        "Directory" => $directory,
                        "Sub Directory" => json_encode($subdirs),
                        "Permissions" => $permissions,
                        "Recursive" => $recursive
                    ],
                    "INFO"
                ); 
            }
        }

        return true;
    }


    /**
     * Creates a unique folder or directory.
     *
     * @param string $directory The directory to create the unique subdirectory in.
     * @param string $prefix A prefix to add to the folder name. Defaults to an empty string.
     * @param int $permissions The permissions to set on the created directory. Defaults to 0777.
     * @param bool $recursive Whether to create the directory recursively. Defaults to true.
     *
     * @return string The path to the created directory.
     *
     * @throws Exception If unable to create the unique folder.
     */
    public function createUniqueFolder($directory, $prefix = "", $permissions = 0777, $recursive = true)
    {
        $dateTime = new DateTime();
        $formattedDate = $dateTime->format('YmdHis');
        $randomString = Generator::generateRandomString();

        $folderName = $prefix . $formattedDate . '_' . $randomString;

        if (self::createFolder($directory, $folderName, $permissions, $recursive)) {
            return $directory . DIRECTORY_SEPARATOR . $folderName;
        } else {
            $this->errorHandler->handleError(
                "Folder Creation Error",
                null,
                [
                    "Directory" => $directory,
                    "Folder" => $folderName,
                    "Permissions" => $permissions,
                    "Recursive" => $recursive,
                    "Message" => "Unable to create unique folder in directory"
                ],
                "WARNING"
            ); 
            throw new Exception("Unable to create unique folder in directory");
        }
    }

    /**
     * Deletes a folder and its contents.
     *
     * @param string $directory The directory to delete.
     *
     * @return bool True if the directory was deleted successfully.
     *
     * @throws Exception If the provided directory is not valid.
     */
    public function deleteFolder($directory)
    {
        if (!is_dir($directory)) {
            $this->errorHandler->handleError(
                "Invalid Directory",
                null,
                [
                    "Message" => "Directory is not valid.",
                    "Directory" => $directory,
                ],
                "WARNING"
            ); 
            throw new Exception("$directory is not a valid directory");
        }

        $directoryContents = glob($directory . DIRECTORY_SEPARATOR . '*');

        foreach ($directoryContents as $content) {
            if (is_dir($content)) {
                self::deleteFolder($content);
            } else {
                unlink($content);
            }
        }

        if (rmdir($directory)) {
            return true;
        } else {
            $this->errorHandler->handleError(
                "Folder Deletion Error",
                null,
                [
                    "Directory" => $directory,
                    "Message" => "Unable to delete directory"
                ],
                "WARNING"
            ); 
        }
    }


    /**
     * Copies a folder and its contents to a new location.
     *
     * @param string $source The path to the folder to copy.
     * @param string $destination The path to copy the folder to.
     * @param bool $recursive Whether to copy the folder and its contents recursively. Defaults to true.
     *
     * @return bool True if the folder was copied successfully.
     *
     * @throws Exception If the source directory is not valid.
     */
    public function copyFolder($source, $destination, $recursive = true)
    {
        if (!is_dir($source)) {
            $this->errorHandler->handleError(
                "Folder Copy Error",
                null,
                [
                    "Source" => $source,
                    "Destination" => $destination,
                    "Recursive" => $recursive,
                    "Message" => "$source is not a valid directory"
                ],
                "WARNING"
            ); 
        }

        if (!is_dir($destination)) {
            self::createFolder(dirname($destination), basename($destination));
        }

        $directoryContents = glob($source . DIRECTORY_SEPARATOR . '*');

        foreach ($directoryContents as $content) {
            $contentName = basename($content);
            $destinationPath = $destination . DIRECTORY_SEPARATOR . $contentName;

            if (is_dir($content) && $recursive) {
                self::copyFolder($content, $destinationPath, $recursive);
            } else {
                copy($content, $destinationPath);
            }
        }

        return true;
    }

    /**
     * Gets the total size of a folder and its contents in bytes.
     *
     * @param string $directory The path to the folder to get the size of.
     *
     * @return int The size of the folder in bytes.
     */
    public static function getFolderSize($directory)
    {
        $size = 0;

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }

        return $size;
    }

    /**
     * Gets the permissions of a folder.
     *
     * @param string $directory The path to the folder to get the permissions of.
     *
     * @return string The permissions of the folder in octal format.
     */
    public static function getFolderPermissions($directory)
    {
        return substr(sprintf('%o', fileperms($directory)), -4);
    }

    /**
     * Sets the permissions of a folder.
     *
     * @param string $directory The path to the folder to set the permissions of.
     * @param int $permissions The permissions to set on the folder.
     *
     * @return bool True if the permissions were set successfully.
     */
    public static function setFolderPermissions($directory, $permissions)
    {
        return chmod($directory, $permissions);
    }

    /**
     * Sets the directory for the object.
     *
     * @param string $directory The directory path to set.
     * @return void
     */
    public function setDir($directory)
    {
        $this->directory = $directory;
    }


    public static function displayFolders($directory)
    {
        // Check if the specified directory exists
        if (!is_dir($directory)) {
            echo "Invalid directory specified.";
            return [];
        }

        // Get all the folders in the directory
        $folders = glob($directory . '/*', GLOB_ONLYDIR);

        // Initialize an empty array to store folder names
        $folderNames = [];

        // Add each folder name to the array
        foreach ($folders as $folder) {
            $folderNames[] = basename($folder);
        }

        return $folderNames;
    }
}
