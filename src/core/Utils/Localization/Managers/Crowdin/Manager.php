<?php

namespace Nxp\Core\Utils\Localization\Managers\Crowdin;

use Exception;
use Nxp\Core\Config\ConfigurationManager;

class Manager
{
    private $apiKey;
    private $projectId;
    private $baseURL;

    public function __construct($projectId, $apiKey)
    {
        $this->baseURL = ConfigurationManager::get("app", "CROWDIN_BASE_URL");
        $this->apiKey = $apiKey;
        $this->projectId = $projectId;
    }

    public function fetchTranslations($language)
    {
        $url = "{$this->baseURL}/{$this->projectId}/language/status?language={$language}&key={$this->apiKey}";

        // Here, use an HTTP client to fetch the translations. This is a pseudo-code:
        /*
        $client = new HttpClient();
        $response = $client->get($url);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true);
        } else {
            throw new Exception("Failed to fetch translations from Crowdin.");
        }
        */

        // For this simulation, we'll return an empty array.
        return [];
    }
}
