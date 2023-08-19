<?php


namespace Nxp\Core\Templating\Parser;

use Nxp\Core\Templating\Handler\Filter;
use Nxp\Core\Templating\Handler\Includer;
use Nxp\Core\Utils\Localization\Translator;

class Parser
{
    private $variables;
    private $includeHandler;
    private $filterHandler;

    public function __construct($variables)
    {
        $this->variables = $variables;
        $this->includeHandler = new Includer($this->variables, $this);
        $this->filterHandler = new Filter();
    }

    public function registerFilter($name, $callable)
    {
        $this->filterHandler->register($name, $callable);
    }

    private function parseVariables($matches)
    {
        return $this->variables[$matches[1]] ?? $matches[0];
    }

    public function setVariables($variables)
    {
        $this->variables = $variables;
    }

    public function getFilterHandler()
    {
        return $this->filterHandler;
    }

    private function evaluateCondition($condition)
    {
        // Handle comparisons
        if (preg_match('/(.+?)(==|!=|<|<=|>|>=)(.+)/', $condition, $matches)) {
            $left = trim($matches[1]);
            $operator = $matches[2];
            $right = trim($matches[3]);

            // Get the values of the variables
            $leftValue = $this->variables[$left] ?? null;
            $rightValue = $this->variables[$right] ?? null;

            // Perform the comparison
            switch ($operator) {
                case '==':
                    return $leftValue == $rightValue;
                case '!=':
                    return $leftValue != $rightValue;
                case '<':
                    return $leftValue < $rightValue;
                case '<=':
                    return $leftValue <= $rightValue;
                case '>':
                    return $leftValue > $rightValue;
                case '>=':
                    return $leftValue >= $rightValue;
            }
        }

        // Handle logical operators
        if (preg_match('/(.+?)(&&|\|\|)(.+)/', $condition, $matches)) {
            $left = trim($matches[1]);
            $operator = $matches[2];
            $right = trim($matches[3]);

            // Evaluate the subconditions
            $leftValue = $this->evaluateCondition($left);
            $rightValue = $this->evaluateCondition($right);

            // Perform the logical operation
            switch ($operator) {
                case '&&':
                    return $leftValue && $rightValue;
                case '||':
                    return $leftValue || $rightValue;
            }
        }

        // Simple case: check if the condition is a variable and get its value
        return $this->variables[$condition] ?? false;
    }

    private function parseMethods($matches)
    {
        $expression = trim($matches[1]);
        $parts = explode('.', $expression);
        $objectName = $parts[0];
        $methodExpression = $parts[1] ?? '';
        $methodParts = explode('(', $methodExpression);
        $methodName = $methodParts[0];
        $args = isset($methodParts[1]) ? rtrim($methodParts[1], ')') : '';
        $args = $args ? explode(',', $args) : [];

        $object = $this->variables[$objectName] ?? null;
        if ($object && method_exists($object, $methodName)) {
            return (string) call_user_func_array([$object, $methodName], $args);
        }

        return '';  // Return an empty string if the method doesn't exist
    }

    public function parse($content)
    {
        // Parse includes
        $content = $this->includeHandler->handle($content);

        $content = $this->parseIfStatements($content);

        // Apply filters to variables
        $content = preg_replace_callback('/\\{\\{\\s*(.+?)\\s*\\}\\}/', function ($matches) {
            return $this->parseFilters($matches[1]);
        }, $content);

        // Parse Translations
        $content = $this->parseTranslations($content);  

        // Parse method calls
        $content = preg_replace_callback('/{%\s*(.+?)\s*%}/', [$this, 'parseMethods'], $content);

        // Parse variables
        $content = preg_replace_callback('/{{\s*(.+?)\s*}}/', [$this, 'parseVariables'], $content);

        return $content;
    }

    private function parseFilters($expression)
    {
        $parts = explode('|', $expression);
        $variableName = trim($parts[0]);
        $value = $this->variables[$variableName] ?? '';

        // Iterate through the filters and apply them
        for ($i = 1; $i < count($parts); $i++) {
            $filterExpression = trim($parts[$i]);
            $filterParts = explode(':', $filterExpression);
            $filterName = trim($filterParts[0]);
            $args = isset($filterParts[1]) ? array_map('trim', explode(',', $filterParts[1])) : [];
            $value = $this->filterHandler->apply($filterName, $value, $args);
        }

        return $value;
    }

    private function parseIfStatements($content)
    {
        $pattern = '/{%\s*if\s+(.+?)\s*%}(.*?)(?:{%\s*elseif\s+(.+?)\s*%}(.*?))*(?:{%\s*else\s*%}(.*?))?{%\s*endif\s*%}/s';

        return preg_replace_callback($pattern, function ($matches) {
            // The first condition and its content are always present
            $conditions[] = $matches[1];
            $contents[] = $matches[2];

            // If there are elseif statements, add their conditions and contents
            if (isset($matches[3])) {
                $conditions = array_merge($conditions, explode('|', $matches[3]));
                $contents = array_merge($contents, explode('|', $matches[4]));
            }

            // The else content, if present
            $elseContent = $matches[5] ?? '';

            // Iterate over the conditions
            foreach ($conditions as $i => $condition) {
                $result = $this->evaluateCondition($condition);
                if ($result) {
                    // If the condition is true, return its corresponding content
                    return $this->parse($contents[$i]);
                }
            }

            // If none of the conditions were true, return the else content
            return $this->parse($elseContent);
        }, $content);
    }

    private function parseTranslations($content)
    {
        $pattern = '/{%\\s*t\\s+"(.*?)"\\s*%}/'; // Matches {% t "translation_key" %}

        return preg_replace_callback($pattern, function ($matches) {
            return Translator::get($matches[1]);
        }, $content);
    }
}
