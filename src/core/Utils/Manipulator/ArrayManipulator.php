<?php

namespace Nxp\Core\Utils\Manipulator;

/**
 * ArrayManipulator class for performing various operations on arrays.
 *
 * @package Nxp\Core\Utils\Manipulator
 */
class ArrayManipulator
{
    /**
     * Sorts an array in ascending order.
     *
     * @param array $array The array to be sorted.
     * @return array The sorted array.
     */
    public static function sort(array $array)
    {
        sort($array);
        return $array;
    }

    /**
     * Searches for a value in an array and returns the corresponding key if found.
     *
     * @param array $array The array to be searched.
     * @param mixed $value The value to search for.
     * @return mixed|false The key of the found element, or false if not found.
     */
    public static function search(array $array, $value)
    {
        $key = array_search($value, $array);
        return $key !== false ? $key : false;
    }

    /**
     * Filters an array by applying a callback function to each element.
     *
     * @param array $array The array to be filtered.
     * @param callable $callback The callback function to apply.
     * @return array The filtered array.
     */
    public static function filter(array $array, callable $callback)
    {
        return array_filter($array, $callback);
    }

    /**
     * Merges multiple arrays into a single array.
     *
     * @param array ...$arrays The arrays to be merged.
     * @return array The merged array.
     */
    public static function merge(...$arrays)
    {
        return array_merge(...$arrays);
    }

    /**
     * Transforms the values of an array by applying a callback function.
     *
     * @param array $array The array to be transformed.
     * @param callable $callback The callback function to apply.
     * @return array The transformed array.
     */
    public static function transform(array $array, callable $callback)
    {
        return array_map($callback, $array);
    }

    /**
     * Reverses the order of elements in an array.
     *
     * @param array $array The array to be reversed.
     * @return array The reversed array.
     */
    public static function reverse(array $array)
    {
        return array_reverse($array);
    }

    /**
     * Checks if an array contains a specific value.
     *
     * @param array $array The array to be checked.
     * @param mixed $value The value to check for.
     * @return bool True if the value is found, false otherwise.
     */
    public static function contains(array $array, $value)
    {
        return in_array($value, $array);
    }

    /**
     * Returns the unique values from an array.
     *
     * @param array $array The array to get unique values from.
     * @return array The array with duplicate values removed.
     */
    public static function unique(array $array)
    {
        return array_unique($array);
    }

    /**
     * Slices an array to include only a portion of its elements.
     *
     * @param array $array The array to be sliced.
     * @param int $offset The starting index of the slice.
     * @param int|null $length The length of the slice. If null, all elements from the offset to the end are included.
     * @return array The sliced array.
     */
    public static function slice(array $array, int $offset, ?int $length = null)
    {
        return array_slice($array, $offset, $length);
    }
}
