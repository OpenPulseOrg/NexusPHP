<?php

namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class TimeInput extends FormElement
{
    public function render()
    {
        $attributes = $this->getAttributesString();
        return '<input type="time" name="' . $this->name . '"' . $attributes . '>';
    }
}
