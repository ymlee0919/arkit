<?php

/**
 * Class Router
 */
final class ErrorHandler
{
    /**
     * @var ?string
     */
    private static ?string $prev_error_reporting = null;

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
     *
     */
    public static function init() : void
    {
        self::$prev_error_reporting = ini_get('error_reporting');

        if(RUN_MODE == DEBUG_MODE)
            set_error_handler('handleServerError', E_STRICT|~E_DEPRECATED);
        else
            set_error_handler('handleServerError', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

        set_exception_handler('handleException');
    }

    /**
     *
     */
    public static function stop() : void
    {
        restore_exception_handler();
        restore_error_handler();

        ini_set('error_reporting', self::$prev_error_reporting);
    }

    /**
     * @param int|string $type
     * @param string $message
     * @param string $file
     * @param int $line
     * @param mixed $trace
     */
    public static function handleServerError(int|string $type, string $message, string $file, int $line, mixed $trace) : void
	{
        if(filter_var($type, FILTER_VALIDATE_INT) !== false)
            $type = self::$errors[$type] ?? 'Internal Server Error';

		switch(RUN_MODE)
		{
			case RELEASE_MODE:
				App::$Logs->error($type, $message, $file, $line,$trace);
				self::stop();
				self::showInternalServerError();
				exit;
			
			case TESTING_MODE:
            case DEBUG_MODE:
				$error = self::buildErrorPage($type, $message, $file, $line, $trace);
                App::$Logs->error($type, $message, $file, $line,$trace);
				self::stop();
				
				header("Status: 500 Server Error");
				echo $error;
				exit;

			default: exit;
		}
	}

    /**
     * @param Exception|Error $exception
     * @throws Exception
     */
    public static function handleException(Exception|Error $exception) : void
    {
        $trace = $exception->getTrace();

        switch(RUN_MODE)
        {
            case RELEASE_MODE:
                App::$Logs->error($exception::class, $exception->getMessage(), $exception->getFile(), $exception->getLine(), $trace);
                self::stop();
                self::showInternalServerError();
                exit;

            case TESTING_MODE:
            case DEBUG_MODE:
                $error = self::buildErrorPage($exception::class, $exception->getMessage(), $exception->getFile(), $exception->getLine(),$trace);
                App::$Logs->error($exception::class, $exception->getMessage(), $exception->getFile(), $exception->getLine(), $trace);
                self::stop();
                header("Status: 500 Server Error");
                echo $error;
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
    private static function buildErrorPage(string $type, string $message, string $file, int $line, mixed &$backtrace) : string
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
		
		$html = '<html><head><title>Internal Server Error</title></head><body>'.
		'<h2 style="color:red">Internal Server Error</h2><p>'.
			sprintf('<b>Error</b>: %s<br><b>Message</b>: %s<br><b>File</b>: %s<br><b>Line</b>:%s<br></p>',
					$type, $message, $file, $line).
		'<b>Call stack</b><br>'. $stack .
		'</body></html>';
		
		return $html;
	}

    /**
     *
     */
    public static function showInternalServerError() : void
    {
        ob_end_clean();
        header("Status: 500 Server Error");
        readfile( dirname(__FILE__) . '/500_PageError.html');
        die;
    }


}

/**
 * @param int $type
 * @param string $message
 * @param string $file
 * @param int $line
 * @param mixed $context
 * @returns void
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
 * @param Exception|Error|array $exception
 * @throws Exception
 */
function handleException(Exception|Error|array $exception) : void
{
    if(is_a($exception, 'Exception') || is_a($exception, 'Error'))
	    ErrorHandler::handleException($exception);
    else
        ErrorHandler::handleServerError($exception['type'], $exception['message'], $exception['file'], $exception['line'], $exception['context']);
}