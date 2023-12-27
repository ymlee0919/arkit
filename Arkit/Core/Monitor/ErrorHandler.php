<?php

namespace Arkit\Core\Monitor;

/**
 * Class to handle internar errors of the application
 */
final class ErrorHandler
{
    /**
     * @var ?string
     */
    private static ?string $prevErrorReporting = null;

    /**
     * @var ?\Arkit\Core\Base\FunctionAddress
     */
    private static ?\Arkit\Core\Base\FunctionAddress $onError = null;

    /**
     * @var array
     */
    private static array $errors = [
		E_ERROR              => 'Error',
		E_WARNING            => 'Warning',
		E_PARSE              => 'Parser error',
		E_NOTICE             => 'Notice',
		E_CORE_ERROR         => 'Core error',
		E_CORE_WARNING       => 'Core warning',
		E_COMPILE_ERROR      => 'Compile error',
		E_COMPILE_WARNING    => 'Compile warning',
		E_USER_ERROR         => 'User error',
		E_USER_WARNING       => 'User warning',
		E_USER_NOTICE        => 'User notice',
		E_STRICT             => 'STRICT',
		E_RECOVERABLE_ERROR  => 'Recoverable error',
        'INTERNAL ERROR'     => 'Internal Server Error'
	];

    /**
     * Start the error handler. Store the previous error_reporting handler. 
     * It is initially called by \Arkit\App class.
     * It can be called after stop handle error by this class.
     */
    public static function init() : void
    {
        self::$prevErrorReporting = ini_get('error_reporting');
        
        if(\Arkit\App::$Env['RUN_MODE'] != TESTING_MODE)
        {
            set_error_handler('Arkit\\Core\\Monitor\\handleServerError', self::$prevErrorReporting);
            set_exception_handler('Arkit\\Core\\Monitor\\handleException');
        }
        else
        {
            ini_set('log_errors', 1);
            ini_set('error_log', constant('ERRORS_LOG_FILE') ?? \Arkit\App::fullPath('/resources/logs/testing.log'));
        }
        
    }

    /**
     * Stop current error handler. Restore the previous error handler.
     */
    public static function stop() : void
    {
        if(\Arkit\App::$Env['RUN_MODE'] != TESTING_MODE)
        {

        }
        else
        {
            restore_exception_handler();
            restore_error_handler();
    
            ini_set('error_reporting', self::$prevErrorReporting);
        }
        
    }

    /**
     * Set a function that handle an Internal Server Error
     *
     * @param \Arkit\Core\Base\FunctionAddress $onError Function to handler an internal server error
     * @return void
     */
    public static function onInternalServerError(\Arkit\Core\Base\FunctionAddress $onError) : void
    {
        self::$onError = $onError;
    }

    /**
     * Function to handle a Server Error
     * 
     * @param int|string $type Error type
     * @param string $message Error message
     * @param string $file File where the error occured
     * @param int $line Line where the error occurred
     * @param mixed $trace Callstack
     * @return void
     */
    public static function handleServerError(int|string $type, string $message, string $file, int $line, mixed $trace) : void
	{
        if(filter_var($type, FILTER_VALIDATE_INT) !== false)
            $type = self::$errors[$type] ?? 'Internal Server Error';

        \Arkit\App::$Logs->error($type, $message, $file, $line,$trace);

		switch(\Arkit\App::$Env['RUN_MODE'])
		{
			case RELEASE_MODE:
				self::stop();
				self::showInternalServerError();
				exit;
			
			case TESTING_MODE:
                return;

            case DEBUG_MODE:
				$error = self::buildErrorMessage($type, $message, $file, $line, $trace);
                
				self::stop();
				
                http_response_code(500);
				header("Status: 500 Server Error");
				echo $error;
				exit;

			default: exit;
		}
	}

    /**
     * Handle an exception
     * @param \Exception|\Error $exception
     */
    public static function handleException(\Exception|\Error $exception) : void
    {
        $trace = $exception->getTrace();

        \Arkit\App::$Logs->error($exception::class, $exception->getMessage(), $exception->getFile(), $exception->getLine(), $trace);

        switch(\Arkit\App::$Env['RUN_MODE'])
        {
            case RELEASE_MODE:
                self::stop();
                self::showInternalServerError();
                exit;

            case TESTING_MODE:
            case DEBUG_MODE:
                $error = self::buildErrorMessage($exception::class, $exception->getMessage(), $exception->getFile(), $exception->getLine(), $trace);
                self::stop();
                self::displayError($error);
                exit;

            default: exit;
        }
    }

    /**
     * @param string $type
     * @param string $message
     * @param string $file
     * @param int $line
     * @param mixed $backtrace
     * @return string
     */
    private static function buildErrorMessage(string $type, string $message, string $file, int $line, mixed &$backtrace) : string
	{
		$stack = '';
		foreach($backtrace as $inv)
		{
			$stack .= sprintf(' # %s (<b>%s</b>)<br> &nbsp; &nbsp; => ', ($inv['file'] ?? ''), ($inv['line'] ?? ''));
			if(isset($inv['class']))
				$stack .= sprintf('%s%s%s', 
					$inv['class'], $inv['type'], $inv['function']);
			else
				$stack .= $inv['function'];

			$stack .= '<br><br>';
		}
		
		$html = sprintf('<b>Error</b>: %s<br><b>Message</b>: %s<br><b>File</b>: %s<br><b>Line</b>:%s<br></p>',
					$type, $message, $file, $line).
		        '<b>Call stack</b><br>'. $stack;
		
		return $html;
	}

    /**
     * Display internal server error message. Show a default message when onInternalServerError was not set.
     * @return void
     */
    public static function showInternalServerError() : void
    {
        if(is_null(self::$onError))
        {
            ob_end_clean();
            http_response_code(500);
            readfile( dirname(__FILE__) . '/500_PageError.html');
        }
        else
        {
            $className    = self::$onError->getClassName();
            $functionName = self::$onError->getFunctionName();

            $obj = new $className();
            $obj->$functionName();
        }
        
        exit;
    }

    private static function displayError($message)
    {
        $errorPage = '<html><head><title>Internal Server Error</title></head><body>'.
		'<h2 style="color:red">Internal Server Error</h2><p>'.
        $message.
		'</body></html>';

        http_response_code(500);
        echo $errorPage;
        exit;
    }

}

/**
 * Function for handle a server error
 * 
 * @param int $type Error type
 * @param string $message Error message
 * @param string $file File where the error ocurred
 * @param int $line Line where the error occurred
 * @param mixed $context Callstack
 * 
 * @return void
 */
function handleServerError(int $type, string $message, string $file, int $line, mixed $context = null) : void
{
    if(is_null($context))
    {
        $context = debug_backtrace();
        array_pop($context);
    }
	ErrorHandler::handleServerError($type, $message, $file, $line, $context);
}

/**
 * Function for handle and exception
 * 
 * @param \Exception|\Error|array $exception
 * @throws \Exception
 */
function handleException(\Exception|\Error|array $exception) : void
{
    if(is_a($exception, 'Exception') || is_a($exception, 'Error'))
        ErrorHandler::handleException($exception);
    else
        ErrorHandler::handleServerError($exception['type'], $exception['message'], $exception['file'], $exception['line'], $exception['context']);
}