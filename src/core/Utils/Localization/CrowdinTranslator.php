<?php

namespace Nxp\Core\Utils\Localization; // Adjust the namespace according to your project structure

use Nxp\Core\Security\Storage\Caching\Cache;
use Nxp\Core\Utils\API\APIRequestHandler;
use Nxp\Core\Utils\HTTP\Request;
use Nxp\Core\Utils\HTTP\Response;

/**
 * CrowdinTranslator class for handling translations using the Crowdin API.
 *
 * @package Nxp\Core\Utils\Localization
 */
class CrowdinTranslator
{
    private $apiKey;
    private $projectId;
    private $cacheHandler;
    private $baseURL = 'https://api.crowdin.com/api/project/';

    /**
     * CrowdinTranslator constructor.
     *
     * @param string $apiKey     The API key used for Crowdin API authentication.
     * @param string $projectId  The Crowdin project ID.
     */
    public function __construct($apiKey, $projectId)
    {
        $this->apiKey = $apiKey;
        $this->projectId = $projectId;
        $this->cacheHandler = new Cache();
    }

    /**
     * Get translations for the specified language from Crowdin.
     *
     * @param string $languageCode The language code for the translations (e.g., 'fr' for French).
     *
     * @return array Associative array of translations.
     */
    public function getTranslations($languageCode)
    {
        $cacheKey = 'crowdin_translations_' . $languageCode;
        $translations = $this->cacheHandler->get($cacheKey);

        if (!$translations) {
            $url = $this->baseURL . $this->projectId . '/export'
                . '?key=' . $this->apiKey
                . '&language=' . urlencode($languageCode)
                . '&format=json';

            // Create an instance of the APIRequestHandler class
            $apiRequestHandler = new APIRequestHandler($this->baseURL, $this->apiKey);

            // Prepare a GET request using APIRequestHandler
            $request = new Request(); // Create a new request instance
            $response = $apiRequestHandler->sendRequest($request, '/export?key=' . urlencode($this->apiKey) . '&language=' . urlencode($languageCode) . '&format=json');

            if ($response->getStatusCode() === 200) {
                // Decode the JSON response body
                $responseBody = $response->getBody();
                $data = json_decode($responseBody, true);

                if (isset($data['data']['translations'])) {
                    $translations = $data['data']['translations'];
                    $this->cacheHandler->set($cacheKey, $translations);
                }
            }
        }

        return $translations ?? [];
    }
}
