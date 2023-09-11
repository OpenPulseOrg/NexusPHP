<?php
namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class DateInput extends FormElement {
    public function render() {
        $attributes = $this->getAttributesString();
        return '<input type="date" name="' . $this->name . '"' . $attributes . '>';
    }
}