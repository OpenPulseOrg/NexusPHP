<?php
namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class Radio extends FormElement {
    public function render() {
        $attributes = $this->getAttributesString();

        return '<input type="radio" name="' . $this->name . '"' . $attributes . '>';
    }
}