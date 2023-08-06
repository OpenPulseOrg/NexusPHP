<?php
namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class NumberInput extends FormElement {
    public function render() {
        $attributes = $this->getAttributesString();

        return '<input type="number" name="' . $this->name . '"' . $attributes . '>';
    }
}