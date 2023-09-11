<?php

namespace Nxp\Core\Security\Server;

/**
 * The Info class is used to manage server-related information and headers for improved security and privacy.
 */
class Info
{
    /**
     * Clean up server headers to enhance security and privacy.
     *
     * This method removes default headers that may reveal server details and adds custom security and privacy headers.
     * Custom headers added here can help prevent certain attacks and enforce stricter security policies.
     *
     * @return void
     */
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
