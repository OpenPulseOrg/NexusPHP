<?php

namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class SubmitButton extends FormElement
{
    protected $label;

    public function __construct($name, $label)
    {
        parent::__construct($name);
        $this->label = $label;
    }

    public function render()
    {
        $attributes = $this->getAttributesString();
        return '<button type="submit" name="' . $this->name . '">' . $this->label . '</button>';
    }
}
