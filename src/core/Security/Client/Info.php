<?php

namespace Nxp\Core\Security\Client;

/**
 * Info class provides methods to retrieve information about the client's browser, device, and network.
 *
 * @package Nxp\Core\Security\Client
 */
class Info
{
    /**
     * Gets the user's browser and version based on their user agent.
     *
     * Determines the user's browser and version based on their user agent in `$_SERVER['HTTP_USER_AGENT']`. 
     *
     * @return string The user's browser and version.
     */
    public static function getBrowser()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $browser = 'Unknown';
        $version = '';

        if (preg_match('/MSIE/i', $agent) && !preg_match('/Opera/i', $agent)) {
            $browser = 'Internet Explorer';
            $version = preg_replace('/.*MSIE([\d\.]+).*?/', '$1', $agent);
        } elseif (preg_match('/Firefox/i', $agent)) {
            $browser = 'Mozilla Firefox';
            $version = preg_replace('/.*Firefox\/([\d\.]+).*?/', '$1', $agent);
        } elseif (preg_match('/Chrome/i', $agent)) {
            $browser = 'Google Chrome';
            $version = preg_replace('/.*Chrome\/([\d\.]+).*?/', '$1', $agent);
        } elseif (preg_match('/Safari/i', $agent)) {
            $browser = 'Apple Safari';
            $version = preg_replace('/.*Version\/([\d\.]+).*?/', '$1', $agent);
        } elseif (preg_match('/Opera/i', $agent)) {
            $browser = 'Opera';
            $version = preg_replace('/.*Version\/([\d\.]+).*?/', '$1', $agent);
        }

        return $browser . ' Version ' . $version;
    }

    /**
     * Gets the user's IP address.
     *
     * Determines the user's IP address based on `$_SERVER['HTTP_CLIENT_IP']`, `$_SERVER['HTTP_X_FORWARDED_FOR']`, or `$_SERVER['REMOTE_ADDR']`.
     *
     * @return string The user's IP address.
     */
    public static function getIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }


    /**
     * Retrieves the language of the browser based on the "Accept-Language" header.
     *
     * @return string The language code of the browser.
     */
    public static function getLanguage()
    {
        return $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    }

    /**
     * Retrieves the operating system platform of the user's device based on the "User-Agent" header.
     *
     * @return string The name of the operating system platform or "Unknown" if it cannot be determined.
     */
    public static function getPlatform()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $platform = 'Unknown';

        if (preg_match('/Windows/i', $agent)) {
            $platform = 'Windows';
        } elseif (preg_match('/Macintosh/i', $agent)) {
            $platform = 'Macintosh';
        } elseif (preg_match('/Linux/i', $agent)) {
            $platform = 'Linux';
        }

        return $platform;
    }

    /**
     * Retrieves the screen resolution of the user's device using JavaScript.
     *
     * @return string The screen resolution in the format "width x height".
     */
    public static function getScreenResolution()
    {
        return '<script>document.write(screen.width + "x" + screen.height)</script>';
    }

    /**
     * Retrieves the URL of the page that referred the user to the current page.
     *
     * @return string The URL of the referring page or an empty string if it cannot be determined.
     */
    public static function getReferrer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    }

    /**
     * Retrieves the type of device the user is using based on the "User-Agent" header.
     *
     * @return string The type of device, such as "Mobile", "Tablet", "PC", or "Unknown".
     */
    public static function getDeviceType()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $deviceType = 'Unknown';

        if (preg_match('/Mobile/i', $agent)) {
            $deviceType = 'Mobile';
        } elseif (preg_match('/Tablet/i', $agent)) {
            $deviceType = 'Tablet';
        } elseif (preg_match('/PC/i', $agent)) {
            $deviceType = 'PC';
        }

        return $deviceType;
    }


    /**
     * Retrieves the brand of the user's device based on the "User-Agent" header.
     *
     * @return string The brand of the device, such as "Apple", "Samsung", "LG", "HTC", "Google", or an empty string if it cannot be determined.
     */
    public static function getDeviceBrand()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $deviceBrand = '';

        if (preg_match('/iPhone/i', $agent)) {
            $deviceBrand = 'Apple';
        } elseif (preg_match('/iPad/i', $agent)) {
            $deviceBrand = 'Apple';
        } elseif (preg_match('/Samsung/i', $agent)) {
            $deviceBrand = 'Samsung';
        } elseif (preg_match('/LG/i', $agent)) {
            $deviceBrand = 'LG';
        } elseif (preg_match('/HTC/i', $agent)) {
            $deviceBrand = 'HTC';
        } elseif (preg_match('/Nexus/i', $agent)) {
            $deviceBrand = 'Google';
        } elseif (preg_match('/Pixel/i', $agent)) {
            $deviceBrand = 'Google';
        }

        return $deviceBrand;
    }

    /**
     * Retrieves the model of the user's device based on the "User-Agent" header.
     *
     * @return string The model of the device, such as "iPhone", "iPad", "Galaxy", "G", "One", "Nexus", "Pixel", or an empty string if it cannot be determined.
     */
    public static function getDeviceModel()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $deviceModel = '';

        if (preg_match('/iPhone/i', $agent)) {
            $deviceModel = 'iPhone';
        } elseif (preg_match('/iPad/i', $agent)) {
            $deviceModel = 'iPad';
        } elseif (preg_match('/Samsung/i', $agent)) {
            $deviceModel = 'Galaxy';
        } elseif (preg_match('/LG/i', $agent)) {
            $deviceModel = 'G';
        } elseif (preg_match('/HTC/i', $agent)) {
            $deviceModel = 'One';
        } elseif (preg_match('/Nexus/i', $agent)) {
            $deviceModel = 'Nexus';
        } elseif (preg_match('/Pixel/i', $agent)) {
            $deviceModel = 'Pixel';
        }

        return $deviceModel;
    }

    /**
     * Retrieves the country of the user based on their IP address using the ipinfo.io API.
     *
     * @return string The name of the country or "Unknown" if it cannot be determined.
     */
    public static function getCountry()
    {
        $ip = self::getIP();
        $details = json_decode(file_get_contents("https://ipinfo.io/{$ip}"));

        return $details ? $details->country : 'Unknown';
    }

    /**
     * Retrieves the hostname of the user's IP address using the ipinfo.io API.
     *
     * @return string The hostname of the IP address or "Unknown" if it cannot be determined.
     */
    public static function getHostname()
    {
        $ip = self::getIP();
        $details = json_decode(file_get_contents("https://ipinfo.io/{$ip}"));

        return $details ? $details->hostname : 'Unknown';
    }

    /**
     * Retrieves the city of the user based on their IP address using the ipinfo.io API.
     *
     * @return string The name of the city or "Unknown" if it cannot be determined.
     */
    public static function getCity()
    {
        $ip = self::getIP();
        $details = json_decode(file_get_contents("https://ipinfo.io/{$ip}"));

        return $details ? $details->city : 'Unknown';
    }
    /**
     * Retrieves the region of the user based on their IP address using the ipinfo.io API.
     *
     * @return string The name of the region or "Unknown" if it cannot be determined.
     */
    public static function getRegion()
    {
        $ip = self::getIP();
        $details = json_decode(file_get_contents("https://ipinfo.io/{$ip}"));

        return $details ? $details->region : 'Unknown';
    }

    /**
     * Retrieves the latitude and longitude of the user's IP address using the ipinfo.io API.
     *
     * @return string The latitude and longitude in the format "latitude,longitude" or "Unknown" if it cannot be determined.
     */
    public static function getLoc()
    {
        $ip = self::getIP();
        $details = json_decode(file_get_contents("https://ipinfo.io/{$ip}"));

        return $details ? $details->loc : 'Unknown';
    }

    /**
     * Retrieves the organization associated with the user's IP address using the ipinfo.io API.
     *
     * @return string The name of the organization or "Unknown" if it cannot be determined.
     */
    public static function getOrg()
    {
        $ip = self::getIP();
        $details = json_decode(file_get_contents("https://ipinfo.io/{$ip}"));

        return $details ? $details->org : 'Unknown';
    }
    /**
     * Retrieves the postal code of the user based on their IP address using the ipinfo.io API.
     *
     * @return string The postal code or "Unknown" if it cannot be determined.
     */
    public static function getPostal()
    {
        $ip = self::getIP();
        $details = json_decode(file_get_contents("https://ipinfo.io/{$ip}"));

        return $details ? $details->postal : 'Unknown';
    }

    /**
     * Retrieves the timezone of the user based on their IP address using the ipinfo.io API.
     *
     * @return string The timezone name or "Unknown" if it cannot be determined.
     */
    public static function getTimezone()
    {
        $ip = self::getIP();
        $details = json_decode(file_get_contents("https://ipinfo.io/{$ip}"));

        return $details ? $details->timezone : 'Unknown';
    }

    /**
     * Retrieves the operating system of the user's device based on the "User-Agent" header.
     *
     * @return string The name of the operating system or "Unknown" if it cannot be determined.
     */
    public static function getOperatingSystem()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $os = 'Unknown';

        if (preg_match('/Windows NT 10/i', $agent)) {
            $os = 'Windows 10';
        } elseif (preg_match('/Windows NT 6\.3/i', $agent)) {
            $os = 'Windows 8.1';
        } elseif (preg_match('/Windows NT 6\.2/i', $agent)) {
            $os = 'Windows 8';
        } elseif (preg_match('/Windows NT 6\.1/i', $agent)) {
            $os = 'Windows 7';
        } elseif (preg_match('/Windows NT 6\.0/i', $agent)) {
            $os = 'Windows Vista';
        } elseif (preg_match('/Windows NT 5\.1/i', $agent)) {
            $os = 'Windows XP';
        } elseif (preg_match('/Windows NT 5\.0/i', $agent)) {
            $os = 'Windows 2000';
        } elseif (preg_match('/Macintosh/i', $agent)) {
            $os = 'Macintosh';
        } elseif (preg_match('/Linux/i', $agent)) {
            $os = 'Linux';
        }

        return $os;
    }

    /**
     * Retrieves the device resolution of the user's device.
     *
     * @return string The device resolution or "Unknown" if it cannot be determined.
     */
    public static function getDeviceResolution()
    {
        $resolution = 'Unknown';

        if (isset($_SERVER['HTTP_CLIENT_HX_RES'])) {
            $resolution = $_SERVER['HTTP_CLIENT_HX_RES'];
        } elseif (isset($_SERVER['HTTP_X_HX_RES'])) {
            $resolution = $_SERVER['HTTP_X_HX_RES'];
        } elseif (isset($_SERVER['HTTP_X_RESOLUTION'])) {
            $resolution = $_SERVER['HTTP_X_RESOLUTION'];
        } elseif (isset($_SERVER['HTTP_RESOLUTION'])) {
            $resolution = $_SERVER['HTTP_RESOLUTION'];
        }

        return $resolution;
    }

    /**
     * Retrieves the browser language preference of the user.
     *
     * @return string The browser language or "Unknown" if it cannot be determined.
     */
    public static function getBrowserLanguage()
    {
        $languages = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $preferredLanguage = '';

        if ($languages) {
            $languages = explode(',', $languages);
            $preferredLanguage = $languages[0];
        }

        return $preferredLanguage;
    }

    /**
     * Retrieves the user agent string of the client.
     *
     * @return string The user agent string.
     */
    public static function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * Retrieves the host of the referring page.
     *
     * @return string The host of the referring page or "Unknown" if it cannot be determined.
     */
    public static function getRefererHost()
    {
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $host = parse_url($referer, PHP_URL_HOST);

        return $host ? $host : 'Unknown';
    }

    /**
     * Checks if the client is using a mobile device.
     *
     * @return bool True if the client is using a mobile device, false otherwise.
     */
    public static function isMobileDevice()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        return preg_match('/Mobile/i', $agent) ? true : false;
    }

    /**
     * Checks if the client is using a tablet device.
     *
     * @return bool True if the client is using a tablet device, false otherwise.
     */
    public static function isTabletDevice()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        return preg_match('/Tablet/i', $agent) ? true : false;
    }

    /**
     * Retrieves the screen DPI (dots per inch) of the user's device.
     *
     * @return string The screen DPI or "Unknown" if it cannot be determined.
     */
    public static function getScreenDPI()
    {
        return $_SERVER['HTTP_DPR'] ?? 'Unknown';
    }

    /**
     * Retrieves the vendor of the user's device.
     *
     * @return string The device vendor or an empty string if it cannot be determined.
     */
    public static function getDeviceVendor()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $deviceVendor = '';

        if (preg_match('/Apple/i', $agent)) {
            $deviceVendor = 'Apple';
        } elseif (preg_match('/Samsung/i', $agent)) {
            $deviceVendor = 'Samsung';
        } elseif (preg_match('/LG/i', $agent)) {
            $deviceVendor = 'LG';
        } elseif (preg_match('/HTC/i', $agent)) {
            $deviceVendor = 'HTC';
        } elseif (preg_match('/Google/i', $agent)) {
            $deviceVendor = 'Google';
        }

        return $deviceVendor;
    }

    /**
     * Checks if the client is a bot.
     *
     * @return bool True if the client is a bot, false otherwise.
     */
    public static function isBot()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $botPatterns = [
            'bot', 'crawl', 'spider', 'slurp', 'mediapartners', 'scrapy', 'curl', 'wget'
        ];

        foreach ($botPatterns as $pattern) {
            if (stripos($agent, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if cookies are enabled in the client's browser.
     *
     * @return bool True if cookies are enabled, false otherwise.
     */
    public static function getBrowserCookiesEnabled()
    {
        return isset($_SERVER['HTTP_COOKIE']) ? true : false;
    }

    /**
     * Retrieves the plugins installed in the client's browser.
     *
     * @return string The browser plugins or "Unknown" if it cannot be determined.
     */
    public static function getBrowserPlugins()
    {
        $plugins = $_SERVER['HTTP_ACCEPT'];
        return $plugins ? $plugins : 'Unknown';
    }

    /**
     * Retrieves the device orientation.
     *
     * @return string The device orientation or "Unknown" if it cannot be determined.
     */
    public static function getDeviceOrientation()
    {
        $orientation = 'Unknown';

        if (isset($_SERVER['HTTP_SCREEN_ORIENTATION'])) {
            $orientation = $_SERVER['HTTP_SCREEN_ORIENTATION'];
        }

        return $orientation;
    }

    /**
     * Retrieves the cookies sent by the client's browser.
     *
     * @return array The browser cookies or an empty array if no cookies are found.
     */
    public static function getBrowserCookies()
    {
        $cookies = $_COOKIE;
        return $cookies ? $cookies : [];
    }

    /**
     * Retrieves the battery level of the user's device.
     *
     * @return string The battery level or "Unknown" if it cannot be determined.
     */
    public static function getDeviceBatteryLevel()
    {
        $batteryLevel = $_SERVER['HTTP_BATTERY_LEVEL'] ?? 'Unknown';
        return $batteryLevel;
    }

    /**
     * Retrieves the amount of RAM (Random Access Memory) of the user's device.
     *
     * @return string The amount of RAM or "Unknown" if it cannot be determined.
     */
    public static function getDeviceRAM()
    {
        $ram = $_SERVER['HTTP_DEVICE_RAM'] ?? 'Unknown';
        return $ram;
    }

    /**
     * Retrieves the number of browser plugins installed.
     *
     * @return int The number of browser plugins.
     */
    public static function getBrowserPluginsCount()
    {
        $plugins = $_SERVER['HTTP_ACCEPT'];
        if ($plugins) {
            $plugins = explode(',', $plugins);
            return count($plugins);
        }
        return 0;
    }

    /**
     * Retrieves the type of connection the client has with the server.
     *
     * @return string The connection type or "Unknown" if it cannot be determined.
     */
    public static function getDeviceConnectionType()
    {
        $connectionType = $_SERVER['HTTP_CONNECTION'] ?? 'Unknown';
        return $connectionType;
    }

    /**
     * Retrieves the number of browser cookies sent by the client's browser.
     *
     * @return int The number of browser cookies.
     */
    public static function getBrowserCookiesCount()
    {
        $cookies = $_COOKIE;
        return count($cookies);
    }

    /**
     * Retrieves the battery status of the user's device.
     *
     * @return string The battery status or "Unknown" if it cannot be determined.
     */
    public static function getDeviceBatteryStatus()
    {
        $batteryStatus = $_SERVER['HTTP_BATTERY_STATUS'] ?? 'Unknown';
        return $batteryStatus;
    }

    /**
     * Retrieves the Accept header value sent by the client's browser.
     *
     * @return string The Accept header value or "Unknown" if it cannot be determined.
     */
    public static function getBrowserAccept()
    {
        return $_SERVER['HTTP_ACCEPT'] ?? 'Unknown';
    }

    /**
     * Retrieves the Accept-Encoding header value sent by the client's browser.
     *
     * @return string The Accept-Encoding header value or "Unknown" if it cannot be determined.
     */
    public static function getBrowserAcceptEncoding()
    {
        return $_SERVER['HTTP_ACCEPT_ENCODING'] ?? 'Unknown';
    }

    /**
     * Retrieves the Accept-Charset header value sent by the client's browser.
     *
     * @return string The Accept-Charset header value or "Unknown" if it cannot be determined.
     */
    public static function getBrowserAcceptCharset()
    {
        return $_SERVER['HTTP_ACCEPT_CHARSET'] ?? 'Unknown';
    }

    /**
     * Retrieves the Accept-Language header value sent by the client's browser.
     *
     * @return string The Accept-Language header value or "Unknown" if it cannot be determined.
     */
    public static function getBrowserAcceptLanguage()
    {
        return $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'Unknown';
    }

    /**
     * Retrieves the server protocol used in the current request.
     *
     * @return string The server protocol or "Unknown" if it cannot be determined.
     */
    public static function getServerProtocol()
    {
        return $_SERVER['SERVER_PROTOCOL'] ?? 'Unknown';
    }

    /**
     * Retrieves the headers sent by the client.
     *
     * @return array The client headers as an associative array.
     */
    public static function getClientHeaders()
    {
        $headers = [];

        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $header = str_replace('HTTP_', '', $key);
                $header = str_replace('_', ' ', $header);
                $header = ucwords(strtolower($header));
                $headers[$header] = $value;
            }
        }

        return $headers;
    }
}
