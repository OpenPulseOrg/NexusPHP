<?php

namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class Checkbox extends FormElement
{
    public function render()
    {
        $attributes = $this->getAttributesString();
        return '<input type="checkbox" name="' . $this->name . '"' . $attributes . '>';
    }
}
