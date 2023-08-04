<?php

namespace Nxp\Core\Utils\Manipulator;

/**
 * JsonManipulator class for performing operations on JSON data.
 *
 * @package Nxp\Core\Utils\Manipulator
 */
class JsonManipulator
{
    /**
     * Reads a JSON file and returns the decoded data.
     *
     * @param string $filename The path to the JSON file.
     * @return mixed|null The decoded JSON data, or null if the file could not be read or decoded.
     */
    public static function readJsonFile($filename)
    {
        $jsonData = file_get_contents($filename);
        if ($jsonData === false) {
            return null;
        }

        return json_decode($jsonData, true);
    }

    /**
     * Writes data to a JSON file.
     *
     * @param string $filename The path to the JSON file.
     * @param mixed $data The data to be encoded and written to the file.
     * @return bool|int False on failure, or the number of bytes written on success.
     */
    public static function writeJsonFile($filename, $data)
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        if ($jsonData === false) {
            return false;
        }

        return file_put_contents($filename, $jsonData);
    }

    /**
     * Retrieves the value of a key from a JSON string or array.
     *
     * @param mixed $json The JSON string or array.
     * @param string $key The key to retrieve the value for.
     * @return mixed|null The value of the key, or null if the key does not exist.
     */
    public static function getJsonValue($json, $key)
    {
        if (!is_array($json)) {
            $json = json_decode($json, true);
            if ($json === null) {
                return null;
            }
        }

        return isset($json[$key]) ? $json[$key] : null;
    }

    /**
     * Sets the value of a key in a JSON string or array.
     *
     * @param mixed $json The JSON string or array.
     * @param string $key The key to set the value for.
     * @param mixed $value The value to set.
     * @return void
     */
    public static function setJsonValue(&$json, $key, $value)
    {
        if (!is_array($json)) {
            $json = json_decode($json, true);
            if ($json === null) {
                $json = [];
            }
        }

        $json[$key] = $value;
    }

    /**
     * Removes a key-value pair from a JSON string or array.
     *
     * @param mixed $json The JSON string or array.
     * @param string $key The key to remove.
     * @return void
     */
    public static function unsetJsonValue(&$json, $key)
    {
        if (!is_array($json)) {
            return;
        }

        unset($json[$key]);
    }

    /**
     * Merges two JSON strings or arrays.
     *
     * @param mixed $json1 The first JSON string or array.
     * @param mixed $json2 The second JSON string or array.
     * @return mixed|null The merged JSON data, or null if both inputs are invalid.
     */
    public static function mergeJsonData($json1, $json2)
    {
        $data1 = self::decodeJson($json1);
        $data2 = self::decodeJson($json2);

        if ($data1 === null && $data2 === null) {
            return null;
        } elseif ($data1 === null) {
            return $data2;
        } elseif ($data2 === null) {
            return $data1;
        }

        return array_merge_recursive($data1, $data2);
    }

    /**
     * Filters a JSON string or array by a specific key.
     *
     * @param mixed $json The JSON string or array.
     * @param string $key The key to filter by.
     * @return mixed|null The filtered JSON data, or null if the input is invalid.
     */
    public static function filterJsonByKey($json, $key)
    {
        $data = self::decodeJson($json);

        if ($data === null) {
            return null;
        }

        return array_filter($data, function ($item) use ($key) {
            return array_key_exists($key, $item);
        });
    }

    /**
     * Sorts a JSON string or array by a specific key.
     *
     * @param mixed $json The JSON string or array.
     * @param string $key The key to sort by.
     * @param int $order The sort order (SORT_ASC or SORT_DESC).
     * @return mixed|null The sorted JSON data, or null if the input is invalid.
     */
    public static function sortJsonByKey($json, $key, $order = SORT_ASC)
    {
        $data = self::decodeJson($json);

        if ($data === null) {
            return null;
        }

        $keyValues = array_column($data, $key);
        array_multisort($keyValues, $order, $data);

        return $data;
    }

    /**
     * Decodes a JSON string into an array.
     *
     * @param mixed $json The JSON string.
     * @return array|null The decoded JSON data as an array, or null if the input is invalid.
     */
    private static function decodeJson($json)
    {
        if (is_array($json)) {
            return $json;
        }

        $data = json_decode($json, true);

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return $data;
    }
}
