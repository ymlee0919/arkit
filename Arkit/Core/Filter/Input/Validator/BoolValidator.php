<?php
namespace Arkit\Core\Filter\Input\Validator;

use \Arkit\Core\Filter\Input\FieldValidator;

/**
 * Class BoolValidator
 */
class BoolValidator extends FieldValidator {

    /**
     * @return $this
     */
    public function check() : self
    {
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;
        
        if($this->checkValidEmpty())
            return $this;

        $this->realValue = filter_var($this->value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        $this->validField = !(is_null($this->realValue));
        
        if(!$this->validField)
			return $this->registerError('invalid_boolean');
		
		return $this;
    }

    /**
     * @return bool|null
     */
    public function getValue() : ?bool
    {
        return $this->realValue;
    }


	public function isTrue() : self
	{
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;
        
        if($this->checkValidEmpty())
            return $this;
		
		$val = (is_bool($this->value)) ? $this->value : filter_var($this->value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
		if(false === $val)
			return $this->registerError('boolean_not_true');
		
		return $this;
	}


	public function isFalse() : self
	{
        if(!$this->validField || !$this->checkValidEmpty() || $this->isEmpty())
            return $this;
        
        if($this->checkValidEmpty())
            return $this;
		
		$val = (is_bool($this->value)) ? $this->value : filter_var($this->value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
		if(true === $val)
			return $this->registerError('boolean_not_false');
		
		return $this;
	}


}