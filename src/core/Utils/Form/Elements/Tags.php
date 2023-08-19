<?php

namespace Nxp\Core\Utils\Form\Elements;

use Nxp\Core\Common\Abstracts\Form\FormElement;

class Tags extends FormElement
{
    protected $formAttributes = [];

    public function startForm($method = 'post', $action = '')
    {
        $this->formAttributes['method'] = $method;
        $this->formAttributes['action'] = $action;
        return '<form ' . $this->getAttributesString() . '>';
    }

    public function endForm()
    {
        return '</form>';
    }

    public function render()
    {
    }
}
