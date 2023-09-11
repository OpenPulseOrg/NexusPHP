<?php

namespace Nxp\Core\Templating\Handler;

use Nxp\Core\Templating\Parser\Parser;
use Nxp\Core\Utils\Service\Locator\Locator;
class Includer
{
    private $variables;
    private $parser;

    public function __construct(&$variables, Parser $parser) // Accept parser as a constructor argument
    {
        $this->variables = &$variables;
        $this->parser = $parser; // Use the passed parser
    }

    public function handle($content)
    {
        return preg_replace_callback('/{%\s*include\s+\'(.+?)\'\s*%}/', function ($matches) {
            $locator = Locator::getInstance();
            $includePath = $locator->getPath("core", "views") . "/$matches[1]";
            if (file_exists($includePath)) {
                $includedContent = file_get_contents($includePath);
                return $this->parser->parse($includedContent); // Use the existing parser to parse the included template
            }
            return ''; // Return empty string if file doesn't exist
        }, $content);
    }
}
