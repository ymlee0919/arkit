<?php

namespace Arkit\Core\Monitor;

use Arkit\Core\HTTP\RequestInterface;
use Arkit\Core\Monitor\Log\LogsHandlerInterface;

/**
 * Log level: Request
 */
const LOG_LEVEL_REQUEST = 'Request';

/**
 * Log level: Info
 */
const LOG_LEVEL_INFO = 'Info';

/**
 * Log level: Info
 */
const LOG_LEVEL_NOTICE = 'Notice';

/**
 * Log level: Warning
 */
const LOG_LEVEL_WARNING = 'Warning';

/**
 * Log level: Alert
 */
const LOG_LEVEL_ALERT = 'Alert';

/**
 * Log level: Debug
 */
const LOG_LEVEL_DEBUG = 'Debug';

/**
 * Log level: Error
 */
const LOG_LEVEL_ERROR = 'Error';

/**
 * Logs manager
 */
final class Logger
{
    /**
     * @var array
     */
    private array $handlers;

    /**
     * @var array
     */
    private array $config;

    /**
     * Constructor of the class
     *
     * @param array $config Configuration array
     */
    public function __construct(array &$config)
    {
        $this->handlers = [
            LOG_LEVEL_REQUEST => [],
            LOG_LEVEL_INFO => [],
            LOG_LEVEL_NOTICE => [],
            LOG_LEVEL_WARNING => [],
            LOG_LEVEL_ALERT => [],
            LOG_LEVEL_DEBUG => [],
            LOG_LEVEL_ERROR => [],
        ];

        $this->config = $config;
    }

    public function init(): void
    {
        foreach ($this->config['handlers'] as $handler) {
            $handlerClass = $handler['name'] . 'LogsHandler';
            $fullClassName = 'Arkit\\Core\\Monitor\\Log\\' . $handlerClass;
            
            if (\Loader::import($handlerClass, 'App.Core.Monitor.Log.' . $handlerClass)) {
                $logHandler = new $fullClassName($handler['config']);
                $this->setHandler($logHandler, $handler['levels']);
            }
            else
                die("The class $handlerClass do not exists");
        }
    }

    /**
     * Set logs handler to events types
     *
     * @param LogsHandlerInterface $handler LogsHandler
     * @param array $logTypes List of type of events
     * @return void
     */
    public function setHandler(LogsHandlerInterface $handler, array $logTypes): void
    {
        $handler->init();

        foreach ($logTypes as $type)
            if (isset($this->handlers[$type]))
                $this->handlers[$type][] = $handler;
    }

    /**
     * Log a request
     * 
     * @param RequestInterface $request Http client request
     * @return void
     */
    public function logRequest(RequestInterface &$request): void
    {
        foreach ($this->handlers[LOG_LEVEL_REQUEST] as $handler)
            $handler->registerRequest($request);
    }

    /**
     * Log an event
     * 
     * @param string $eventType Event type
     * @param string $message Message
     * @param array|null $context Log context, usualy taken from debug_backtrace
     * @return bool
     */
    public function log(string $eventType, string $message, array $context = null): bool
    {
        if (is_null($context)) {
            // Get class and function that register the log
            $backTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            do
                $trace = array_shift($backTrace);
            while ($trace['file'] === __FILE__);

            $context = [
                'Request' => '[' . strtoupper($_SERVER['REQUEST_METHOD']) . ']' . $_SERVER['SERVER_NAME'] . urldecode($_SERVER['REQUEST_URI']),
                'File' => $trace['file'] . ' on line ' . $trace['line']
            ];
        }

        $success = true;
        foreach ($this->handlers[$eventType] as $handler)
            $success = $success && $handler->registerLog($eventType, $message, $context);

        return $success;
    }

    /**
     * Detailed debug information.
     *
     * @param string $message Message
     * @param array|null $context Log context, usualy taken from debug_backtrace
     * @return void
     */
    public function debug(string $message, array $context = null): void
    {
        $this->log(LOG_LEVEL_DEBUG, $message, $context);
    }

    /**
     * Normal but significant events
     *
     * @param string $info Message
     * @param array|null $context Log context, usualy taken from debug_backtrace
     * @return void
     */
    public function info(string $info, array $context = null): void
    {
        $this->log(LOG_LEVEL_INFO, $info, $context);
    }

    /**
     * Normal but significant events
     *
     * @param string $notice Notice text
     * @param array|null $context Log context, usualy taken from debug_backtrace
     * @return void
     */
    public function notice(string $notice, array $context = null): void
    {
        $this->log(LOG_LEVEL_NOTICE, $notice, $context);
    }

    /**
     * Exceptional occurrences that are not errors
     *
     * @param string $warning Warning text
     * @param array|null $context Log context, usualy taken from debug_backtrace
     * @return void
     */
    public function warning(string $warning, array $context = null): void
    {
        $this->log(LOG_LEVEL_WARNING, $warning, $context);
    }

    /**
     * Action must be taken immediately
     *
     * @param string $alert Alert message 
     * @param array|null $context Log context, usualy taken from debug_backtrace
     * @return void
     */
    public function alert(string $alert, array $context = null): void
    {
        $this->log(LOG_LEVEL_ALERT, $alert, $context);
    }

    /**
     * Report an error of application performance
     *
     * @param string $errorType Error type
     * @param string $message Message
     * @param string $file File where the error happend
     * @param int $line Line number of the file
     * @param mixed $backtrace Callstack
     * @return bool
     */
    public function error(string $errorType, string $message, string $file, int $line, mixed &$backtrace): bool
    {
        // Register the error with each handler
        $success = true;
        foreach ($this->handlers[LOG_LEVEL_ERROR] as $handler)
            $success = $success && $handler->registerError($errorType, $message, $file, $line, $backtrace);

        return $success;

    }
}