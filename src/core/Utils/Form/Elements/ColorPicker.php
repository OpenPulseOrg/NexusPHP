<?php

namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class ColorPicker extends FormElement
{
    public function render()
    {
        $attributes = $this->getAttributesString();
        return '<input type="color" name="' . $this->name . '"' . $attributes . '>';
    }
}