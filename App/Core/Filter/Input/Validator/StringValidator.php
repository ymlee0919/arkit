<?php
namespace Arkit\Core\Filter\Input\Validator;

use \Arkit\Core\Filter\Input\FieldValidator;

/**
 * Class StringValidator
 */
class StringValidator extends FieldValidator {

    /**
     * @return $this
     */
    public function check() : self
    {
        $this->realValue = $this->value;
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
     * @param integer $min
     * @param ?integer $max
     * @return $this
     */
	public function withLengthBetween(int $min = 0, ?int $max = null) : self
	{
        if(!$this->validField)
            return $this;
            
		if($min == 0 && is_null($max))
			throw new \InvalidArgumentException('You must establish length boundaries to compare the string length',601);
		
		$length = strlen($this->value);

        if($length < $min)
            return $this->registerError('invalid_string_min_size', ['min' => $min]);

		if(is_null($max))
			return $this;
		else
		{
			if($length > $max)
				return $this->registerError('invalid_string_max_size', ['max' => $min]);
		}

        return $this;
	}

    /**
     * @param integer $min
     * @param integer|null $max
     * @return $this
     */
    public function wordsCount(int $min = 0, ?int $max = null) : self
    {
        if(!$this->validField)
            return $this;

        if($min == 0 && is_null($max))
            throw new \InvalidArgumentException('You must establish length boundaries to compare the string length',601);

        // Get the string value
        $str = strval($this->value);
        $str = trim($str);

        // Clean not readable characters
        $str = strtr($str,'[]{}()<>¿?%$+/*-,.;"¡!=', '                       ');

        // Clean double white spaces
        while(FALSE !== strpos($str, '  '))
            $str = str_replace('  ', ' ', $str);

        // Count white spaces
        $count = substr_count($str, ' ');
        // Add one
        $count++;

        if($count < $min)
            return $this->registerError('invalid_string_min_words', ['min' => $min]);
        if(!!$max && $count > $max)
            return $this->registerError('invalid_string_max_words', ['max' => $max]);

        return $this;
    }

    /**
     * @param string $part
     * @param bool $matchCase
     * @return $this
     */
	public function contains(string $part, bool $matchCase = true) : self
	{
        if(!$this->validField)
            return $this;
        
        if(is_null($part))
			throw new \InvalidArgumentException('Invalid part of string',601);
            
		if($matchCase)
		{
			if(strpos($this->value, $part) === false)
				return $this->registerError('string_must_contain', ['value' => $part]);
		}
		else
		{
			if(stripos($this->value, $part) === false)
				return $this->registerError('string_must_contain_matchcase', ['value' => $part]);
		}

        return $this;
	}

    /**
     * @param string $begin
     * @param bool $matchCase
     * @return $this
     */
	public function startWith(string $begin, bool $matchCase = true) : self
	{
        if(!$this->validField)
            return $this;
        
        if(is_null($begin))
			throw new \InvalidArgumentException('Invalid begin of string',601);
		
		if($matchCase)
		{
			if(strpos($this->value, $begin) > 0)
				return $this->registerError('string_must_begin', ['value' => $begin]);
		}
		else
		{
			if(stripos($this->value, $begin) > 0)
				return $this->registerError('string_must_begin_matchcase', ['value' => $begin]);
		}

        return $this;
	}

    /**
     * @param string $final
     * @param bool $matchCase
     * @return $this
     */
	public function endWith(string $final, bool $matchCase = true) : self
	{
        if(!$this->validField)
            return $this;
        
        if($matchCase)
        {
			$pos = strrpos($this->value, $final);
			if($pos + strlen($final) + 1 != strlen($this->value))
				return $this->registerError('string_must_end', ['value' => $final]);
		}
		else
		{
			$pos = strripos($this->value, $final);
			if($pos + strlen($final) + 1 != strlen($this->value))
				return $this->registerError('string_must_end_matchcase', ['value' => $final]);
		}

        return $this;
	}

    /**
     * @param string $pattern
     * @return $this
     */
	public function matchWith(string $pattern) : self
	{
        if(!$this->validField)
            return $this;

        if(!preg_match($pattern, $this->value))
        	return $this->registerError('invalid_expression');
        	
		return $this;
        
	}

    /**
     * @param array $items
     * @param bool $matchCase
     * @return $this
     */
	public function isOneOf(array $items, bool $matchCase = true) : self
	{
        if(!$this->validField)
            return $this;
        
        if(!is_array($items))
        	throw new \InvalidArgumentException('Invalid list of items to compare', 602);
        
        if($matchCase)
        {
			if(!in_array($this->value, $items))
	        	return $this->registerError('invalid_field');
		}
        else
        {
			foreach($items as $item)
				if(strcasecmp($item, $this->value) != 0)
					return $this->registerError('invalid_field');
		}

        return $this;
	}

    /**
     * @param array $items
     * @return $this
     */
	public function matchWithAny(array $items) : self
	{
        if(!$this->validField)
            return $this;
        
        if(!is_array($items))
        	throw new \InvalidArgumentException('Invalid list of items to match', 603);
        
        foreach($items as $pattern)
        	if(!preg_match($pattern, $this->value))
        		return $this->registerError('invalid_expression');
        
        return $this;
	}

    /**
     * @param $prefix
     * @return $this
     * @throws \Exception
     */
    public function isCryptId(string $prefix) : self
    {
        if(!$this->validField)
            return $this;

        if(!class_exists('Security\Crypt', false))
            \Loader::import('App.Security.Crypt');

        // Decode
        $str = @Crypt::decodeUrl($this->value);

        // Check the return value
        if(!$str)
            return $this->registerError('invalid_field');

        // Check the prefix
        $tokens = explode(':', $str);
        if(count($tokens) != 2)
            return $this->registerError('invalid_field');

        if($tokens[0] != $prefix)
            return $this->registerError('invalid_field');

        $this->realValue = trim($tokens[1]);

        return $this;
    }

    /**
     * @param string $secretKey
     * @return $this
     */
    public function isGoogleCaptcha(string $secretKey) : self
    {
        $data = array(
            'secret' => $secretKey,
            'response' => $this->value
        );

        $url = 'https://www.google.com/recaptcha/api/siteverify?' . http_build_query($data);

        $verify = file_get_contents($url);
        $response = json_decode($verify, true);

        unset($url);
        unset($data);
        unset($options);
        unset($context);
        unset($verify);

        $this->realValue = $response['success'];

        return $this;
    }
}