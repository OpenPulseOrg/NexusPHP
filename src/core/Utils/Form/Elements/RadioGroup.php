<?php

namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class RadioGroup extends FormElement
{
    protected $options;

    public function __construct($name, array $options)
    {
        parent::__construct($name);
        $this->options = $options;
    }

    public function render()
    {
        $attributes = $this->getAttributesString();

        $html = '';
        foreach ($this->options as $value => $label) {
            $html .= '<label><input type="radio" name="' . $this->name . '" value="' . $value . '">' . $label . '</label><br>';
        }
        return $html;
    }
}
