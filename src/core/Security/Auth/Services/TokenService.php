<?php

namespace Nxp\Core\Security\Auth\Services;

use Nxp\Core\Config\ConfigurationManager;

class TokenService
{
    private $secretKey;
    private $expirationTime;
    private $claims = [];
    private static $blacklist = [];

    public function __construct()
    {
        $this->secretKey = ConfigurationManager::get("keys", "SIGNING_KEY");
    }
    public function generateToken($userData)
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        if ($this->expirationTime) {
            $userData['exp'] = time() + $this->expirationTime;
        }

        // Merge custom claims
        $userData = array_merge($this->claims, $userData);

        $payload = json_encode($userData);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return $jwt;
    }

    public function validateToken($token)
    {
        $tokenParts = explode('.', $token);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return ($base64UrlSignature === $signatureProvided);
    }

    public function parseToken($token)
    {
        $tokenParts = explode('.', $token);
        $payload = base64_decode($tokenParts[1]);
        return json_decode($payload, true);
    }

    public function setExpirationTime($seconds)
    {
        $this->expirationTime = $seconds;
    }

    public function hasExpired($token)
    {
        $payload = $this->parseToken($token);
        if (isset($payload['exp'])) {
            return time() > $payload['exp'];
        }
        return false;
    }

    public function blacklistToken($token)
    {
        self::$blacklist[] = $token;
    }

    public function isBlacklisted($token)
    {
        return in_array($token, self::$blacklist);
    }

    public static function getBlacklistedTokens()
    {
        return self::$blacklist;
    }


    public function generateRefreshToken($userData)
    {
        $this->setExpirationTime(7 * 24 * 60 * 60);  // 7 days, for example
        return $this->generateToken($userData);
    }

    public function refreshToken($refreshToken)
    {
        if ($this->validateToken($refreshToken) && !$this->hasExpired($refreshToken) && !$this->isBlacklisted($refreshToken)) {
            $userData = $this->parseToken($refreshToken);
            return $this->generateToken($userData);
        }
        return false;
    }

    public function addClaim($name, $value)
    {
        $this->claims[$name] = $value;
    }

    public function getClaim($token, $name)
    {
        $payload = $this->parseToken($token);
        return $payload[$name] ?? null;
    }
}
