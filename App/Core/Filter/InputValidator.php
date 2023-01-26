<?php

namespace Arkit\Core\Filter;

use \Arkit\Core\HTTP\Request;
/**
 * Class FormValidator
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
     * @var ?Request
     */
    private ?Request $request;

    /**
     * @var ?array
     */
    private ?array $current;

    /**
     * @var ?string
     */
    private ?string $defaultCsrfFieldName;

    /**
     * @var Form\CSRFHandler
     */
    private $csrfHandler;

    /**
     * @var ?Form\Validator\IntValidator
     */
    private $intValidator;
	
	/**
     * @var ?Form\Validator\NumericValidator
     */
    private $numericValidator;
	
	/**
     * @var ?Form\Validator\BoolValidator
     */
    private $booleanValidator;

    /**
     * @var ?Form\Validator\PersonalDataValidator
     */
    private $personalDataValidator;

    /**
     * @var ?Form\Validator\InternetAddressValidator
     */
    private $internetAddressValidator;

    /**
     * @var ?Form\Validator\CreditCardValidator
     */
    private $creditCardValidator;

    /**
     * @var ?Form\Validator\StringValidator
     */
    private $stringValidator;

    /**
     * @var ?Form\Validator\StrNumberValidator
     */
    private $strNumberValidator;

    /**
     * @var ?Form\Validator\DateValidator
     */
    private $dateValidator;

    /**
     * @var ?Form\Validator\DateTimeValidator
     */
    private $dateTimeValidator;

    /**
     * @var ?Form\Validator\FileValidator
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

        $this->csrfHandler = new Form\CSRFHandler();
        $this->csrfHandler->init($config['CSRF']);
    }

    /**
     * @param ?string $formId - If formId is set, return the code. Otherwise, set into \Arkit\App::$store
     * @param ?string $fieldName - Field name to generate the hidden html field. If not set, get the default csrf field name
     * @param ?int $expire - Time in seconds of form expiration
     * @param bool $setCookie - Indicate if set cookies into the output as other token validation
     * @return string
     */
    public function generateCsrfCode(?string $formId = null, ?string $fieldName = null, ?int $expire = null, bool $setCookie = true) : string
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
     * @param string|null $formId
     * @param string|null $fieldName
     * @param bool $validateCookie
     * @return bool
     */
    public function validateCsrfCode(?string $formId = null, ?string $fieldName = null, bool $validateCookie = true) : bool
    {
        $formId = $formId ?? $this->formId;
        $fieldName = $fieldName ?? $this->defaultCsrfFieldName;

        $this->validate($fieldName);

        $token = \Arkit\App::$Request->getPostParam($fieldName);
        if(is_null($token))
        {
            \Arkit\App::$Logs->warning('CSRF token not send');
            return $this->registerError('invalid_form_token');
        }

        $result = $this->csrfHandler->validateCode($formId, $token);
        if($result != Form\CSRFHandler::CSRF_VALIDATION_SUCCESS)
        {
            switch ($result)
            {
                case Form\CSRFHandler::CSRF_VALIDATION_INVALID:
                    \Arkit\App::$Logs->warning('Invalid CSRF token');
                    return $this->registerError('invalid_form_token');

                case Form\CSRFHandler::CSRF_VALIDATION_EXPIRED:
                    \Arkit\App::$Logs->warning('Expired CSRF token');
                    return $this->registerError('expired_form_token');
            }
        }

        if($validateCookie)
        {
            $result = $this->csrfHandler->validateCookie($formId);
            if(!$result)
            {
                \Arkit\App::$Logs->warning('Invalid CSRF cookie');
                return $this->registerError('invalid_form_token');
            }
        }

        return true;
    }

    /**
     * Release the cookie sent with the form
     * @param string|null $formId
     * @return void
     */
    public function releaseCsrfCookie(?string $formId = null) : void
    {
        $form = $formId ?? $this->formId;
        $this->csrfHandler->releaseCookie($form);
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
     * @param Form\FieldValidator $validator
     */
    private function checkAndValidate(Form\FieldValidator &$validator) : void
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
     * @return Form\Validator\IntValidator
     * @throws \Exception
     */
    public function isInteger() : Form\Validator\IntValidator
    {
        if(is_null($this->intValidator))
        {
            \Loader::import('IntValidator','App.Form.Validators.IntValidator');
            $this->intValidator = new Form\Validator\IntValidator($this);
        }

        $this->checkAndValidate($this->intValidator);
        return $this->intValidator;
    }

    /**
     * @return Form\Validator\NumericValidator
     * @throws \Exception
     */
    public function isNumeric() : Form\Validator\NumericValidator
    {
        if(is_null($this->numericValidator))
        {
            \Loader::import('NumericValidator','App.Form.Validators.NumericValidator');
            $this->numericValidator = new Form\Validator\NumericValidator($this);
        }

        $this->checkAndValidate($this->numericValidator);
        return $this->numericValidator;
    }

    /**
     * @return Form\Validator\BoolValidator
     * @throws \Exception
     */
    public function isBoolean() : Form\Validator\BoolValidator
    {
        if(is_null($this->booleanValidator))
        {
            \Loader::import('BoolValidator','App.Form.Validators.BoolValidator');
            $this->booleanValidator = new Form\Validator\BoolValidator($this);
        }

        $this->checkAndValidate($this->booleanValidator);
        return $this->booleanValidator;
    }

    /**
     * @return Form\Validator\InternetAddressValidator
     * @throws \Exception
     */
    public function isInternetAddress() : Form\Validator\InternetAddressValidator
    {
        if(is_null($this->internetAddressValidator))
        {
            \Loader::import('InternetAddressValidator', 'App.Form.Validators.InternetAddressValidator');
            $this->internetAddressValidator = new InternetAddressValidator($this);
        }

        $this->checkAndValidate($this->internetAddressValidator);
        return $this->internetAddressValidator;
    }

    /**
     * @return Form\Validator\PersonalDataValidator
     * @throws \Exception
     */
    public function isPersonalData() : Form\Validator\PersonalDataValidator
    {
        if(is_null($this->personalDataValidator))
        {
            \Loader::import('PersonalDataValidator', 'App.Form.Validators.PersonalDataValidator');
            $this->personalDataValidator = new PersonalDataValidator($this);
        }

        $this->checkAndValidate($this->personalDataValidator);
        return $this->personalDataValidator;
    }

    /**
     * @return Form\Validator\CreditCardValidator
     * @throws \Exception
     */
    public function isCreditCard() : Form\Validator\CreditCardValidator
    {
        if(is_null($this->creditCardValidator))
        {
            \Loader::import('CreditCardValidator', 'App.Form.Validators.CreditCardValidator');
            $this->creditCardValidator = new Form\Validator\CreditCardValidator($this);
        }

        $this->checkAndValidate($this->creditCardValidator);
        return $this->creditCardValidator;
    }


    /**
     * @return Form\Validator\StringValidator
     * @throws \Exception
     */
    public function isString() : Form\Validator\StringValidator
    {
        if(is_null($this->stringValidator))
        {
            \Loader::import('StringValidator', 'App.Form.Validators.StringValidator');
            $this->stringValidator = new Form\Validator\StringValidator($this);
        }

        $this->purify();
        $this->checkAndValidate($this->stringValidator);
        return $this->stringValidator;
    }

    /**
     * @return Form\Validator\StrNumberValidator
     * @throws \Exception
     */
    public function isStrNumber() : Form\Validator\StrNumberValidator
    {
        if(is_null($this->stringValidator))
        {
            \Loader::import('StrNumberValidator', 'App.Form.Validators.StrNumberValidator');
            $this->strNumberValidator = new Form\Validator\StrNumberValidator($this);
        }

        $this->checkAndValidate($this->strNumberValidator);
        return $this->strNumberValidator;
    }

    /**
     * @return Form\Validator\DateTimeValidator
     * @throws \Exception
     */
    public function isDateTime() : Form\Validator\DateTimeValidator
    {
        if(is_null($this->dateTimeValidator))
        {
            \Loader::import('DateTimeValidator', 'App.Form.Validators.DateTimeValidator');
            $this->dateTimeValidator = new Form\Validator\DateTimeValidator($this);
        }

        $this->dateTimeValidator->setFormat($this->datetime_format);
        $this->checkAndValidate($this->dateTimeValidator);

        return $this->dateTimeValidator;
    }

    /**
     * @return Form\Validator\DateValidator
     * @throws \Exception
     */
    public function isDate() : Form\Validator\DateValidator
    {
        if(is_null($this->dateValidator))
        {
            \Loader::import('DateValidator', 'App.Form.Validators.DateValidator');
            $this->dateValidator = new Form\Validator\DateValidator($this);
        }

        $this->dateValidator->setFormat($this->date_format);
        $this->checkAndValidate($this->dateValidator);

        return $this->dateValidator;
    }

    /**
     * @return Form\Validator\FileValidator
     * @throws \Exception
     */
    public function isFile() : Form\Validator\FileValidator
    {
        if(is_null($this->fileValidator))
        {
            \Loader::import('FileValidator', 'App.Form.Validators.FileValidator');
            $this->fileValidator = new Form\Validator\FileValidator($this, $this->current['field']);
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
     * @throws \Exception
     */
    public function purify(string $value = null) : string|self
    {
        if(is_null($this->purifier))
        {
            \Loader::import('HtmlPurifier','Libs.HtmlPurifier.autoload');
            $this->purifier = new \HTMLPurifier();
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