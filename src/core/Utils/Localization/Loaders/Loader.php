<?php

namespace Nxp\Core\Utils\Localization\Loaders;

use Exception;
use Nxp\Core\Config\ConfigurationManager;
use Nxp\Core\Utils\Localization\Managers\Crowdin\CrowdinManager;
use Nxp\Core\Utils\Localization\Managers\Crowdin\Manager;
use Nxp\Core\Utils\Service\Locator\Locator;
class Loader
{
    public function loadFromFile($language)
    {
        if(empty($language)){
            throw new Exception("Language is empty within the app config file");
        }
        $locator = Locator::getInstance();

        $filePath = $locator->getPath("langauge", "root", ["language" => $language]) . "/{$language}.json";

        if (file_exists($filePath)) {
            return json_decode(file_get_contents($filePath), true);
        } else {
            throw new Exception("Language file not found for: {$language}");
        }
    }

    public function loadFromDatabase($language)
    {
        // TO-DO
        // Simulating fetching from a database
        // In reality, this method would interact with the database using the database handler.
        // Here's a pseudo-code for demonstration:

        /*
        $db = DatabaseConnection::getInstance();
        $translations = $db->fetch("SELECT translations FROM languages WHERE language_code = ?", [$language]);
        return $translations;
        */

        // For this simulation, we'll return an empty array as the translations.
        return [];
    }

    public function loadFromAPI($language)
    {
        // TO-DO
        // Simulating fetching from an API
        // In reality, you'd make an HTTP request to the external API to get the translations.

        /*
        $client = new HttpClient();
        $response = $client->get("https://api.example.com/translations/{$language}");
        return json_decode($response->getBody(), true);
        */

        // For this simulation, we'll return an empty array as the translations.
        return [];
    }

    public function loadFromCrowdin($language)
    {
        $crowdin = new Manager(ConfigurationManager::get("app", "crowdin.project_id"), ConfigurationManager::get("app", "crowdin.api_key"));
        return $crowdin->fetchTranslations($language);
    }
}
