<?php

namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class Password extends FormElement
{
    public function render()
    {
        $attributes = $this->getAttributesString();

        return '<input type="password" name="' . $this->name . '"' . $attributes . '>';
    }
}
