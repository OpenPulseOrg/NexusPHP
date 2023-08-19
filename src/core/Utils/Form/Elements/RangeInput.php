<?php
namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class RangeInput extends FormElement {
    public function render() {
        $attributes = $this->getAttributesString();

        return '<input type="range" name="' . $this->name . '"' . $attributes . '>';
    }
}