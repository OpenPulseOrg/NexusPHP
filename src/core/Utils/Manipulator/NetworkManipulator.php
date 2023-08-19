<?php

namespace Nxp\Core\Utils\Manipulator;

/**
 * NetworkManipulator class for performing various network-related operations.
 *
 * @package Nxp\Core\Utils\Manipulator
 */
class NetworkManipulator
{
    /**
     * Make an HTTP request.
     *
     * @param string $url The URL to send the request to.
     * @param string $method The HTTP method (GET, POST, etc.).
     * @param array $headers An array of HTTP headers.
     * @param array $data An array of data to send with the request.
     * @return string The response from the HTTP request.
     */
    public static function makeHttpRequest($url, $method = 'GET', $headers = [], $data = [])
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * Handle the response from an HTTP request.
     *
     * @param string $response The response from the HTTP request.
     * @return mixed The parsed response data.
     */
    public static function handleHttpResponse($response)
    {
        // Example: Parse JSON response
        return json_decode($response, true);
    }

    /**
     * Send data over a socket connection.
     *
     * @param string $host The host to connect to.
     * @param int $port The port to connect to.
     * @param string $data The data to send.
     * @return string The response from the socket.
     */
    public static function sendDataOverSocket($host, $port, $data)
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_connect($socket, $host, $port);

        socket_write($socket, $data, strlen($data));
        $response = socket_read($socket, 1024);

        socket_close($socket);

        return $response;
    }

    /**
     * Receive data over a socket connection.
     *
     * @param string $host The host to connect to.
     * @param int $port The port to connect to.
     * @return string The received data from the socket.
     */
    public static function receiveDataOverSocket($host, $port)
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_connect($socket, $host, $port);

        $response = socket_read($socket, 1024);

        socket_close($socket);

        return $response;
    }

    /**
     * Resolve a domain name to an IP address.
     *
     * @param string $domain The domain name to resolve.
     * @return string The IP address of the domain.
     */
    public static function resolveDns($domain)
    {
        $ip = gethostbyname($domain);

        return $ip;
    }

    /**
     * Check network connectivity.
     *
     * @param string $host The host to check connectivity to.
     * @return bool True if the host is reachable, false otherwise.
     */
    public static function checkNetworkConnectivity($host)
    {
        $ping = exec(sprintf('ping -c 1 -W 2 %s', escapeshellarg($host)), $output, $result);

        return ($result === 0);
    }

    /**
     * Manage network configurations.
     *
     * @param string $action The action to perform (e.g., set, get, reset).
     * @param array $config The network configuration data.
     * @return bool True if the network configuration was successfully managed, false otherwise.
     */
    public static function manageNetworkConfigurations($action, $config)
    {
        // Example: Save network configuration to a file
        if ($action === 'set') {
            $configJson = json_encode($config);
            file_put_contents('network_config.json', $configJson);
            return true;
        }

        // Example: Get network configuration from a file
        if ($action === 'get') {
            $configJson = file_get_contents('network_config.json');
            $config = json_decode($configJson, true);
            return $config;
        }

        // Example: Reset network configuration
        if ($action === 'reset') {
            unlink('network_config.json');
            return true;
        }

        return false;
    }

    /**
     * Check if a given port is open on a host.
     *
     * @param string $host The host to check.
     * @param int $port The port to check.
     * @return bool True if the port is open, false otherwise.
     */
    public static function checkPortAvailability($host, $port)
    {
        $fp = @fsockopen($host, $port, $errno, $errstr, 2);
        if ($fp) {
            fclose($fp);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the public IP address of the current machine.
     *
     * @return string The public IP address.
     */
    public static function getPublicIpAddress()
    {
        $ip = file_get_contents('https://api.ipify.org');
        return trim($ip);
    }

    /**
     * Download a file from a remote server.
     *
     * @param string $url The URL of the file to download.
     * @param string $destination The local file path to save the downloaded file.
     * @return bool True if the file was downloaded successfully, false otherwise.
     */
    public static function downloadFile($url, $destination)
    {
        $result = file_put_contents($destination, file_get_contents($url));
        return ($result !== false);
    }

    /**
     * Upload a file to a remote server using FTP.
     *
     * @param string $ftpServer The FTP server hostname.
     * @param string $ftpUsername The FTP username.
     * @param string $ftpPassword The FTP password.
     * @param string $localFile The local file path to upload.
     * @param string $remoteDir The remote directory to upload the file to.
     * @return bool True if the file was uploaded successfully, false otherwise.
     */
    public static function uploadFileViaFtp($ftpServer, $ftpUsername, $ftpPassword, $localFile, $remoteDir)
    {
        $ftpConn = ftp_connect($ftpServer);
        if (!$ftpConn) {
            return false;
        }

        $loginResult = ftp_login($ftpConn, $ftpUsername, $ftpPassword);
        if (!$loginResult) {
            ftp_close($ftpConn);
            return false;
        }

        $remoteFile = $remoteDir . '/' . basename($localFile);
        $uploadResult = ftp_put($ftpConn, $remoteFile, $localFile, FTP_BINARY);

        ftp_close($ftpConn);

        return $uploadResult;
    }

    /**
     * Execute a shell command on the local machine.
     *
     * @param string $command The shell command to execute.
     * @return string The output of the command.
     */
    public static function executeShellCommand($command)
    {
        $output = shell_exec($command);
        return trim($output);
    }
}
