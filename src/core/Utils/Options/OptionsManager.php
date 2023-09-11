<?php

namespace Nxp\Core\Utils\Options;

class OptionsManager
{
    private $options = [];

    public function setOption(string $key, $value)
    {
        $this->options[$key] = $value;
    }

    public function getOption(string $key, $defaultValue = null)
    {
        return $this->options[$key] ?? $defaultValue;
    }
}
