<?php

namespace Arkit\Core\Filter\Input\Validator;

use \Arkit\Core\Filter\Input\FieldValidator;

/**
 * Class StrNumberValidator
 */
class StrNumberValidator extends FieldValidator
{

    /**
     * @return $this
     */
    public function check() : self
    {
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue() : mixed
    {
        return $this->realValue;
    }


    /**
     * @return $this
     */
    public function isRoman() : self
    {
        if(!$this->validField)
            return $this;
            
    	if(!preg_match('/^M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/', $this->value))
    		return $this->registerError('invalid_roman_number');

    	return $this;
    }

    /**
     * @return $this
     */
    public function isHexadecimal() : self
    {
        if(!$this->validField)
            return $this;
            
    	if(false === filter_var($this->value, FILTER_VALIDATE_INT, FILTER_FLAG_ALLOW_HEX))
    		return $this->registerError('invalid_hexadecimal_number');
    	
    	return $this;
    }

    /**
     * @return $this
     */
    public function isOctal() : self
    {
        if(!$this->validField)
            return $this;
            
    	if(false === filter_var($this->value, FILTER_VALIDATE_INT, FILTER_FLAG_ALLOW_OCTAL))
    		return $this->registerError('invalid_octal_number');
    	
    	return $this;
    }

    /**
     * @return $this
     */
    public function isBinary() : self
    {
        if(!$this->validField)
            return $this;
            
    	if(!preg_match('/^[0-1]$/', $this->value))
    		return $this->registerError('invalid_binary_number');
    	
    	return $this;
    }

    /**
     * @return $this
     */
    public function isRgbColor() : self
    {
        if(!$this->validField)
            return $this;
            
        if(!preg_match('/^#?[0-9A-F]{6,6}$/', strtoupper($this->value)))
        	return $this->registerError('invalid_rgb_color');
        
        return $this;
    }
}