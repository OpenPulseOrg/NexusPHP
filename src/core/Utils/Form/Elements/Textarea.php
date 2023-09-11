<?php

namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class Textarea extends FormElement
{
    public function render()
    {
        $attributes = $this->getAttributesString();

        return '<textarea name="' . $this->name . '"></textarea>';
    }
}
