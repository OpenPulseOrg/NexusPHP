<?php

namespace Nxp\Core\Templating;

use Nxp\Core\Config\ConfigHandler;

/**
 * Class TemplateEngine
 *
 * @package Nxp\Core\Templating
 */
class TemplateEngine
{
    /**
     * @var string The directory path where templates are stored.
     */
    protected $templateDir;

    /**
     * TemplateEngine constructor.
     *
     * @param string $templateDir The directory path where templates are stored.
     */
    public function __construct($templateDir)
    {
        $this->templateDir = $templateDir;
    }

    /**
     * Renders a template with the given variables.
     *
     * @param string $templateName The name of the template file to render.
     * @param array $vars An associative array of variables to be passed to the template.
     *
     * @return string The rendered template content.
     * @throws Exception If the template file is not found.
     */
    public function render($templateName, $vars = [])
    {
        $predefinedVars = [
            "title" => ConfigHandler::get("app", "CORE_TITLE")
        ];

        $allVars = array_merge($predefinedVars, $vars);

        $templatePath = $this->getTemplatePath($templateName);

        if (!file_exists($templatePath)) {
            throw new \Exception('Template not found: ' . $templateName);
        }

        // Extract the variables to a local namespace
        extract($allVars);

        // Start output buffering
        ob_start();

        // Include the template file
        include $templatePath;

        // Get the contents of the buffer
        $content = ob_get_clean();

        // Replace conditional and loop blocks with their respective content
        $content = $this->processBlocks($content, $vars);

        // Replace placeholders with their corresponding values
        foreach ($vars as $key => $value) {
            $content = str_replace('{{ ' . $key . ' }}', $value, $content);
        }

        return $content;
    }

    /**
     * Processes conditional, include, escape, and loop blocks within the template content.
     *
     * @param string $content The template content.
     * @param array $vars An associative array of variables to be used within the template.
     *
     * @return string The processed template content.
     */
    protected function processBlocks($content, $vars)
    {
        // Replace if blocks
        $content = preg_replace_callback('/{{#if (.*?)}}(.*?){{\/if}}/s', function ($matches) use ($vars) {
            return !empty($vars[$matches[1]]) ? $matches[2] : '';
        }, $content);

        // Replace include statements
        $content = preg_replace_callback('/{{include \'(.*?)\'}}/', function ($matches) use ($vars) {
            $includeFileName = $matches[1];
            return $this->render($includeFileName, $vars);
        }, $content);

        // Escape variables
        $content = preg_replace_callback('/{{escape (.*?)}}/', function ($matches) use ($vars) {
            return htmlspecialchars($vars[$matches[1]]);
        }, $content);

        // Replace foreach blocks
        $content = preg_replace_callback('/{{#foreach (.*?) as (.*?)}}(.*?){{\/foreach}}/s', function ($matches) use ($vars) {
            $output = '';
            if (isset($vars[$matches[1]]) && is_array($vars[$matches[1]])) {
                foreach ($vars[$matches[1]] as $item) {
                    $output .= str_replace('{{ ' . $matches[2] . ' }}', $item, $matches[3]);
                }
            }
            return $output;
        }, $content);

        return $content;
    }

    /**
     * Gets the full file path of the given template name.
     *
     * @param string $templateName The name of the template file.
     *
     * @return string The full file path of the template.
     */
    protected function getTemplatePath($templateName)
    {
        return $this->templateDir . '/' . $templateName . '.php';
    }
}
