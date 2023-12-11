<?php

namespace Arkit\Core\Filter\Input;

use \Arkit\Core\Filter\InputValidator;

/**
 * Class FieldValidator
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
     * @param mixed $value
     */
    public function set(mixed $value) : void
    {
        $this->value = $value;
        $this->realValue = null;
        $this->validField = !is_null($value);
    }

    /**
     * @return self
     */
    public abstract function check() : self;

    /**
     * @return mixed
     */
    public abstract function getValue() : mixed;

    /**
     * @param string $error
     * @param array|null $params
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

    public function checkValidEmpty() : bool
    {
        if($this->isEmpty())
            return $this->allowEmpty;

        return true;
    }


    public function isEmpty() : bool
    {
        return (is_null($this->value) || strlen(strval($this->value)) == 0);
    }

    /**
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
     * @return bool
     */
    public function isValid() : bool
    {
        return !is_null($this->realValue);
    }

}