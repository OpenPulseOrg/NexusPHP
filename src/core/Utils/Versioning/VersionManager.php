<?php

namespace Nxp\Core\Utils\Versioning;

class VersionManager
{
    private static $versionFile = 'version.txt';
    private static $major = 1;
    private static $minor = 0;
    private static $patch = 0;

    // Read the version from the file or initialize if not present.
    private static function readVersion(): void
    {
        if (file_exists(self::$versionFile)) {
            $version = file_get_contents(self::$versionFile);
            list(self::$major, self::$minor, self::$patch) = explode('.', $version);
        }
    }

    // Write the current version to the file.
    private static function writeVersion(): void
    {
        $version = sprintf('%d.%d.%d', self::$major, self::$minor, self::$patch);
        file_put_contents(self::$versionFile, $version);
    }

    public static function getVersion(): string
    {
        self::readVersion();
        return sprintf('%d.%d.%d', self::$major, self::$minor, self::$patch);
    }

    public static function incrementMajor(): void
    {
        self::$major += 1;
        self::$minor = 0;
        self::$patch = 0;
        self::writeVersion();
    }

    public static function incrementMinor(): void
    {
        self::$minor += 1;
        self::$patch = 0;
        self::writeVersion();
    }

    public static function incrementPatch(): void
    {
        self::$patch += 1;
        self::writeVersion();
    }
}
