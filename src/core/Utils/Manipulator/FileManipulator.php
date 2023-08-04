<?php

namespace Nxp\Core\Utils\Manipulator;

class FileManipulator
{
    /**
     * Get the date from a log file name.
     *
     * @param string $filename The log file name.
     * @return string|null The date in the format 'YYYY-MM-DD', or null if the date cannot be extracted.
     */
    public static function getDateFromFile($filename)
    {
        $filename = basename($filename);
        $matches = [];

        // Extract date from the filename using regular expression
        if (preg_match('/(\d{4}-\d{2}-\d{2})/', $filename, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
