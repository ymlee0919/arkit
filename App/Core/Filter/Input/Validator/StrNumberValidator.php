<?php

namespace Arkit\Core\Filter\Input\Validator;

use \Arkit\Core\Filter\Input\FieldValidator;

/**
 * Class StrNumberValidator
 */
class StrNumberValidator extends FieldValidator
{

    public function set(mixed $value) : void
    {
        if(!is_string($value))
        {
            $this->value = null;
            $this->realValue = null;
            $this->validField = false;
        }
        else
            parent::set(trim($value));
    }

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
        if(!$this->validField || !$this->checkValidEmpty())
            return $this;
            
    	if(!preg_match('/^M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/', $this->value))
    		return $this->registerError('invalid_roman_number');
        else
            $this->realValue = $this->value;

    	return $this;
    }

    /**
     * @return $this
     */
    public function isHexadecimal() : self
    {
        if(!$this->validField || !$this->checkValidEmpty())
            return $this;
        
        // Set 0x as prefix if have not
        $hexVal = $this->value;
        if(strpos($this->value, '0x') !== 0)
            $hexVal = '0x' . $this->value;

    	if(false === filter_var($hexVal, FILTER_VALIDATE_INT, FILTER_FLAG_ALLOW_HEX))
    		return $this->registerError('invalid_hexadecimal_number');
        else
            $this->realValue = $hexVal;
    	
    	return $this;
    }

    /**
     * @return $this
     */
    public function isOctal() : self
    {
        if(!$this->validField || !$this->checkValidEmpty())
            return $this;
        // Set 0x as prefix if have not
        $octVal = $this->value;
        if(strpos($this->value, '0x') !== 0)
            $octVal = '0x' . $this->value;
        
    	if(!preg_match('/^(0x)?[0-8]+$/', $octVal))
    		return $this->registerError('invalid_octal_number');
        else
            $this->realValue = $octVal;
    	
    	return $this;
    }

    /**
     * @return $this
     */
    public function isBinary() : self
    {
        if(!$this->validField || !$this->checkValidEmpty())
            return $this;
            
    	if(!preg_match('/^(0x)?[0-1]+$/', $this->value))
    		return $this->registerError('invalid_binary_number');
        else
            $this->realValue = $this->value;
    	
    	return $this;
    }

    /**
     * @return $this
     */
    public function isRgbColor() : self
    {
        if(!$this->validField || !$this->checkValidEmpty())
            return $this;
            
        if(!preg_match('/^#?[0-9A-F]{6}$/', strtoupper(trim($this->value))))
        	return $this->registerError('invalid_rgb_color');
        else
            $this->realValue = strtoupper(trim($this->value));
        
        return $this;
    }
}