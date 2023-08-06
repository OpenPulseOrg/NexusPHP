<?php

namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class CheckboxGroup extends FormElement
{
    protected $options;

    public function __construct($name, array $options)
    {
        parent::__construct($name);
        $this->options = $options;
    }

    public function render()
    {
        $html = '';
        foreach ($this->options as $value => $label) {
            $attributes = $this->getAttributesString();
            $html .= '<label><input type="checkbox" name="' . $this->name . '[]" value="' . $value . '"' . $attributes . '>' . $label . '</label><br>';
        }
        return $html;
    }
}
