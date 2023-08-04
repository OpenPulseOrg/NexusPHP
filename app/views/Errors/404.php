<?php

use Nxp\Core\Config\ConfigHandler;
use Nxp\Core\Utils\Assets\AssetLoader;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo ConfigHandler::get("app", "CORE_TITLE"); ?> - 404 Not Found</title>
    <?php
    AssetLoader::loadCSS("style.css");
    AssetLoader::generateFavicon("favicon.png");
    ?>
</head>

<body>
    <h1 id="fancyText">404 Page Not Found</h1>

    <div class="container">
        <p>Oops! The page you are looking for could not be found.</p>
        <p>Check out the <a href="https://github.com/kevingorman1000/NexusPHP">GitHub repository</a> for more information.</p>
        <p>Documentation: <a href="#">Coming Soon</a></p>
    </div>

    <div class="container">
        <a class="button" href="#">Get Started</a>
    </div>

    <div class="container">
        <a class="support-link" href="https://github.com/kevingorman1000/NexusPHP">Need support? Visit our GitHub page.</a>
    </div>

    <div class="container">
        <p>License: <a href="https://www.apache.org/licenses/LICENSE-2.0.txt">Apache License 2.0</a></p>
    </div>

    <?php AssetLoader::loadJS("app.js"); ?>
</body>

</html>