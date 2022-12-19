<?php

import('FieldValidator','App.Server.Form.FieldValidator');
import('Crypt','App.Server.Security.Crypt');

/**
 * Class FormValidator
 */
class FormValidator {

    /**
     * @var ?array
     */
    private ?array $errors = null;

    /**
     * @var ?array
     */
    private ?array $errors_hash = null;

    /**
     * @var ?Request
     */
    private ?Request $request = null;

    /**
     * @var ?array
     */
    private ?array $current = null;

    /**
     * @var ?IntValidator
     */
    private ?IntValidator $intValidator = null;
	
	/**
     * @var ?NumericValidator
     */
    private ?NumericValidator $numericValidator = null;
	
	/**
     * @var ?BoolValidator
     */
    private ?BoolValidator $booleanValidator = null;

    /**
     * @var ?PersonalDataValidator
     */
    private ?PersonalDataValidator $personalDataValidator = null;

    /**
     * @var ?InternetAddressValidator
     */
    private ?InternetAddressValidator $internetAddressValidator = null;

    /**
     * @var ?CreditCardValidator
     */
    private ?CreditCardValidator $creditCardValidator = null;

    /**
     * @var ?StringValidator
     */
    private ?StringValidator $stringValidator = null;

    /**
     * @var ?StrNumberValidator
     */
    private ?StrNumberValidator $strNumberValidator = null;

    /**
     * @var ?DateValidator
     */
    private ?DateValidator $dateValidator = null;

    /**
     * @var ?DateTimeValidator
     */
    private ?DateTimeValidator $dateTimeValidator = null;

    /**
     * @var ?FileValidator
     */
    private ?FileValidator $fileValidator = null;

    /**
     * @var ?string
     */
    private ?string $date_format = null;

    /**
     * @var ?string
     */
    private ?string $datetime_format = null;

	/**
	 * @var string
	 */
    private static string $csrf_key = '?0=CbEd#tk3109$';

    /**
     * @var ?string
     */
    private ?string $formId = null;

    /**
     * @var ?HTMLPurifier
     */
    private ?HTMLPurifier $purifier = null;


    /**
     * @param ?string $language
     * @throws Exception
     */
    public function __construct(?string $language = null)
    {
        $this->errors = array();

        // Load the hash errors
        if(!is_null($language))
        {
            $lang_file = dirname(__FILE__) . '/lang/' . $language . '.php';
            if(!is_file($lang_file))
                throw new Exception('Invalid language for from validator', 501);
        }
        else
            $lang_file = dirname(__FILE__) . '/lang/' . App::$config['validation']['default_language'] . '.php';

        $this->errors_hash = require $lang_file;

        // Load the default date format
        $this->date_format = App::$config['validation']['default_date_format'];
        $this->datetime_format = App::$config['validation']['default_datetime_format'];

        $this->formId = 'FROM';
    }

    /**
     * @param ?int $expire - Time in seconds of form expiration
     * @param string $fieldName
     * @param ?string $formId - If formId is set, return the code. Otherwise, save into App::$store
     * @returns string
     */
    public function generateCsrfCode(?int $expire = null, string $fieldName = '_token_', ?string $formId = null) : string
    {
        if(is_null($expire)) $expire = APP::$config['security']['csrf_expire'];

        if(!Session::is_set('CSRF'))
            $_SESSION['CSRF'] = str_shuffle(md5(session_id()) . session_id() . sha1(session_id()));

        $code = $_SESSION['CSRF'];

        if(is_null($formId))
            $code .= '|' . strval( $_SERVER['REQUEST_TIME'] + intval($expire) ) . '|' . trim(md5( self::$csrf_key . $this->formId));
        else
            $code .= '|' . strval( $_SERVER['REQUEST_TIME'] + intval($expire) ) . '|' . trim(md5( self::$csrf_key . $formId));

        $code = Crypt::encrypt($code, Session::getCryptKey());

        if(!$formId)
            App::$store['CSRF'] = [
                'CODE' => $code,
                'HTML' => '<input type="hidden" name="' . $fieldName . '" value="' . $code. '">'
            ];

        return $code;
    }

    /**
     * @param string $postField
     * @return bool
     */
    public function validateCsrfCode(string $postField = '_token_') : bool
    {
        $this->validate($postField);

        $token = App::$Request->getPostParam($postField);
        if(is_null($token))
            return $this->registerError('invalid_form_token');
        
        //$token = @Crypt::cryptare($token, self::$csrf_key, 'rijndael-128', false);
        $token = @Crypt::decrypt($token, Session::getCryptKey());
        if(!$token)
            return $this->registerError('invalid_form_token');

        $parts = explode('|', $token);
        if(count($parts) != 3)
            return $this->registerError('invalid_form_token');

        if(trim($parts[0]) != $_SESSION['CSRF']) return $this->registerError('invalid_form_token');
        if(trim($parts[2]) != md5( self::$csrf_key . $this->formId) ) return $this->registerError('invalid_form_token');
        if($_SERVER['REQUEST_TIME'] > intval($parts[1])) return $this->registerError('token_expired');

        return true;
    }

    /**
     * @param string $formId
     * @return $this
     */
    public function setId(string $formId) : self
    {
        $this->formId = $formId;
        return $this;
    }

    /**
     * @param Request &$request
     */
    public function checkValues(Request &$request) : void
	{
		$this->request = $request;
		
		$this->errors = array();
		
		$this->current = array(
			'field' => null,            // Store the name of the field
			'value' => null,            // Store the value of the field
			'alias' => null,            // Store alias name for error
			'error' => null,            // Store the error message
			'required' => null,         // Indicate if is required
            'allowEmpty' => null,       // Indicate if allow empty
            'emptyMessage' => null      // Store message for empty
		);
	}

    /**
     * @param string $error
     * @param ?array $params
     * @return bool
     * @throws InvalidArgumentException
     */
    public function registerError(string $error, ?array $params = null) : bool
    {
        if(!is_null($this->current['error']))
            $this->errors[$this->current['field']] = $this->current['error'];
        else
        {
            if(isset($this->errors[$this->current['field']]))
                return false;

            if(!isset($this->errors_hash[$error]))
                throw new InvalidArgumentException('The error message ' . $error . ' is not defined', 502);

            $message = (is_null($this->current['error'])) ? $this->errors_hash[$error] : $this->current['error'];
            if(is_array($params))
                foreach($params as $key => $value)
                    $message = str_replace('{' . $key . '}', $value, $message);

            $message = str_replace('{field}', $this->current['alias'], $message);

            $this->errors[$this->current['field']] = $message;
        }

        return false;
    }

    /**
     * @param string $field
     * @return ?string
     */
    public function getError(string $field) : ?string
    {
        if(isset($this->errors[$field]))
            return $this->errors[$field];

        return null;
    }

    /**
     * @return array
     */
    public function getErrors() : array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function isValid() : bool
	{
		return (count($this->errors) == 0);
	}

    /**
     * @param string $field
     * @param mixed $value
     * @return $this
     */
    public function validate(string $field, mixed $value = null) : self
    {
		$this->current['field'] = $field;
		$this->current['alias'] = $field;
		$this->current['value'] = $value;
		$this->current['error'] = null;
		$this->current['required'] = null;
		$this->current['allowEmpty'] = true;
		$this->current['emptyMessage'] = null;

		if(is_null($value) && !is_null($this->request->getPostParam($field)))
			$this->current['value'] = $this->request->getPostParam($field);

        return $this;
    }

    /**
     * @param string $fileIndex
     * @return $this
     */
    public function validateFile(string $fileIndex) : self
    {
		$this->current['field'] = $fileIndex;
		$this->current['alias'] = $fileIndex;
		$this->current['value'] = $this->request->getFileParam($fileIndex);
		$this->current['error'] = null;
		$this->current['required'] = null;
		$this->current['allowEmpty'] = true;
		$this->current['emptyMessage'] = null;

        return $this;
    }

    /**
     * @param string $alias
     * @return $this
     */
    public function alias(string $alias) : self
	{
		$this->current['alias'] = $alias;
		return $this;
	}

    /**
     * @param ?string $errorMessage
     * @return $this
     */
    public function isRequired(?string $errorMessage = null) : self
	{
        $this->current['required'] = true;

		if(is_null($this->current['value']))
        {
            if(is_null($errorMessage))
                $this->registerError('field_required');
            else
                $this->current['error'] = $errorMessage;
        }

		return $this;
	}

    /**
     * @param ?string $errorMessage
     * @return $this
     */
    public function notEmpty(?string $errorMessage = null) : self
	{
        $this->current['allowEmpty'] = false;

        if(!is_null($errorMessage))
            $this->current['emptyMessage'] = $errorMessage;
		
		return $this;
	}

    /**
     * @param string $error
     * @return  $this
     */
    public function setCustomError(string $error) : self
	{
		$this->current['error'] = $error;
        return $this;
	}

    /**
     * @param string $format
     * @return  $this
     */
    public function setDateFormat(string $format) : self
    {
        $this->date_format = $format;
        return $this;
    }

    /**
     * @param string $format
     * @return  $this
     */
    public function setDateTimeFormat(string $format) : self
    {
        $this->datetime_format = $format;
        return $this;
    }


    /**
     * @param string $sessionKey
     * @param bool $asFlash
     */
    public function storeErrorsInSession(string $sessionKey = 'FROM_ERRORS', bool $asFlash = true) : void
    {
    	if($asFlash)
			Session::set_flash($sessionKey, $this->errors);
		else
			Session::set($sessionKey, $this->errors);
	}

    /**
     * @param string $language
     * @return  $this
     * @throws Exception
     */
    public function setLanguage(string $language) : self
    {
        $lang_file = dirname(__FILE__) . '/lang/' . $language . '.php';
        if(!is_file($lang_file))
            throw new Exception('Invalid language for from validator', 501);

        $this->errors_hash = require $lang_file;
        return $this;
    }

    /**
     * @param FieldValidator $validator
     */
    private function checkAndValidate(FieldValidator &$validator) : void
    {
        $validator->set($this->current['value']);
        if(!$this->current['allowEmpty'])
        {
            $valid = $validator->notEmpty();

            if(!$valid)
            {
                if(!is_null($this->current['emptyMessage']))
                    $this->errors[$this->current['field']] = $this->current['emptyMessage'];
                else
                    $this->registerError('not_empty_field');
            }
        }

        $validator->check();
    }

    /**
     * @return IntValidator
     * @throws Exception
     */
    public function isInteger() : IntValidator
    {
        if(is_null($this->intValidator))
        {
            import('IntValidator','App.Server.Form.Validators.IntValidator');
            $this->intValidator = new IntValidator($this);
        }

        $this->checkAndValidate($this->intValidator);
        return $this->intValidator;
    }

    /**
     * @return NumericValidator
     * @throws Exception
     */
    public function isNumeric() : NumericValidator
    {
        if(is_null($this->numericValidator))
        {
            import('NumericValidator','App.Server.Form.Validators.NumericValidator');
            $this->numericValidator = new NumericValidator($this);
        }

        $this->checkAndValidate($this->numericValidator);
        return $this->numericValidator;
    }

    /**
     * @return BoolValidator
     * @throws Exception
     */
    public function isBoolean() : BoolValidator
    {
        if(is_null($this->booleanValidator))
        {
            import('BoolValidator','App.Server.Form.Validators.BoolValidator');
            $this->booleanValidator = new BoolValidator($this);
        }

        $this->checkAndValidate($this->booleanValidator);
        return $this->booleanValidator;
    }

    /**
     * @return InternetAddressValidator
     * @throws Exception
     */
    public function isInternetAddress() : InternetAddressValidator
    {
        if(is_null($this->internetAddressValidator))
        {
            import('InternetAddressValidator', 'App.Server.Form.Validators.InternetAddressValidator');
            $this->internetAddressValidator = new InternetAddressValidator($this);
        }

        $this->checkAndValidate($this->internetAddressValidator);
        return $this->internetAddressValidator;
    }

    /**
     * @return PersonalDataValidator
     * @throws Exception
     */
    public function isPersonalData() : PersonalDataValidator
    {
        if(is_null($this->personalDataValidator))
        {
            import('PersonalDataValidator', 'App.Server.Form.Validators.PersonalDataValidator');
            $this->personalDataValidator = new PersonalDataValidator($this);
        }

        $this->checkAndValidate($this->personalDataValidator);
        return $this->personalDataValidator;
    }

    /**
     * @return CreditCardValidator
     * @throws Exception
     */
    public function isCreditCard() : CreditCardValidator
    {
        if(is_null($this->creditCardValidator))
        {
            import('CreditCardValidator', 'App.Server.Form.Validators.CreditCardValidator');
            $this->creditCardValidator = new CreditCardValidator($this);
        }

        $this->checkAndValidate($this->creditCardValidator);
        return $this->creditCardValidator;
    }


    /**
     * @return StringValidator
     * @throws Exception
     */
    public function isString() : StringValidator
    {
        if(is_null($this->stringValidator))
        {
            import('StringValidator', 'App.Server.Form.Validators.StringValidator');
            $this->stringValidator = new StringValidator($this);
        }

        $this->purify();
        $this->checkAndValidate($this->stringValidator);
        return $this->stringValidator;
    }

    /**
     * @return StrNumberValidator
     * @throws Exception
     */
    public function isStrNumber() : StrNumberValidator
    {
        if(is_null($this->stringValidator))
        {
            import('StrNumberValidator', 'App.Server.Form.Validators.StrNumberValidator');
            $this->strNumberValidator = new StrNumberValidator($this);
        }

        $this->checkAndValidate($this->strNumberValidator);
        return $this->strNumberValidator;
    }

    /**
     * @return DateTimeValidator
     * @throws Exception
     */
    public function isDateTime() : DateTimeValidator
    {
        if(is_null($this->dateTimeValidator))
        {
            import('DateTimeValidator', 'App.Server.Form.Validators.DateTimeValidator');
            $this->dateTimeValidator = new DateTimeValidator($this);
        }

        $this->dateTimeValidator->setFormat($this->datetime_format);
        $this->checkAndValidate($this->dateTimeValidator);

        return $this->dateTimeValidator;
    }

    /**
     * @return DateValidator
     * @throws Exception
     */
    public function isDate() : DateValidator
    {
        if(is_null($this->dateValidator))
        {
            import('DateValidator', 'App.Server.Form.Validators.DateValidator');
            $this->dateValidator = new DateValidator($this);
        }

        $this->dateValidator->setFormat($this->date_format);
        $this->checkAndValidate($this->dateValidator);

        return $this->dateValidator;
    }

    /**
     * @return FileValidator
     * @throws Exception
     */
    public function isFile() : FileValidator
    {
        if(is_null($this->fileValidator))
        {
            import('FileValidator', 'App.Server.Form.Validators.FileValidator');
            $this->fileValidator = new FileValidator($this, $this->current['field']);
        }

        $this->fileValidator->set($this->current['value']);
        $this->fileValidator->check();

        // Clear errors for file
        if(isset($this->errors[$this->current['field']]))
            unset($this->errors[$this->current['field']]);

        // Validate required
        if(!$this->current['required'])
            $this->fileValidator->isRequired();

        // Validate empty
        if(!$this->current['allowEmpty'])
        {
            $valid = $this->fileValidator->notEmpty();

            if(!$valid && !is_null($this->current['emptyMessage']))
                $this->errors[$this->current['field']] = $this->current['emptyMessage'];
        }

        return $this->fileValidator;
    }


    /**
     * @param ?string $value Value to purify, null for the current value
     * @return string|$this
     * @throws Exception
     */
    public function purify(string $value = null) : string|self
    {
        if(is_null($this->purifier))
        {
            import('HtmlPurifier','Libs.HtmlPurifier.autoload');
            $this->purifier = new HTMLPurifier();
        }

        if(!$value)
        {
            if(!!$this->current['value'])
                $this->current['value'] = utf8_decode( $this->purifier->purify( utf8_encode($this->current['value']) ) );

            return $this;
        }

        return utf8_decode( $this->purifier->purify( utf8_encode($value) ) );

    }

}