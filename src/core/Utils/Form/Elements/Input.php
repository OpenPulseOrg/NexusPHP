<?php

namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class Input extends FormElement
{
    public function render()
    {
        $attributes = $this->getAttributesString();
        return '<input type="text" name="' . $this->name . '"' . $attributes . '>';
    }
}
