<?php

namespace Nxp\Core\Security\Cryptography\Hash;

/**
 * The Hasher class provides functionality for hashing and unhashing data using AES-256-GCM encryption.
 * The class uses a passphrase to derive an encryption key and randomly generate a salt for key derivation.
 */
class Hasher
{
    private $encryptionKey;
    private $encryptionMethod = 'AES-256-GCM';
    private $kdfSalt;
    private $passphrase;

    /**
     * Hasher constructor.
     *
     * @param string $passphrase The passphrase used to derive the encryption key.
     */
    public function __construct($passphrase)
    {
        // Store the salt for later use.
        $this->kdfSalt = openssl_random_pseudo_bytes(16);
        $encryptionKey = hash('sha256', $passphrase . $this->kdfSalt, true);
        $this->encryptionKey = $encryptionKey;
        $this->passphrase = $passphrase;
    }

    public function hash($data)
    {
        $ivSize = openssl_cipher_iv_length($this->encryptionMethod);
        $iv = openssl_random_pseudo_bytes($ivSize);

        $encryptedData = openssl_encrypt($data, $this->encryptionMethod, $this->encryptionKey, OPENSSL_RAW_DATA, $iv, $tag);

        // Concatenate salt, IV, encrypted data, and authentication tag.
        $hash = base64_encode($this->kdfSalt . $iv . $encryptedData . $tag);

        return $hash;
    }

    public function unhash($hash)
    {
        $data = base64_decode($hash);

        $saltSize = 16; // Salt size in bytes.
        $ivSize = openssl_cipher_iv_length($this->encryptionMethod);
        $tagSize = 16; // GCM tag size is 16 bytes.

        // Ensure that the provided data length matches the expected length
        if (strlen($data) < $saltSize + $ivSize + $tagSize) {
            return false;
        }

        $kdfSalt = substr($data, 0, $saltSize);
        $iv = substr($data, $saltSize, $ivSize);
        $encryptedData = substr($data, $saltSize + $ivSize, strlen($data) - $saltSize - $ivSize - $tagSize);
        $tag = substr($data, -$tagSize);

        $encryptionKey = hash('sha256', $this->passphrase . $kdfSalt, true);

        $decryptedData = openssl_decrypt($encryptedData, $this->encryptionMethod, $encryptionKey, OPENSSL_RAW_DATA, $iv, $tag);

        if ($decryptedData === false) {
            // Decryption failed, possibly due to wrong key or tampered data.
            return false;
        }

        return $decryptedData;
    }
}
