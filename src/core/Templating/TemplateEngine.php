<?php

namespace Nxp\Core\Templating;

use Nxp\Core\Templating\Parser\Parser;

class TemplateEngine
{
    private $templatePath;
    private $variables;
    private $parser;

    public function __construct($templatePath)
    {
        $this->templatePath = $templatePath;
        $this->variables = [];
        $this->parser = new Parser($this->variables); // Initialize the parser
    }

    public function set($key, $value)
    {
        $this->variables[$key] = $value;
    }

    public function render()
    {
        // Update the parser's variables before parsing
        $this->parser->setVariables($this->variables);

        $templateContent = file_get_contents($this->templatePath);
        return $this->parser->parse($templateContent); // Delegate parsing to the parser class
    }

    public function getParser()
    {
        return $this->parser;
    }
}
