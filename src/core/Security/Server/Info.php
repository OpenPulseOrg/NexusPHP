<?php

namespace Nxp\Core\Security\Server;

class Info
{
    public function cleanHeaders()
    {
        // Remove default headers that may reveal server details
        header_remove('X-Powered-By');
        header_remove('Server');

        // Add your custom headers for security and privacy
        header("Server: Nexus Security");
        header('X-Powered-By: Nexus Security');
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\'');
        header('Referrer-Policy: no-referrer, strict-origin-when-cross-origin');
        header('Feature-Policy: accelerometer \'none\'; camera \'none\'; geolocation \'none\'; gyroscope \'none\'; microphone \'none\'; payment \'none\'; usb \'none\'');
        header('Permissions-Policy: camera=(), microphone=(), geolocation=(), interest-cohort=()');
    }
}
