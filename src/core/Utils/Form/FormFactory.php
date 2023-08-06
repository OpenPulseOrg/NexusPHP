<?php

namespace Nxp\Core\Utils\Form;

use Nxp\Core\Utils\From\Elements\File;
use Nxp\Core\Utils\From\Elements\Tags;
use Nxp\Core\Utils\From\Elements\Input;
use Nxp\Core\Utils\From\Elements\Radio;
use Nxp\Core\Utils\From\Elements\Select;
use Nxp\Core\Utils\From\Elements\Checkbox;
use Nxp\Core\Utils\From\Elements\Password;
use Nxp\Core\Utils\From\Elements\Textarea;
use Nxp\Core\Utils\From\Elements\UrlInput;
use Nxp\Core\Utils\From\Elements\DateInput;
use Nxp\Core\Utils\From\Elements\TimeInput;
use Nxp\Core\Utils\From\Elements\EmailInput;
use Nxp\Core\Utils\From\Elements\RadioGroup;
use Nxp\Core\Utils\From\Elements\RangeInput;
use Nxp\Core\Utils\From\Elements\ColorPicker;
use Nxp\Core\Utils\From\Elements\HiddenInput;
use Nxp\Core\Utils\From\Elements\NumberInput;
use Nxp\Core\Utils\From\Elements\ResetButton;
use Nxp\Core\Utils\From\Elements\SubmitButton;
use Nxp\Core\Utils\From\Elements\CheckboxGroup;
use Nxp\Core\Utils\From\Elements\PhoneNumberInput;

class FormFactory
{
    public static function checkbox($name)
    {
        return new Checkbox($name);
    }

    public static function checkboxGroup($name, array $options)
    {
        return new CheckboxGroup($name, $options);
    }

    public static function colorPicker($name)
    {
        return new ColorPicker($name);
    }

    public static function dateInput($name)
    {
        return new DateInput($name);
    }

    public static function emailInput($name)
    {
        return new EmailInput($name);
    }

    public static function file($name)
    {
        return new File($name);
    }

    public static function hiddenInput($name, $value)
    {
        return new HiddenInput($name, $value);
    }

    public static function input($name)
    {
        return new Input($name);
    }

    public static function numberInput($name)
    {
        return new NumberInput($name);
    }

    public static function password($name)
    {
        return new Password($name);
    }

    public static function phoneNumberInput($name)
    {
        return new PhoneNumberInput($name);
    }

    public static function radio($name)
    {
        return new Radio($name);
    }

    public static function radioGroup($name, array $options)
    {
        return new RadioGroup($name, $options);
    }

    public static function rangeInput($name)
    {
        return new RangeInput($name);
    }

    public static function resetButton($name, $label)
    {
        return new ResetButton($name, $label);
    }

    public static function select($name, array $options)
    {
        return new Select($name, $options);
    }

    public static function submitButton($name, $label)
    {
        return new SubmitButton($name, $label);
    }

    public static function textarea($name)
    {
        return new Textarea($name);
    }

    public static function timeInput($name)
    {
        return new TimeInput($name);
    }

    public static function urlInput($name)
    {
        return new UrlInput($name);
    }

    public static function startForm(){
        return new Tags();
    }

    public static function endForm(){
        return new Tags();
    }
}
