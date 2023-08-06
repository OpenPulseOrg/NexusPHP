<?php

namespace Nxp\Core\Common\Abstracts\Form;

use Nxp\Core\Utils\From\Validation\Validator;

abstract class FormElement
{
    protected $name;
    protected $attributes = [];
    protected $label;
    protected $validationRules = [];
    protected $errors = [];

    public function __construct($name = null)
    {
        $this->name = $name;
    }

    public function setAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
        return $this;  // Allowing method chaining
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;  // Allowing method chaining
    }

    public function addValidationRule($rule, $param = null)
    {
        $this->validationRules[$rule] = $param;
        return $this;  // Allowing method chaining
    }

    public function getValidationRules()
    {
        return $this->validationRules;
    }

    public function validate($value)
    {
        foreach ($this->validationRules as $rule => $param) {
            $method = [Validator::class, $rule];
            $isValid = isset($param) ? call_user_func($method, $value, $param) : call_user_func($method, $value);
            if (!$isValid) {
                $this->errors[] = $this->getErrorMessage($rule, $param);
            }
        }

        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function getErrorMessage($rule, $param)
    {
        $messages = [
            'required' => 'This field is required.',
            'minLength' => "This field must be at least {$param} characters long.",
            'maxLength' => "This field must not exceed {$param} characters.",
            'email' => 'Please enter a valid email address.',
            // ... add more messages as needed
        ];

        return $messages[$rule] ?? 'Invalid input.';
    }

    protected function getAttributesString()
    {
        $attributesString = '';
        foreach ($this->attributes as $attribute => $value) {
    
            if ($value === true) {
                // For boolean attributes, only output the attribute name without a value
                $attributesString .= ' ' . $attribute;
            } elseif (!empty($value)) {
                // For attributes with non-empty values, output the attribute name and value
                $attributesString .= ' ' . $attribute . '="' . $value . '"';
            }
        }
        return $attributesString;
    }

    abstract public function render();
}
