<?php

/**
 * Returns an associative array with configuration settings for the application.
 *
 * This code defines various configuration settings used by the application, including the title,
 * file uploading settings, and backup data location. The settings are returned as an associative array.
 *
 * @return array An associative array containing the configuration settings.
 */
return [

    // General Settings
    'general' => [
        'core_title' => 'NexusPHP',
        'base_path' => '/',
    ],

    // System Settings

    'system' => [
        "time_zone" => "Europe/London",
        'default_language' => 'en'
    ],

    // File Uploading Settings
    'file_upload' => [
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'docx', 'xlsx'],
        'max_size' => 5242880,  // 5MB in bytes
        'max_filename_length' => 255,
        'max_retries' => 10,
        'retry_delay' => 100000,  // in microseconds
    ],

    // Sentry Settings
    'sentry' => [
        'use' => false,
        'dsn' => '',
    ],

    // Crowdin Settings
    'crowdin' => [
        'base_url' => '',
        'project_id' => '',
        'api_key' => '',
    ],
];
