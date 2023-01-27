<?php

namespace Arkit\Core\HTTP\Response;

class RedirectDispatcher implements DispatcherInterface
{

    /**
     * Internal array values
     * @var array
     */
    private array $values;

    /**
     * Input errors
     *
     * @var array
     */
    private array $inputErrors;

    /**
     * Messages
     *
     * @var array
     */
    private array $messages;


    public function __construct()
    {
        $this->values = [];
        $this->inputErrors = [];
        $this->messages = [];
    }

    /**
     * @inheritDoc
     */
    public function assignValues(array &$values): void
    {
        $this->values = $this->values + $values;
    }

    /**
     * @inheritDoc
     */
    public function assign(string $varName, mixed $value): void
    {
        $this->values[$varName] = $value;
    }

    /**
     * @inheritDoc
     */
    public function inputError(string $fieldName, string $error): void
    {
        $this->inputErrors[$fieldName] = $error;
    }

    /**
     * @inheritDoc
     */
    public function inputErrors(array $errors): void
    {
        $this->inputErrors = $this->inputErrors + $errors;
    }

    /**
     * @inheritDoc
     */
    public function error(string $errorType, string $message): void
    {
        $this->messages[$errorType] = $message;
    }

    /**
     * @inheritDoc
     */
    public function warning(string $message): void
    {
        $this->messages['WARNING'] = $message;
    }

    /**
     * @inheritDoc
     */
    public function success(string $message): void
    {
        $this->messages['SUCCESS'] = $message;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(?string $resource, ?array $arguments = null): void
    {
        // NOTE: The resource is the url

        // Build the flash memory to store this into session
        $flashMemory = new \Arkit\Helper\View\ViewFlashMemory($resource);

        // Save values
        if(!empty($this->values))
            foreach ($this->values as $fieldName => $value)
                $flashMemory->storeCustomValue($fieldName, $value);

        // Save input errors
        if(!empty($this->inputErrors))
            $flashMemory->storeInputErrors($this->inputErrors);

        // Save messages
        if(!empty($this->messages))
            foreach ($this->messages as $errorType => $message)
                if($errorType === 'WARNING')
                    $flashMemory->storeWarning($message);
                elseif ($errorType === 'SUCCESS')
                    $flashMemory->storeSuccessMessage($message);
                else
                    $flashMemory->storeCustomError($errorType, $message);

        // Perform redirection
        header("Location: $resource");
        exit;
    }
}