<?php

namespace Nxp\Core\Security\Cryptography\Hash;

class Hasher
{
    private $encryptionKey;
    private $encryptionMethod = 'AES-256-GCM';
    private $kdfSalt;
    private $passphrase;

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

        // Extract the salt, IV, encrypted data, and authentication tag from the binary data.
        $kdfSalt = substr($data, 0, $saltSize);

        $iv = substr($data, $saltSize, $ivSize);

        $encryptedDataWithTag = substr($data, $saltSize + $ivSize);

        $tagSize = 16; // GCM tag size is 16 bytes.

        $encryptedData = substr($encryptedDataWithTag, 0, -$tagSize);

        $tag = substr($encryptedDataWithTag, -$tagSize);

        // Re-derive the encryption key using the extracted salt.
        $encryptionKey = hash('sha256', $this->passphrase . $kdfSalt, true);

        // Decrypt the data using the derived key and provided IV and authentication tag.
        $decryptedData = openssl_decrypt($encryptedData, $this->encryptionMethod, $encryptionKey, OPENSSL_RAW_DATA, $iv, $tag);

        return $decryptedData;
    }
}
