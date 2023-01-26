<?php
namespace Arkit\Core\Filter\Input\Validator;

use \Arkit\Core\Filter\InputValidator;
use \Arkit\Core\Filter\Input\FieldValidator;

/**
 * Class BoolValidator
 */
class FileValidator extends FieldValidator {

    /**
     * @var string
     */
    private string $fieldName;

    /**
     * @param InputValidator $form
     * @param string $fieldName
     */
    public function __construct(InputValidator &$form, string $fieldName)
    {
        parent::__construct($form);
        $this->fieldName = $fieldName;
    }

    /**
     * @return $this
     */
    public function check() : self
    {
        $this->validField = isset($_FILES[$this->fieldName]) && strlen($_FILES[$this->fieldName]['name']) > 0;

        if(!!$this->validField)
            $this->realValue = $_FILES[$this->fieldName];

        return $this;
    }

    /**
     * @return $this
     */
    public function notEmpty() : self
    {
        $this->check();

        if(!$this->validField)
            return $this->registerError('not_empty_field');

        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue() : ?string
    {
        return $this->realValue;
    }

    /**
     * @return $this
     */
    public function isRequired() : self
	{
        if(!$this->validField)
            return $this->registerError('file_not_set');

		return $this;
	}

    /**
     * @return $this
     */
    public function isImage() : self
	{
        if(!$this->validField)
            return $this;

        if(!preg_match('#^(gif|jpg|jpeg|jpe|png)$#i', $_FILES[$this->fieldName]['name']))
            return $this->registerError('invalid_image_file');

        return $this;
	}

    /**
     * @param string $extension
     * @return $this
     */
    public function checkExtension(string $extension) : self
    {
        if(!$this->validField)
            return $this;

        $fileName = $_FILES[$this->fieldName]['name'];

        $valid = (strtolower(file_type($fileName)) == strtolower($extension));

        if(!$valid)
            return $this->registerError('invalid_file_type', ['type' => strtoupper($extension)]);

        return $this;
    }
}