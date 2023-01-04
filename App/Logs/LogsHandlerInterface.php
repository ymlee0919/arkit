<?php

interface LogsHandlerInterface
{

    /**
     * Initialize the handler
     * @return void
     */
    public function init() : void;

    /**
     * Register a request made to the application
     * @param Request $request
     * @return bool
     */
    public function registerRequest(Request &$request) : bool;

    /**
     * Register an internal event
     * @param string $logType
     * @param string $message
     * @param array|null $context
     * @return bool
     */
    public function registerLog(string $logType, string $message, ?array $context = null) : bool;

    /**
     * Register a critical error into the application
     * @param string $message
     * @param string $file
     * @param int $line
     * @param mixed $backtrace
     * @return bool
     */
    public function registerError(string $errorType, string $message, string $file, int $line, mixed &$backtrace) : bool;
}