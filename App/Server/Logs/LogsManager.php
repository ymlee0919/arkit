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
     * @var ?string
     */
    private ?string $responsibleEmail;

    /**
     * @param string $handler Name of the handler
     * @param array $config Configuration
     * @throws Exception
     */
    public function __construct(string $handler, array &$config)
    {
        $this->responsibleEmail = $config['responsible_email'] ?? null;

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
     * @param Request $request
     * @return void
     */
    public function logRequest(Request &$request) : void
    {
        $this->handler->registerRequest($request);
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
        $this->log('Debug', $message, $context);
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
        $this->log('Notice', $notice, $context);
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
        $this->log('Warning', $warning, $context);
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
        $this->log('Alert', $alert, $context);
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
        // Register the error
        $success = $this->handler->registerError($errorType, $message, $file, $line, $backtrace);

        // Send email to administrator
        if(import('EmailDispatcher', 'Services.Email.EmailDispatcher') && !is_null($this->responsibleEmail))
        {
            $stack = '';
            foreach($backtrace as $inv)
            {
                $stack .= sprintf('&nbsp; &nbsp; # %s, line %s :: ', ((isset($inv['file'])) ? $inv['file'] : '(NO FILE)'), ((isset($inv['line'])) ? $inv['line'] : '(NO LINE)'));
                $stack .= (isset($inv['class'])) ?  $inv['class'] . $inv['type'] . $inv['function'] : $inv['function'];
                $stack .= '<br><br>';
            }

            // Send email
            $content = <<<CONTENT
            Report of internal server error.<br><br>
        Time: {moment}<br>
        Request: [{method}] {domain}{url}<br>
        From: {from}<br>
        {errorType}: {message}<br> 
        File: {file}, line {line}<br>
        CallStack: <br>{callStack}
CONTENT;
            $content = strtr($content, [
                '{moment}'    => date('d/m/Y H:i:s', $_SERVER['REQUEST_TIME']),
                '{method}'    => strtoupper($_SERVER['REQUEST_METHOD']),
                '{domain}'    => $_SERVER['SERVER_NAME'],
                '{url}'       => urldecode($_SERVER['REQUEST_URI']),
                '{from}'      => $_SERVER['SERVER_ADDR'],
                '{errorType}' => $errorType,
                '{message}'   => $message,
                '{file}'      => $file,
                '{line}'      => $line,
                '{callStack}' => $stack
            ]);

            $dispatcher = new EmailDispatcher();
            $dispatcher->connect();
            $dispatcher->send($this->responsibleEmail, 'Internal Server Error - ' . $_SERVER['SERVER_NAME'], $content);
            $dispatcher->release();
        }


        return $success;

    }
}