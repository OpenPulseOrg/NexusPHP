<?php

namespace Nxp\Core\Security\Checksum;

use Exception;

/**
 * The FileChecksum class is used to calculate and verify checksums of files using various hash algorithms.
 * Supported algorithms include MD5, SHA1, SHA256, SHA512, and CRC32.
 */
class FileChecksum
{
    private $supportedAlgorithms = array('md5', 'sha1', 'sha256', 'sha512', 'crc32');
    private $algorithm;

    /**
     * FileChecksum constructor.
     *
     * @param string $algorithm Optional. The hash algorithm to use for calculating the checksum.
     *                          Default is 'md5'.
     * @throws Exception If an unsupported hash algorithm is provided.
     */
    public function __construct($algorithm = 'md5')
    {
        if (!in_array($algorithm, $this->supportedAlgorithms)) {
            throw new Exception("Unsupported hash algorithm: $algorithm");
        }

        $this->algorithm = $algorithm;
    }

    /**
     * Set the hash algorithm to be used for calculating the checksum.
     *
     * @param string $algorithm The hash algorithm to use.
     * @throws Exception If an unsupported hash algorithm is provided.
     * @return void
     */
    public function setAlgorithm($algorithm)
    {
        if (!in_array($algorithm, $this->supportedAlgorithms)) {
            throw new Exception("Unsupported hash algorithm: $algorithm");
        }

        $this->algorithm = $algorithm;
    }

    /**
     * Calculate the checksum of a file using the chosen hash algorithm.
     *
     * @param string $filePath The path to the file for which the checksum is to be calculated.
     * @throws Exception If the file does not exist or is not readable.
     * @return string The calculated checksum value.
     */
    public function calculateChecksum($filePath)
    {
        if (!file_exists($filePath)) {
            throw new Exception("File not found: $filePath");
        }

        if (!is_readable($filePath)) {
            throw new Exception("File is not readable: $filePath");
        }

        return hash_file($this->algorithm, $filePath);
    }

    /**
     * Verify the checksum of a file against an expected checksum using the chosen hash algorithm.
     *
     * @param string $filePath        The path to the file for which the checksum is to be verified.
     * @param string $expectedChecksum The expected checksum value to compare against.
     * @throws Exception If the file does not exist or is not readable.
     * @return bool True if the calculated checksum matches the expected checksum, false otherwise.
     */
    public function verifyChecksum($filePath, $expectedChecksum)
    {
        if (!file_exists($filePath)) {
            throw new Exception("File not found: $filePath");
        }

        if (!is_readable($filePath)) {
            throw new Exception("File is not readable: $filePath");
        }

        $calculatedChecksum = hash_file($this->algorithm, $filePath);
        return hash_equals($calculatedChecksum, $expectedChecksum);
    }

    /**
     * Get the list of supported hash algorithms.
     *
     * @return array An array containing the names of the supported hash algorithms.
     */
    public function getSupportedAlgorithms()
    {
        return $this->supportedAlgorithms;
    }
}
