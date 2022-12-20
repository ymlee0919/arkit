<?php

require 'LogsHandlerInterface.php';

const LOG_LEVEL_REQUEST = 'Request';
const LOG_LEVEL_INFO    = 'Info';
const LOG_LEVEL_NOTICE  = 'Notice';
const LOG_LEVEL_WARNING = 'Warning';
const LOG_LEVEL_ALERT   = 'Alert';
const LOG_LEVEL_DEBUG   = 'Debug';
const LOG_LEVEL_ERROR   = 'Error';

/**
 * Class LogsManager
 */
final class LogsManager
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
            LOG_LEVEL_INFO    => [],
            LOG_LEVEL_NOTICE  => [],
            LOG_LEVEL_WARNING => [],
            LOG_LEVEL_ALERT   => [],
            LOG_LEVEL_DEBUG   => [],
            LOG_LEVEL_ERROR   => [],
        ];

        $this->config = $config;
    }

    public function init()
    {
        $this->setHandler('file', $this->config,
            LOG_LEVEL_REQUEST, LOG_LEVEL_INFO, LOG_LEVEL_NOTICE, LOG_LEVEL_WARNING, LOG_LEVEL_ALERT, LOG_LEVEL_DEBUG, LOG_LEVEL_ERROR);
    }

    public function setHandler(string $handler, array &$config, string ...$logTypes)
    {
        $logHandler = null;
        switch (strtolower($handler))
        {
            case 'file':
                import('FileLogsHandler', 'App.Server.Logs.FileLogsHandler');
                $logHandler = new FileLogsHandler($config);
                break;

            case 'email':
                import('EmailLogsHandler', 'App.Server.Logs.EmailLogsHandler');
                $logHandler = new EmailLogsHandler($config);
                break;

            default:
                throw new Exception("Logs handler '$handler' not found");
        }

        foreach ($logTypes as $type)
            if(isset($this->handlers[$type]))
                $this->handlers[$type][] = $logHandler;
    }

    /**
     * @param Request $request
     * @return void
     */
    public function logRequest(Request &$request) : void
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
    public function log(string $eventType, string $message, array $context = null) : bool
    {
        if(is_null($context))
        {
            // Get class and function that register the log
            $backTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            do
                $trace = array_pop($backTrace);
            while($trace['class'] === __CLASS__);

            $context = [
                'Request' => $_SERVER['SERVER_NAME'] . urldecode($_SERVER['REQUEST_URI']),
                'Function' => (isset($inv['class'])) ? sprintf('%s%s%s', $trace['class'], $trace['type'], $trace['function']) : $trace['function'],
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
    public function debug(string $message, array $context = null) : void
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
    public function info(string $info, array $context = null) : void
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
    public function notice(string $notice, array $context = null) : void
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
    public function warning(string $warning, array $context = null) : void
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
    public function alert(string $alert, array $context = null) : void
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
    public function error(string $errorType, string $message, string $file, int $line, mixed &$backtrace) : bool
    {
        // Register the error with each handler
        $success = true;
        foreach ($this->handlers[LOG_LEVEL_ERROR] as $handler)
            $success = $success && $handler->registerError($errorType, $message, $file, $line, $backtrace);

        return $success;

    }
}