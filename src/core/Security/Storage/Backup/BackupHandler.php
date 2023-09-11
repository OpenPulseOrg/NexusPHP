<?php

namespace Nxp\Core\Security\Storage\Backup;

use Nxp\Core\Utils\Error\ErrorFactory;
use Nxp\Core\Utils\Service\Container\Container;
use Nxp\Core\Utils\Service\Locator\Locator;use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

/**
 * BackupHandler class provides functionality for generating backups of a root directory.
 *
 * @package Nxp\Core\Security\Storage\Backup
 */
class BackupHandler
{
    private $backupDir;
    private $rootDir;
    private $excludedFolders;
    private $errorHandler;

    /**
     * Constructs a new BackupHandler object.
     *
     * @param string|null $backupDir The directory to store backups. If null, the default backup directory will be used.
     * @param string|null $rootDir The root directory to backup. If null, the default root directory will be used.
     * @param array $excludedFolders An array of directories to exclude from the backup.
     *
     * @return void
     */
    public function __construct($backupDir = null, $rootDir = null, $excludedFolders = [])
    {
        $locator = Locator::getInstance();
        if ($backupDir === null) {
            $backupDir = $locator->getPath("core", "backup"); // Default backup directory
        }
        $this->backupDir = $backupDir;

        if ($rootDir === null) {
            $rootDir = __DIR__ . "/../../"; // Default root directory (two levels up from BackupHandler file)
        }
        $this->rootDir = $rootDir;

        // Set excluded folders
        $this->excludedFolders = $excludedFolders;
    }

    /**
     * Generates a backup of the root directory.
     *
     * Creates a timestamped ZIP archive of the entire root directory and returns the filename of the backup.
     * If the backup directory does not exist or is not writable, a warning will be logged.
     * If the backup file cannot be created, a warning will be logged.
     *
     * @return string The filename of the backup.
     */
    public function generateBackup()
    {
        $container = Container::getInstance();

        $factory = new ErrorFactory($container);
        $this->errorHandler = $factory->createErrorHandler();

        // Create a timestamped filename for the backup
        $backupFilename = 'backup_' . date('Y-m-d_H-i-s') . '.zip';

        // Check if the backup directory exists and is writable
        if (!file_exists($this->backupDir) || !is_writable($this->backupDir)) {
            $this->errorHandler->handleError(
                "Backup Error",
                null,
                [
                    "Message" => "Backup directory does not exist or is not writable",
                    "Backup Directory" => $this->backupDir
                ],
                "WARNING"
            );
        }

        // Create a ZIP archive of the entire root directory
        $zip = new ZipArchive();
        if ($zip->open($this->backupDir . '/' . $backupFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->errorHandler->handleError(
                "Backup Error",
                null,
                [
                    "Message" => "Failed to create backup file",
                    "Backup Directory" => $this->backupDir
                ],
                "WARNING"
            );
        }
        $this->zipDirectory($this->rootDir, $zip);
        $zip->close();

        // Return the filename of the backup
        return $backupFilename;
    }


    /**
     * Adds all files in the directory to the ZIP archive.
     *
     * Adds all files in the `$directory` to the `$zip` archive, ignoring dotfiles, directories, and excluded folders.
     *
     * @param string $directory The directory to zip.
     * @param ZipArchive $zip The ZIP archive to add files to.
     *
     * @return void
     */
    private function zipDirectory($directory, $zip)
    {
        // Add all files in the directory to the ZIP archive
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        foreach ($files as $file) {
            // Ignore dotfiles, directories, and excluded folders
            if (!$file->isFile() || $file->getFilename()[0] === '.' || $this->isExcludedFolder($file)) {
                continue;
            }
            // Add the file to the ZIP archive
            $localPath = substr($file->getPathname(), strlen($this->rootDir));
            $zip->addFile($file->getPathname(), $localPath);
        }
    }

    /**
     * Checks if a file is in an excluded folder.
     *
     * Checks if `$file` is in one of the excluded folders in `$this->excludedFolders`.
     *
     * @param SplFileInfo $file The file to check.
     *
     * @return bool True if the file is in an excluded folder, false otherwise.
     */
    private function isExcludedFolder($file)
    {
        foreach ($this->excludedFolders as $excludedFolder) {
            if (strpos($file->getPath(), "/$excludedFolder/") !== false) {
                return true;
            }
        }
        return false;
    }
}
