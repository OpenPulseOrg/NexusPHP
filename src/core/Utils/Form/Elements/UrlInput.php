<?php

namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class UrlInput extends FormElement
{
    public function render()
    {
        $attributes = $this->getAttributesString();

        return '<input type="url" name="' . $this->name . '"' . $attributes . '>';
    }
}
