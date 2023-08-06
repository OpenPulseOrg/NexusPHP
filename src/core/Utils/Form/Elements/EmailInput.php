<?php
namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class EmailInput extends FormElement {
    public function render() {
        $attributes = $this->getAttributesString();

        return '<input type="email" name="' . $this->name . '"' . $attributes . '>';
    }
}