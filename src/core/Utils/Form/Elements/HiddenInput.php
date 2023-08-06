<?php
namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class HiddenInput extends FormElement {
    protected $value;

    public function __construct($name, $value) {
        parent::__construct($name);
        $this->value = $value;
    }

    public function render() {
        $attributes = $this->getAttributesString();

        return '<input type="hidden" name="' . $this->name . '"' . $attributes . '>';
    }
}