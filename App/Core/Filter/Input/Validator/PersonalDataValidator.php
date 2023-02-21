<?php
namespace Arkit\Core\Filter\Input\Validator;

use \Arkit\Core\Filter\Input\FieldValidator;

class PersonalDataValidator extends FieldValidator 
{

    /**
     * @inheritDoc
     */
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
     * @inheritDoc
     */
    public function check() : self
    {
        return $this;
    }

    /**
     * @inheritDoc
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
            
        if(false === filter_var($this->value, FILTER_VALIDATE_EMAIL))
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
        $list = [];
        foreach($values as $value)
        {
            $email = trim($value);
            if(false === filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $this->form->registerError('invalid_email');
                return $this;
            }
            $list[] = $email;
        }

        $this->realValue = $list;

        return $this;
    }

    /**
     * @return $this
     */
    public function isPhone() : self
    {
        if(!$this->validField)
            return $this;
        
        $strPhone = strtr($this->value, [
            ' '
        ]);

        $pattern = '/^[+]?([\d]{0,3})?[\(\.\-\s]?(([\d]{1,3})[\)\.\-\s]*)?(([\d]{3,5})[\.\-\s]?([\d]{3,5})|([\d]{2}[\.\-\s]?){4})$/';
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

        $pattern = '/^[0-9a-zA-Z_\.\-]{4,}$/';
        if(!preg_match($pattern, $this->value))
        {
            $this->form->registerError('invalid_user');
            return $this;
        }
        else
        {
            // The user name must have 3 or more types of characters
            $chars = count_chars($this->value, 1);
            if(count($chars) < 3)
            {
                $this->form->registerError('invalid_user');
                return $this;
            }
        }
            
        $this->realValue = $this->value;
        return $this;
    }

    /**
     * @return $this
     */
    public function isPassword() : self
    {
        if(!$this->validField)
            return $this;

        $len = strlen($this->value);

        // Length between 8 and 32
        if($len < 8 || $len > 32)
            return $this->registerError('invalid_password_length');

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
        
        // Length > 10
        if($len < 10 || $len > 32)
            return $this->registerError('invalid_password_length');
            
        // Contains special chars ['.', '*', '=', '$', '@', '%', '+', '-', '&', '#']
        $specials = ['.', '*', '=', '$', '@', '%', '+', '-', '&', '#'];
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