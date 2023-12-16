<?php

namespace Arkit\Core\Filter;

use \Arkit\Core\HTTP\RequestInterface;

/**
 * Input form validator.
 * 
 * Help to validate each form field sent from the client side.
 */
class InputValidator
{
    /**
     * @var ?array
     */
    private ?array $errors;

    /**
     * @var ?array
     */
    private ?array $errors_hash;

    /**
     * @var ?RequestInterface
     */
    private ?RequestInterface $request;

    /**
     * @var ?array
     */
    private ?array $current;

    /**
     * @var ?string
     */
    private ?string $defaultCsrfFieldName;

    /**
     * @var Input\CSRFHandler
     */
    private $csrfHandler;

    /**
     * @var ?Input\Validator\IntValidator
     */
    private $intValidator;
	
	/**
     * @var ?Input\Validator\NumericValidator
     */
    private $numericValidator;
	
	/**
     * @var ?Input\Validator\BoolValidator
     */
    private $booleanValidator;

    /**
     * @var ?Input\Validator\PersonalDataValidator
     */
    private $personalDataValidator;

    /**
     * @var ?Input\Validator\InternetAddressValidator
     */
    private $internetAddressValidator;

    /**
     * @var ?Input\Validator\CreditCardValidator
     */
    private $creditCardValidator;

    /**
     * @var ?Input\Validator\StringValidator
     */
    private $stringValidator;

    /**
     * @var ?Input\Validator\StrNumberValidator
     */
    private $strNumberValidator;

    /**
     * @var ?Input\Validator\DateValidator
     */
    private $dateValidator;

    /**
     * @var ?Input\Validator\DateTimeValidator
     */
    private $dateTimeValidator;

    /**
     * @var ?Input\Validator\FileValidator
     */
    private $fileValidator;

    /**
     * @var ?string
     */
    private ?string $date_format;

    /**
     * @var ?string
     */
    private ?string $datetime_format;

    /**
     * @var ?string
     */
    private ?string $formId;

    /**
     * @var ?\HTMLPurifier
     */
    private $purifier;


    /**
     * Constructor of the class
     */
    public function __construct()
    {
        $this->errors = array();
        $this->formId = 'FROM';

        $this->errors_hash = null;
        $this->request = null;
        $this->current = null;
        $this->defaultCsrfFieldName = null;
        $this->csrfHandler = null;

        $this->intValidator = null;
        $this->numericValidator = null;
        $this->booleanValidator = null;
        $this->personalDataValidator = null;
        $this->internetAddressValidator = null;
        $this->creditCardValidator = null;
        $this->stringValidator = null;
        $this->strNumberValidator = null;
        $this->dateValidator = null;
        $this->dateTimeValidator = null;
        $this->fileValidator = null;
        $this->date_format = null;
        $this->datetime_format = null;
        $this->purifier = null;
    }

    /**
     * @param array $config
     * @return void
     * @throws \Exception
     */
    public function init(array &$config): void
    {
        $language = $config['language'] ?? 'en';

        // Load the hash errors
        $lang_file = dirname(__FILE__) . '/Input/lang/' . $language . '.php';
        if(!is_file($lang_file))
            throw new \Exception('Invalid language for from validator');

        $this->errors_hash = require $lang_file;

        // Load the default date format
        $this->date_format = $config['default_date_format'] ?? 'd-m-Y';
        $this->datetime_format = $config['default_datetime_format'] ?? 'd-m-Y H:i:s';
        $this->defaultCsrfFieldName = $config['CSRF']['field_name'] ?? '_token_';

        $this->csrfHandler = new Input\CSRFHandler();
        $this->csrfHandler->init($config['CSRF']);
    }

    /**
     * Generate the CRSF code
     * 
     * @param ?string $formId - If formId is set, return the code. Otherwise, set into \Arkit\App::$store
     * @param ?string $fieldName - Field name to generate the hidden html field. If not set, get the default csrf field name
     * @param ?int $expire - Time in seconds of form expiration
     * @param bool $setCookie - Indicate if set cookies into the output as other token validation
     * @return string
     */
    public function generateCsrfCode(?string $formId = null, ?string $fieldName = null, ?int $expire = null, bool $setCookie = false) : string
    {
        $fieldName = $fieldName ?? $this->defaultCsrfFieldName;
        $formId = $formId ?? $this->formId;

        $csrfCode = $this->csrfHandler->generateCode($formId, $expire);
        if($setCookie)
            $this->csrfHandler->generateCookie($formId);

        if($formId == $this->formId)
            \Arkit\App::$store['CSRF'] = [
                'CODE' => $csrfCode,
                'HTML' => '<input type="hidden" name="' . $fieldName . '" value="' . $csrfCode. '">'
            ];

        return $csrfCode;
    }


    /**
     * Validate the CSRF code
     * @param string|null $formId
     * @param string|null $fieldName
     * @param bool $validateCookie
     * @return bool
     */
    public function validateCsrfCode(?string $formId = null, ?string $fieldName = null, bool $validateCookie = false) : bool
    {
        $formId = $formId ?? $this->formId;
        $fieldName = $fieldName ?? $this->defaultCsrfFieldName;

        $this->validate($fieldName);

        $token = \Arkit\App::$Request->getPostParam($fieldName);
        if(is_null($token))
        {
            \Arkit\App::$Logs->warning('CSRF token not send');
            return $this->registerError('invalid_token');
        }
        
        $result = $this->csrfHandler->validateCode($formId, $token);
        if($result != Input\CSRFHandler::CSRF_VALIDATION_SUCCESS)
        {
            switch ($result)
            {
                case Input\CSRFHandler::CSRF_VALIDATION_INVALID:
                    \Arkit\App::$Logs->warning('Invalid CSRF token');
                    return $this->registerError('invalid_token');

                case Input\CSRFHandler::CSRF_VALIDATION_EXPIRED:
                    \Arkit\App::$Logs->warning('Expired CSRF token');
                    return $this->registerError('expired_token');
            }
        }

        if($validateCookie)
        {
            $result = $this->csrfHandler->validateCookie($formId);
            if(!$result)
            {
                \Arkit\App::$Logs->warning('Invalid CSRF cookie');
                return $this->registerError('invalid_token');
            }
        }

        return true;
    }

    /**
     * Release the cookie sent with the form
     * 
     * @param string|null $formId
     * @return void
     */
    public function releaseCsrfCookie(?string $formId = null) : void
    {
        $form = $formId ?? $this->formId;
        $this->csrfHandler->releaseCookie($form);
    }

    /**
     * Set form Id
     * @param string $formId
     * @return $this
     */
    public function setId(string $formId) : self
    {
        $this->formId = $formId;
        return $this;
    }

    /**
     * Get the request for check it values
     * 
     * @param RequestInterface &$request
     */
    public function checkValues(RequestInterface &$request) : void
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
     * Register a field validation error
     * 
     * @param string $error
     * @param ?array $params
     * @return bool
     * @throws \InvalidArgumentException
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
                throw new \InvalidArgumentException('The error message ' . $error . ' is not defined', 502);

            $message = (is_null($this->current['error'])) ? $this->errors_hash[$error] : $this->current['error'];
            if(is_array($params))
                foreach($params as $key => $value)
                    $message = str_replace('{' . $key . '}', $value, $message);

            $message = str_replace('{field}', $this->current['alias'], $message);

            $this->errors[$this->current['field']] = $message;
        }

        return false;
    }

    public function registerCustomError($errorMessage)
    {
        if(!is_null($this->current['error']))
            $this->errors[$this->current['field']] = $this->current['error'];
        else
        {
            if(isset($this->errors[$this->current['field']]))
                return false;

            $this->errors[$this->current['field']] = $errorMessage;
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
			\Arkit\App::$Session->setFlash($sessionKey, $this->errors);
		else
			\Arkit\App::$Session->set($sessionKey, $this->errors);
	}

    /**
     * @param string $language
     * @return  $this
     * @throws \Exception
     */
    public function setLanguage(string $language) : self
    {
        $lang_file = dirname(__FILE__) . '/lang/' . $language . '.php';
        if(!is_file($lang_file))
            throw new \Exception('Invalid language for from validator', 501);

        $this->errors_hash = require $lang_file;
        return $this;
    }

    /**
     * @param Input\FieldValidator $validator
     */
    private function checkAndValidate(Input\FieldValidator &$validator) : void
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
     * @return Input\Validator\IntValidator
     * @throws \Exception
     */
    public function isInteger() : Input\Validator\IntValidator
    {
        if(is_null($this->intValidator))
            $this->intValidator = new Input\Validator\IntValidator($this);

        $this->checkAndValidate($this->intValidator);
        return $this->intValidator;
    }

    /**
     * @return Input\Validator\NumericValidator
     * @throws \Exception
     */
    public function isNumeric() : Input\Validator\NumericValidator
    {
        if(is_null($this->numericValidator))
            $this->numericValidator = new Input\Validator\NumericValidator($this);

        $this->checkAndValidate($this->numericValidator);
        return $this->numericValidator;
    }

    /**
     * @return Input\Validator\BoolValidator
     * @throws \Exception
     */
    public function isBoolean() : Input\Validator\BoolValidator
    {
        if(is_null($this->booleanValidator))
            $this->booleanValidator = new Input\Validator\BoolValidator($this);

        $this->checkAndValidate($this->booleanValidator);
        return $this->booleanValidator;
    }

    /**
     * @return Input\Validator\InternetAddressValidator
     * @throws \Exception
     */
    public function isInternetAddress() : Input\Validator\InternetAddressValidator
    {
        if(is_null($this->internetAddressValidator))
            $this->internetAddressValidator = new Input\Validator\InternetAddressValidator($this);

        $this->checkAndValidate($this->internetAddressValidator);
        return $this->internetAddressValidator;
    }

    /**
     * @return Input\Validator\PersonalDataValidator
     * @throws \Exception
     */
    public function isPersonalData() : Input\Validator\PersonalDataValidator
    {
        if(is_null($this->personalDataValidator))
            $this->personalDataValidator = new Input\Validator\PersonalDataValidator($this);

        $this->checkAndValidate($this->personalDataValidator);
        return $this->personalDataValidator;
    }

    /**
     * @return Input\Validator\CreditCardValidator
     * @throws \Exception
     */
    public function isCreditCard() : Input\Validator\CreditCardValidator
    {
        if(is_null($this->creditCardValidator))
            $this->creditCardValidator = new Input\Validator\CreditCardValidator($this);

        $this->checkAndValidate($this->creditCardValidator);
        return $this->creditCardValidator;
    }


    /**
     * @return Input\Validator\StringValidator
     * @throws \Exception
     */
    public function isString() : Input\Validator\StringValidator
    {
        if(is_null($this->stringValidator))
            $this->stringValidator = new Input\Validator\StringValidator($this);

        $this->purify();
        $this->checkAndValidate($this->stringValidator);
        return $this->stringValidator;
    }

    /**
     * @return Input\Validator\StrNumberValidator
     * @throws \Exception
     */
    public function isStrNumber() : Input\Validator\StrNumberValidator
    {
        if(is_null($this->strNumberValidator))
            $this->strNumberValidator = new Input\Validator\StrNumberValidator($this);

        $this->checkAndValidate($this->strNumberValidator);
        return $this->strNumberValidator;
    }

    /**
     * @param string|null $format
     * @return Input\Validator\DateTimeValidator
     * @throws \Exception
     */
    public function isDateTime(?string $format = null) : Input\Validator\DateTimeValidator
    {
        if(is_null($this->dateTimeValidator))
            $this->dateTimeValidator = new Input\Validator\DateTimeValidator($this);

        $dateFormat = $format ?? $this->datetime_format;

        $this->dateTimeValidator->setFormat($dateFormat);
        $this->checkAndValidate($this->dateTimeValidator);

        return $this->dateTimeValidator;
    }

    /**
     * @param string|null $format
     * @return Input\Validator\DateValidator
     * @throws \Exception
     */
    public function isDate(?string $format = null) : Input\Validator\DateValidator
    {
        if(is_null($this->dateValidator))
            $this->dateValidator = new Input\Validator\DateValidator($this);

        $dateFormat = $format ?? $this->date_format;

        $this->dateValidator->setFormat($dateFormat);
        $this->checkAndValidate($this->dateValidator);

        return $this->dateValidator;
    }

    /**
     * @return Input\Validator\FileValidator
     * @throws \Exception
     */
    public function isFile() : Input\Validator\FileValidator
    {
        if(is_null($this->fileValidator))
            $this->fileValidator = new Input\Validator\FileValidator($this, $this->current['field']);

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

    public function isCustom(Input\FieldValidator $fieldValidator) : Input\FieldValidator
    {
        $this->checkAndValidate($fieldValidator);
        return $fieldValidator;
    }


    /**
     * @param ?string $value Value to purify, null for the current value
     * @return string|$this
     * @throws \Exception
     */
    public function purify(string $value = null) : string|self
    {
        if(is_null($this->purifier))
        {
            $this->purifier = new \HTMLPurifier();
        }

        if(!$value)
        {
            if(is_string($this->current['value']) && !!$this->current['value'])
                $this->current['value'] = utf8_decode( $this->purifier->purify( utf8_encode($this->current['value']) ) );

            return $this;
        }

        return utf8_decode( $this->purifier->purify( utf8_encode($value) ) );

    }

}