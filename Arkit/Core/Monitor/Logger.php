<?php

namespace Arkit\Core\Monitor;

use Arkit\Core\HTTP\RequestInterface;
use Arkit\Core\Monitor\Log\LogsHandlerInterface;

const LOG_LEVEL_REQUEST = 'Request';
const LOG_LEVEL_INFO = 'Info';
const LOG_LEVEL_NOTICE = 'Notice';
const LOG_LEVEL_WARNING = 'Warning';
const LOG_LEVEL_ALERT = 'Alert';
const LOG_LEVEL_DEBUG = 'Debug';
const LOG_LEVEL_ERROR = 'Error';

/**
 * Class LogsManager
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

    public function setHandler(LogsHandlerInterface $handler, array $logTypes): void
    {
        $handler->init();

        foreach ($logTypes as $type)
            if (isset($this->handlers[$type]))
                $this->handlers[$type][] = $handler;
    }

    /**
     * @param RequestInterface $request
     * @return void
     */
    public function logRequest(RequestInterface &$request): void
    {
        foreach ($this->handlers[LOG_LEVEL_REQUEST] as $handler)
            $handler->registerRequest($request);
    }

    /**
     * @param string $eventType
     * @param string $message
     * @param array|null $context
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
     * @param string $message
     * @param array|null $context
     * @return void
     */
    public function debug(string $message, array $context = null): void
    {
        $this->log(LOG_LEVEL_DEBUG, $message, $context);
    }

    /**
     * Normal but significant events
     *
     * @param string $info
     * @param array|null $context
     * @return void
     */
    public function info(string $info, array $context = null): void
    {
        $this->log(LOG_LEVEL_INFO, $info, $context);
    }

    /**
     * Normal but significant events
     *
     * @param string $notice
     * @param array|null $context
     * @return void
     */
    public function notice(string $notice, array $context = null): void
    {
        $this->log(LOG_LEVEL_NOTICE, $notice, $context);
    }

    /**
     * Exceptional occurrences that are not errors
     *
     * @param string $warning
     * @param array|null $context
     * @return void
     */
    public function warning(string $warning, array $context = null): void
    {
        $this->log(LOG_LEVEL_WARNING, $warning, $context);
    }

    /**
     * Action must be taken immediately
     *
     * @param string $alert
     * @param array|null $context
     * @return void
     */
    public function alert(string $alert, array $context = null): void
    {
        $this->log(LOG_LEVEL_ALERT, $alert, $context);
    }

    /**
     * Report an error of application performance
     *
     * @param string $errorType
     * @param string $message
     * @param string $file
     * @param int $line
     * @param mixed $backtrace
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