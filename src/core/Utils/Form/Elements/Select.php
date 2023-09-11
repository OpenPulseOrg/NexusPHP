<?php

namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class Select extends FormElement
{
    protected $options;

    public function __construct($name, array $options)
    {
        parent::__construct($name);
        $this->options = $options;
    }

    public function render()
    {
        $attributes = $this->getAttributesString();

        $html = '<select name="' . $this->name . '"' . $attributes . '>';
        foreach ($this->options as $value => $label) {
            $html .= '<option value="' . $value . '">' . $label . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
}
