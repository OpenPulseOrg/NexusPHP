<?php

namespace Nxp\Core\Utils\Manipulator;

/**
 * URLManipulator class for parsing and manipulating URLs.
 *
 * @package Nxp\Core\Utils\Manipulator
 */
class URLManipulator
{
    /**
     * Parse a URL and return its components.
     *
     * @param string $url The URL to parse.
     * @return array|false An associative array containing the components of the URL, or false on failure.
     */
    public static function parseURL($url)
    {
        return parse_url($url);
    }

    /**
     * Extract the scheme from a URL.
     *
     * @param string $url The URL to extract the scheme from.
     * @return string|false The scheme component of the URL, or false if not found.
     */
    public static function extractScheme($url)
    {
        $components = parse_url($url);
        return isset($components['scheme']) ? $components['scheme'] : false;
    }

    /**
     * Extract the host from a URL.
     *
     * @param string $url The URL to extract the host from.
     * @return string|false The host component of the URL, or false if not found.
     */
    public static function extractHost($url)
    {
        $components = parse_url($url);
        return isset($components['host']) ? $components['host'] : false;
    }

    /**
     * Extract the path from a URL.
     *
     * @param string $url The URL to extract the path from.
     * @return string|false The path component of the URL, or false if not found.
     */
    public static function extractPath($url)
    {
        $components = parse_url($url);
        return isset($components['path']) ? $components['path'] : false;
    }

    /**
     * Extract the query parameters from a URL.
     *
     * @param string $url The URL to extract the query parameters from.
     * @return array An associative array containing the query parameters.
     */
    public static function extractQueryParameters($url)
    {
        $components = parse_url($url);
        parse_str(isset($components['query']) ? $components['query'] : '', $params);
        return $params;
    }

    /**
     * Encode an array of parameters into a URL-encoded string.
     *
     * @param array $parameters The parameters to encode.
     * @return string The URL-encoded string.
     */
    public static function encodeParameters(array $parameters)
    {
        return http_build_query($parameters);
    }

    /**
     * Decode a URL-encoded string into an array of parameters.
     *
     * @param string $queryString The URL-encoded string.
     * @return array An associative array containing the decoded parameters.
     */
    public static function decodeParameters($queryString)
    {
        parse_str($queryString, $params);
        return $params;
    }

    /**
     * Construct a URL based on the given components.
     *
     * @param array $components An associative array containing the components of the URL.
     * @return string The constructed URL.
     */
    public static function constructURL(array $components)
    {
        $url = '';
        if (isset($components['scheme'])) {
            $url .= $components['scheme'] . '://';
        }
        if (isset($components['host'])) {
            $url .= $components['host'];
        }
        if (isset($components['path'])) {
            $url .= $components['path'];
        }
        if (isset($components['query'])) {
            $url .= '?' . $components['query'];
        }
        if (isset($components['fragment'])) {
            $url .= '#' . $components['fragment'];
        }
        return $url;
    }

    /**
     * Append query parameters to a URL.
     *
     * @param string $url The URL to append parameters to.
     * @param array $parameters An associative array of parameters to append.
     * @return string The updated URL with appended query parameters.
     */
    public static function appendQueryParameters($url, array $parameters)
    {
        $queryString = http_build_query($parameters);
        $separator = strpos($url, '?') !== false ? '&' : '?';
        return $url . $separator . $queryString;
    }

    /**
     * Remove query parameters from a URL.
     *
     * @param string $url The URL to remove parameters from.
     * @param array $parameters An array of parameter names to remove.
     * @return string The updated URL with removed query parameters.
     */
    public static function removeQueryParameters($url, array $parameters)
    {
        $parsedUrl = parse_url($url);
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParameters);
            $updatedParameters = array_diff_key($queryParameters, array_flip($parameters));
            if (empty($updatedParameters)) {
                unset($parsedUrl['query']);
            } else {
                $parsedUrl['query'] = http_build_query($updatedParameters);
            }
        }
        return self::constructURL($parsedUrl);
    }

    /**
     * Normalize a URL by removing redundant components and ensuring a consistent format.
     *
     * @param string $url The URL to normalize.
     * @return string The normalized URL.
     */
    public static function normalizeURL($url)
    {
        $parsedUrl = parse_url($url);
        if (isset($parsedUrl['scheme'])) {
            $parsedUrl['scheme'] = strtolower($parsedUrl['scheme']);
        }
        if (isset($parsedUrl['host'])) {
            $parsedUrl['host'] = strtolower($parsedUrl['host']);
        }
        if (isset($parsedUrl['path'])) {
            $parsedUrl['path'] = rtrim($parsedUrl['path'], '/');
        }
        return self::constructURL($parsedUrl);
    }

    /**
     * Get the base URL of a given URL.
     *
     * @param string $url The URL to get the base URL from.
     * @return string|false The base URL, or false if the URL is invalid.
     */
    public static function getBaseURL($url)
    {
        $components = parse_url($url);
        if ($components === false) {
            return false;
        }
        $baseURL = $components['scheme'] . '://' . $components['host'];
        if (isset($components['port'])) {
            $baseURL .= ':' . $components['port'];
        }
        return $baseURL;
    }

    /**
     * Retrieves the current URL.
     *
     * @return string The current URL.
     */
    public static function getCurrentURL()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $path = $_SERVER['REQUEST_URI'];

        $url = $protocol . '://' . $host . $path;

        return $url;
    }


    /**
     * Check if a URL is an absolute URL.
     *
     * @param string $url The URL to check.
     * @return bool True if the URL is absolute, false otherwise.
     */
    public static function isAbsoluteURL($url)
    {
        $components = parse_url($url);
        return isset($components['scheme']) && isset($components['host']);
    }

    /**
     * Check if a URL is a relative URL.
     *
     * @param string $url The URL to check.
     * @return bool True if the URL is relative, false otherwise.
     */
    public static function isRelativeURL($url)
    {
        return !self::isAbsoluteURL($url);
    }

    /**
     * Remove the fragment component from a URL.
     *
     * @param string $url The URL to remove the fragment from.
     * @return string The updated URL without the fragment.
     */
    public static function removeFragment($url)
    {
        $components = parse_url($url);
        unset($components['fragment']);
        return self::constructURL($components);
    }

    /**
     * Check if a URL is valid.
     *
     * @param string $url The URL to check.
     * @return bool True if the URL is valid, false otherwise.
     */
    public static function isValidURL($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Resolve a relative URL against a base URL.
     *
     * @param string $baseUrl The base URL to resolve against.
     * @param string $relativeUrl The relative URL to resolve.
     * @return string|false The resolved URL, or false if the base URL is invalid.
     */
    public static function resolveRelativeURL($baseUrl, $relativeUrl)
    {
        if (!self::isValidURL($baseUrl)) {
            return false;
        }

        $baseComponents = parse_url($baseUrl);
        $resolvedComponents = parse_url($relativeUrl);

        if (isset($resolvedComponents['scheme'])) {
            return $relativeUrl; // The relative URL is already an absolute URL
        }

        $scheme = $baseComponents['scheme'];
        $host = $baseComponents['host'];
        $port = isset($baseComponents['port']) ? ':' . $baseComponents['port'] : '';

        $resolvedUrl = $scheme . '://' . $host . $port;

        // Resolve path
        if (isset($resolvedComponents['path'])) {
            if (substr($resolvedComponents['path'], 0, 1) === '/') {
                $resolvedUrl .= $resolvedComponents['path'];
            } else {
                $resolvedUrl .= '/' . $resolvedComponents['path'];
            }
        } else {
            $resolvedUrl .= $baseComponents['path'];
        }

        // Resolve query
        if (isset($resolvedComponents['query'])) {
            $resolvedUrl .= '?' . $resolvedComponents['query'];
        } elseif (isset($baseComponents['query'])) {
            $resolvedUrl .= '?' . $baseComponents['query'];
        }

        // Resolve fragment
        if (isset($resolvedComponents['fragment'])) {
            $resolvedUrl .= '#' . $resolvedComponents['fragment'];
        }

        return $resolvedUrl;
    }

    /**
     * Extract domain name from a URL.
     *
     * @param string $url The URL to extract the domain name from.
     * @return string|false The extracted domain name, or false if the URL is invalid.
     */
    public static function extractDomain($url)
    {
        $components = parse_url($url);
        if (!isset($components['host'])) {
            return false;
        }
        $host = $components['host'];
        $hostParts = explode('.', $host);
        $numParts = count($hostParts);
        if ($numParts >= 2) {
            $domain = $hostParts[$numParts - 2] . '.' . $hostParts[$numParts - 1];
            if ($numParts >= 3 && strlen($hostParts[$numParts - 3]) > 2) {
                $domain = $hostParts[$numParts - 3] . '.' . $domain;
            }
            return $domain;
        }
        return $host;
    }

    /**
     * Extract the path segments from the current URL.
     *
     * @return array|false An array containing the path segments, or false if not found.
     */
    public static function extractCurrentPathSegments()
    {
        $currentURL = self::getCurrentURL();
        $components = parse_url($currentURL);
        if (isset($components['path'])) {
            $path = trim($components['path'], '/');
            return explode('/', $path);
        }
        return false;
    }


    /**
     * Check if a URL is a subdomain of another URL.
     *
     * @param string $url The URL to check if it is a subdomain.
     * @param string $parentUrl The URL to check against as the parent.
     * @return bool True if the URL is a subdomain of the parent URL, false otherwise.
     */
    public static function isSubdomainOf($url, $parentUrl)
    {
        $urlDomain = self::extractDomain($url);
        $parentDomain = self::extractDomain($parentUrl);

        if ($urlDomain === false || $parentDomain === false) {
            return false;
        }

        return (stripos($urlDomain, $parentDomain) !== false && $urlDomain !== $parentDomain);
    }

    /**
     * Get the URL path hierarchy.
     *
     * @param string $url The URL to get the path hierarchy from.
     * @return array|false An array representing the URL path hierarchy, or false if the URL is invalid.
     */
    public static function getPathHierarchy($url)
    {
        $components = parse_url($url);
        if (!isset($components['path'])) {
            return false;
        }
        $path = trim($components['path'], '/');
        return explode('/', $path);
    }

    /**
     * Get the URL extension.
     *
     * @param string $url The URL to get the extension from.
     * @return string|false The URL extension, or false if the extension is not found or the URL is invalid.
     */
    public static function getExtension($url)
    {
        $path = self::extractPath($url);
        if ($path === false) {
            return false;
        }
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        return $extension !== '' ? $extension : false;
    }

    /**
     * Check if a URL has a specific extension.
     *
     * @param string $url The URL to check.
     * @param string $extension The extension to check for.
     * @return bool True if the URL has the specified extension, false otherwise.
     */
    public static function hasExtension($url, $extension)
    {
        $urlExtension = self::getExtension($url);
        return $urlExtension !== false && strtolower($urlExtension) === strtolower($extension);
    }

    /**
     * Check if a URL is an image URL based on its extension.
     *
     * @param string $url The URL to check.
     * @return bool True if the URL is an image URL, false otherwise.
     */
    public static function isImageURL($url)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
        return self::hasExtension($url, $imageExtensions);
    }

    /**
     * Generate a URL slug from a string.
     *
     * @param string $string The string to convert into a slug.
     * @return string The generated slug.
     */
    public static function generateSlug($string)
    {
        $slug = preg_replace('/[^a-z0-9-]+/', '-', strtolower($string));
        $slug = trim($slug, '-');
        return $slug;
    }

    /**
     * Generate a URL-friendly string by removing special characters and replacing spaces with dashes.
     *
     * @param string $string The string to convert into a URL-friendly string.
     * @return string The URL-friendly string.
     */
    public static function generateURLString($string)
    {
        $urlString = preg_replace('/[^a-z0-9]+/', ' ', strtolower($string));
        $urlString = trim($urlString);
        $urlString = str_replace(' ', '-', $urlString);
        return $urlString;
    }

    /**
     * Get the last segment of a URL path.
     *
     * @param string $url The URL to extract the last segment from.
     * @return string|false The last segment of the URL path, or false if the URL is invalid or has no path.
     */
    public static function getLastPathSegment($url)
    {
        $path = self::extractPath($url);
        if ($path !== false) {
            $segments = explode('/', $path);
            return end($segments);
        }
        return false;
    }

    /**
     * Check if a URL contains a specific query parameter.
     *
     * @param string $url The URL to check.
     * @param string $parameter The query parameter to check for.
     * @return bool True if the URL contains the specified query parameter, false otherwise.
     */
    public static function hasQueryParameter($url, $parameter)
    {
        $queryParameters = self::extractQueryParameters($url);
        return isset($queryParameters[$parameter]);
    }

    /**
     * Remove the specified query parameter from a URL.
     *
     * @param string $url The URL to remove the query parameter from.
     * @param string $parameter The query parameter to remove.
     * @return string The updated URL with the query parameter removed.
     */
    public static function removeQueryParameter($url, $parameter)
    {
        $parsedUrl = parse_url($url);
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParameters);
            unset($queryParameters[$parameter]);
            if (empty($queryParameters)) {
                unset($parsedUrl['query']);
            } else {
                $parsedUrl['query'] = http_build_query($queryParameters);
            }
        }
        return self::constructURL($parsedUrl);
    }
}
