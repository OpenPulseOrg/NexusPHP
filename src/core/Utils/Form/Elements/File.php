<?php

namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class File extends FormElement
{
    public function render()
    {
        $attributes = $this->getAttributesString();

        return '<input type="file" name="' . $this->name . '"' . $attributes . '>';
    }
}
