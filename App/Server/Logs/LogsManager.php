<?php

require 'LogsHandlerInterface.php';

/**
 * Class LogsManager
 */
final class LogsManager
{
    /**
     * @var LogsHandlerInterface
     */
    private LogsHandlerInterface $handler;

    /**
     * @param string $handler Name of the handler
     * @param array $config Configuration
     * @throws Exception
     */
    public function __construct(string $handler, array &$config)
    {
        switch (strtolower($handler))
        {
            case 'file':
                import('FileLogsHandler', 'App.Server.Logs.FileLogsHandler');
                $this->handler = new FileLogsHandler($config);
                break;
            default:
                throw new Exception("Logs handler '$handler' not found");
        }
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

        return $this->handler->register($eventType, $message, $context);
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
        $this->log('Debug', $message);
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
        $this->log('Notice', $notice);
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
        $this->log('Warning', $warning);
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
        $this->log('Alert', $alert);
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
        return $this->handler->registerError($errorType, $message, $file, $line, $backtrace);
    }
}