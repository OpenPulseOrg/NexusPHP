<?php

/**
 * Returns an associative array with configuration settings for the application.
 *
 * This code defines various configuration settings used by the application, including the title,
 * file uploading settings, and backup data location. The settings are returned as an associative array.
 *
 * @return array An associative array containing the configuration settings.
 */
return array(
    // General Settings
    "CORE_TITLE" => "NexusPHP",
    "COMMUNITY_NAME" => "NexusPHP",
    "PRELOADER_TRUE" => false,
    "BASE_PATH" => "/",
    "TIME_ZONE" => 'Europe/London',
    "CURRENT_VERSION" => "0.0.1", // Please ensure you leave this else you may corrupt your instance.

    // File Uploading Settings
    "ALLOWED_FILE_TYPES" => ["jpg", "jpeg", "png", "gif", "pdf", "docx", "xlsx"],
    "MAX_ALLOWED_FILE_SIZE" => 5242880, // 5MB in bytes
    "MAX_FILENAME_LENGTH" => 255, // Change this to your desired maximum filename length
    "MAX_RETRIES" => 10, // Change this to your desired maximum number of retries
    "RETRY_DELAY" => 100000, // Change this to your desired retry delay in microseconds

    // Backup Settings
    "BACKUP_ZIP_LOCATION" => "/src/data/core_backup",

    "USE_SENTRY" => false,
    "SENTRY_DSN" => ""
);
