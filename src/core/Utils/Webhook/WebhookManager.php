<?php

namespace Nxp\Core\Utils\Webhook;

class WebhookManager
{
    /**
     * Send a webhook POST request to a specified URL.
     *
     * @param string $url The URL to send the webhook to.
     * @param array $data The data to send with the webhook.
     * @return bool True if the webhook was sent successfully, false otherwise.
     */
    public static function sendWebhook($url, $data)
    {
        $payload = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload),
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false || ($httpCode !== 200 && $httpCode !== 201 && $httpCode !== 204)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception("Failed to send webhook: $error");
        }

        curl_close($ch);

        return true;
    }
}
