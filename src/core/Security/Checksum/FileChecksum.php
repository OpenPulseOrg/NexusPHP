<?php

namespace Nxp\Core\Security\Checksum;

use Exception;

class FileChecksum
{
    private $supportedAlgorithms = array('md5', 'sha1', 'sha256', 'sha512', 'crc32');
    private $algorithm;

    public function __construct($algorithm = 'md5') {
        if (!in_array($algorithm, $this->supportedAlgorithms)) {
            throw new Exception("Unsupported hash algorithm: $algorithm");
        }

        $this->algorithm = $algorithm;
    }

    public function setAlgorithm($algorithm) {
        if (!in_array($algorithm, $this->supportedAlgorithms)) {
            throw new Exception("Unsupported hash algorithm: $algorithm");
        }

        $this->algorithm = $algorithm;
    }

    public function calculateChecksum($filePath) {
        if (!file_exists($filePath)) {
            throw new Exception("File not found: $filePath");
        }

        if (!is_readable($filePath)) {
            throw new Exception("File is not readable: $filePath");
        }

        return hash_file($this->algorithm, $filePath);
    }

    public function verifyChecksum($filePath, $expectedChecksum) {
        if (!file_exists($filePath)) {
            throw new Exception("File not found: $filePath");
        }

        if (!is_readable($filePath)) {
            throw new Exception("File is not readable: $filePath");
        }

        $calculatedChecksum = hash_file($this->algorithm, $filePath);
        return hash_equals($calculatedChecksum, $expectedChecksum);
    }

    public function getSupportedAlgorithms() {
        return $this->supportedAlgorithms;
    }
}
