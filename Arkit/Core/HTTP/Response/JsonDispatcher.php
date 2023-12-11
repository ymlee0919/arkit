<?php

namespace Arkit\Core\HTTP\Response;

/**
 *
 */
class JsonDispatcher implements DispatcherInterface
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


    /**
     * Flag to indicate if the result is successful
     * @var bool
     */
    private bool $success;


    /**
     *
     */
    public function __construct()
    {
        $this->values = [];
        $this->inputErrors = [];
        $this->messages = [];
        $this->success = true;
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
        $this->success = false;
        $this->inputErrors[$fieldName] = $error;
    }

    /**
     * @inheritDoc
     */
    public function inputErrors(array $errors): void
    {
        $this->success = false;
        $this->inputErrors = $this->inputErrors + $errors;
    }

    /**
     * @inheritDoc
     */
    public function error(string $errorType, string $message): void
    {
        $this->success = false;
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
        $this->success = true;
        $this->messages['SUCCESS'] = $message;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(?string $resource, ?array $arguments = null): void
    {
        // Build the response
        $response = [
            'success' => $this->success
        ];

        if($this->success)
        {
            // Set the values
            foreach ($this->values as $field => $value)
                $response[strtolower($field)] = $value;

            // Set warning
            if(isset($this->messages['WARNING']))
                $response['warning'] = $this->messages['WARNING'];

            // Set warning
            if(isset($this->messages['SUCCESS']))
                $response['message'] = $this->messages['SUCCESS'];
        }
        else
        {
            if(!empty($this->inputErrors))
            {
                $response['inputErrors'] = [];
                foreach ($this->inputErrors as $field => $error)
                    $response['inputErrors'][strtolower($field)] = $error;
            }

            foreach ($this->messages as $errorType => $message)
                if($errorType !== 'SUCCESS' && $errorType !== 'WARNING')
                    $response[strtolower($errorType)] = $message;
        }

        echo json_encode($response);
    }
}