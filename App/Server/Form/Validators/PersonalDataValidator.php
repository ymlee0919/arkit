<?php

class PersonalDataValidator extends FieldValidator {

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
    public function isEmail() : self
    {
        if(!$this->validField)
            return $this;
            
        if(!filter_var($this->value, FILTER_VALIDATE_EMAIL))
            $this->form->registerError('invalid_email');
        else
            $this->realValue = $this->value;

        return $this;
    }

    /**
     * @param string $separator
     * @return $this
     */
    public function isEmailList(string $separator = ';') : self
    {
        if(!$this->validField)
            return $this;

        $values = explode($separator, $this->value);
        foreach($values as $value)
        {
            if(!filter_var(trim($value), FILTER_VALIDATE_EMAIL))
            {
                $this->form->registerError('invalid_email');
                break;
            }
        }

        $this->realValue = $values;

        return $this;
    }

    /**
     * @return $this
     */
    public function isPhone() : self
    {
        if(!$this->validField)
            return $this;
            
        $pattern = '/^[+]?([\d]{0,3})?[\(\.\-\s]?(([\d]{1,3})[\)\.\-\s]*)?(([\d]{3,5})[\.\-\s]?([\d]{4})|([\d]{2}[\.\-\s]?){4})$/';
        if(!preg_match($pattern, $this->value))
            $this->form->registerError('invalid_phone');
        else
            $this->realValue = $this->value;

        return $this;
    }

    /**
     * @return $this
     */
    public function isValidName() : self
    {
        if(!$this->validField)
            return $this;
            
        $pattern = "/^(([A-Z\. ])|([A-ZÑÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝ][a-zA-ZÑÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝñàáâãäåçèéêëìíîïñðòóôõöùúûüýÿ'\-, ]{1,}))+$/";
        if(!preg_match($pattern, $this->value))
            $this->form->registerError('invalid_name');
        else
            $this->realValue = $this->value;

        return $this;
    }

    /**
     * @return $this
     */
    public function isValidUser() : self
    {
        if(!$this->validField)
            return $this;
            
        $pattern = '/^[0-9a-zA-Z-_\.]+$/';
        if(!preg_match($pattern, $this->value))
            $this->form->registerError('invalid_user');
        else
            $this->realValue = $this->value;

        return $this;
    }

    /**
     * @return $this
     */
    public function isWeakPassword() : self
    {
        if(!$this->validField)
            return $this;

        $len = strlen($this->value);

        // Length between 8 and 32
        if($len < 8 || $len > 32)
            return $this->registerError('invalid_password_length');

        // Contains special chars ['.', '*', '=', '#', '$', '@', '%']
        $rules = ['lowercase' => false, 'uppercase' => false, 'numeric' => false];
        for($i = 0; $i < $len; $i++)
            if(ctype_digit($this->value[$i])) $rules['numeric'] = true;
            elseif(ctype_lower($this->value[$i])) $rules['lowercase'] = true;
            elseif(ctype_upper($this->value[$i])) $rules['uppercase'] = true;

        if(!$rules['lowercase'])
            return $this->registerError('password_without_lowercase');
        if(!$rules['uppercase'])
            return $this->registerError('password_without_uppercase');
        if(!$rules['numeric'])
            return $this->registerError('password_without_numeric');

        $this->realValue = $this->value;

        return $this;
    }

    /**
     * @return $this
     */
    public function isStrongPassword() : self
    {
        if(!$this->validField)
            return $this;
            
        $len = strlen($this->value);
        
        // Length > 12
        if($len < 12 || $len > 32)
            return $this->registerError('invalid_password_length');
            
        // Contains special chars ['.', '*', '=', '#', '$', '@', '%']
        $specials = ['.', '*', '=', '$', '@', '%'];
        $rules = ['lowercase' => false, 'uppercase' => false, 'numeric' => false, 'special' => false];
        for($i = 0; $i < $len; $i++)
            if(ctype_digit($this->value[$i])) $rules['numeric'] = true;
            elseif(ctype_lower($this->value[$i])) $rules['lowercase'] = true;
            elseif(ctype_upper($this->value[$i])) $rules['uppercase'] = true;
            elseif(in_array($this->value[$i], $specials)) $rules['special'] = true;

        if(!$rules['lowercase'])
            return $this->registerError('password_without_lowercase');
        if(!$rules['uppercase'])
            return $this->registerError('password_without_uppercase');
        if(!$rules['numeric'])
            return $this->registerError('password_without_numeric');
        if(!$rules['special'])
            return $this->registerError('password_without_special');

        $this->realValue = $this->value;

        return $this;
    }
}