<?php

namespace Nxp\Core\Security\Cryptography;

use Nxp\Core\Security\Logging\Logger;

use Defuse\Crypto\{
    Exception\EnvironmentIsBrokenException,
    Crypto,
    Key
};
use Nxp\Core\Database\Factories\Query;
use Nxp\Core\Utils\Service\Container;

/**
 * Encryptor class provides methods for encrypting and decrypting messages using the Defuse Crypto library.
 *
 * @package Nxp\Core\Security\Cryptography
 */
class Encryptor
{
    private static $key;

    /**
     * Creates a new instance of the Encryptor class.
     * If the encryption key is not set, generates a new random key using the Key class.
     *
     * @return void
     */
    public function __construct()
    {
        if (!self::$key || self::$key == null || empty(self::$key)) {
            self::$key = Key::createNewRandomKey();
        }
    }

    /**
     * Encrypts a message using the encryption key.
     *
     * @param mixed $message The message to encrypt.
     *
     * @return string The encrypted message.
     */
    public function encrypt($message)
    {
        $queryFactory = new Query(Container::getInstance());
        $logger = new Logger($queryFactory);

        try {
            $json_message = json_encode($message);
            $ciphertext = Crypto::encrypt($json_message, self::$key);
            return $ciphertext;
        } catch (EnvironmentIsBrokenException $ex) {
            // Handle cryptographic errors
            $logger->log("CRITICAL", "Encryptor Error", [
                "Message" => "Encryption Failed",
                "Error" => $ex->getMessage(),
                "Code" => $ex->getCode(),
            ]);
        }
    }
    /**
     * Decrypts a ciphertext using the encryption key.
     *
     * @param string $ciphertext The ciphertext to decrypt.
     *
     * @return mixed The decrypted message.
     */
    public function decrypt($ciphertext)
    {
        $queryFactory = new Query(Container::getInstance());
        $logger = new Logger($queryFactory);

        try {
            $json_message = Crypto::decrypt($ciphertext, self::$key);
            $message = json_decode($json_message, true);
            return $message;
        } catch (EnvironmentIsBrokenException $ex) {
            // Handle cryptographic errors
            $logger->log("CRITICAL", "Encryptor Error", [
                "Message" => "Decryption Failed",
                "Error" => $ex->getMessage(),
                "Code" => $ex->getCode(),
            ]);
        }
    }
}
