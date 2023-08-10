<?php

use Nxp\Core\Config\ConfigurationManager;

// Root Constant
define("ROOT", __DIR__ . "/../../");

// Base Constants
define("BASE_ROOT_DIR", ROOT);

// Routes
define("ROUTE_PATH", ROOT . "/app/routes/");

// Config Constants
define("CONFIG_ROOT_PATH", ROOT . "app/config/");

// Plugin Constants
define("PLUGIN_ROOT_PATH", ROOT . "src/plugins");

// Views Constants
define("VIEWS_ROOT_PATH", ROOT . "app/views/");

// Backup Constants
define("DEFAULT_BACKUP_LOCATION_ROOT", ROOT . "app/" . ConfigurationManager::get("app", "BACKUP_ZIP_LOCATION"));

