<?php

namespace Arkit\Core\Monitor\Log;

use \Arkit\Core\HTTP\RequestInterface;

/**
 * Interface that must implement each log handler
 */
interface LogsHandlerInterface
{

    /**
     * Initialize the handler
     * @return void
     */
    public function init(): void;

    /**
     * Register a request made to the application
     * @param RequestInterface $request Request to be registered
     * @return bool
     */
    public function registerRequest(RequestInterface &$request): bool;

    /**
     * Register an internal event
     * 
     * @param string $logType Log type
     * @param string $message Message to register
     * @param array|null $context Callstack
     * @return bool
     */
    public function registerLog(string $logType, string $message, ?array $context = null): bool;

    /**
     * Register a critical error into the application
     * 
     * @param string $errorType Type of error 
     * @param string $message Error message
     * @param string $file File where the error occurred
     * @param int $line Line where the error occurred
     * @param mixed $backtrace Callstack
     * @return bool
     */
    public function registerError(string $errorType, string $message, string $file, int $line, mixed &$backtrace): bool;
}