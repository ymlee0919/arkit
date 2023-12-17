<?php

namespace Arkit\Core\Filter\Input;

use \Arkit\Core\Filter\InputValidator;

/**
 * Abstract class for each type of validator
 */
abstract class FieldValidator
{

    /**
     * The value to validate
     * @var mixed
     */
    protected mixed $value;

    /**
     * The real value
     * @var mixed
     */
    protected mixed $realValue;

    /**
     * @var InputValidator
     */
    protected InputValidator $form;

    /**
     * @var bool
     */
    protected bool $validField;

    /**
     * @var bool
     */
    protected bool $allowEmpty;


    /**
     * Create the validator. It requiere a reference to the form input validator.
     * 
     * @param InputValidator $form
     */
    public function __construct(InputValidator &$form)
    {
        $this->value = null;
        $this->form = $form;
        $this->validField = true;
        $this->allowEmpty = true;
    }

    /**
     * Set the value to validate
     * 
     * @param mixed $value Value to validate
     */
    public function set(mixed $value) : void
    {
        $this->value = $value;
        $this->realValue = null;
        $this->validField = !is_null($value);
    }

    /**
     * Perform basic chek
     * @return self
     */
    public abstract function check() : self;

    /**
     * Get the value after validate. Return null if any validation error.
     * @return mixed
     */
    public abstract function getValue() : mixed;

    /**
     * Register an error associated to the current value.
     * 
     * @param string $error Error message format
     * @param array|null $params Perameters to build the message
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function registerError(string $error, ?array $params = null) : self
    {
        $this->form->registerError($error, $params);
        $this->realValue = null;
        $this->validField = false;
        return $this;
    }

    /**
     * Indicate if the value is empty and can be empty
     *
     * @return boolean
     */
    public function checkValidEmpty() : bool
    {
        if($this->isEmpty())
            return $this->allowEmpty;

        return true;
    }


    /**
     * Define when a field is empty
     *
     * @return boolean
     */
    public function isEmpty() : bool
    {
        return (is_null($this->value) || strlen(strval($this->value)) == 0);
    }

    /**
     * Validate the field is not empty.
     * 
     * @return $this
     */
    public function notEmpty() : self
    {
        $this->allowEmpty = false;

        if($this->isEmpty())
            return $this->registerError('not_empty_field');

        return $this;
    }

    /**
     * Indicate if the value is valid, after validate.
     * @return bool
     */
    public function isValid() : bool
    {
        return !is_null($this->realValue);
    }

}