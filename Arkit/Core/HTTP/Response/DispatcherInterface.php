<?php

namespace Arkit\Core\HTTP\Response;

/**
 * Class for dispatch the payload response
 */
interface DispatcherInterface
{

    /**
     * Assign multiple values to the dispatcher
     *
     * @param array $values VarName => Value array
     * @return void
     */
    public function assignValues(array &$values) : void;

    /**
     * Assign a single value to the dispatcher
     *
     * @param string $varName Name inside the response
     * @param mixed $value Value of the var
     * @return void
     */
    public function assign(string $varName, mixed $value) : void;

    /**
     * Set an input error associated to a field
     *
     * @param string $fieldName Field of error
     * @param string $error Error description
     * @return void
     */
    public function inputError(string $fieldName, string $error) : void;

    /**
     * Set a list of input errors
     *
     * @param array $errors FieldName => ErrorMessage array
     * @return void
     */
    public function inputErrors(array $errors) : void;

    /**
     * Set a custom error type
     *
     * @param string $errorType Error type
     * @param string $message Error message
     * @return void
     */
    public function error(string $errorType, string $message) : void;

    /**
     * Set a warning
     *
     * @param string $message Warning message
     * @return void
     */
    public function warning(string $message) : void;

    /**
     * Set a success message
     *
     * @param string $message Success message
     * @return void
     */
    public function success(string $message) : void;

    /**
     * Dispatch the content to the payload
     *
     * @param ?string $resource Resource to dispatch
     * @param array|null $arguments Arguments for dispatching
     * @return void
     */
    public function dispatch(?string $resource, ?array $arguments = null) : void;

}