<?php
namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class PhoneNumberInput extends FormElement {
    public function render() {
        $attributes = $this->getAttributesString();

        return '<input type="tel" name="' . $this->name . '"' . $attributes . '>';
    }
}