<?php

/**
 * Class Router
 */
final class ErrorHandler {

    /**
     * @var array
     */
    private static array $error_list = [];

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
		E_PARSE              => 'Parcer error',
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
     * @param int $type
     * @param string $message
     */
    public static function throwFatalError(int $type, string $message) : void
	{}

    /**
     * @param int $type
     * @param string $message
     */
    public static function throwInternalServerError(int $type, string $message) : void
	{}

    /**
     * @param int $type
     * @param string $message
     */
    public static function throwInvalidAction(int $type, string $message) : void
	{}

    /**
     * @param int $type
     * @param string $message
     */
    public static function throwInvalidInputError(int $type, string $message) : void
	{}

    /**
     * @param int $type
     * @param string $message
     */
    public static function registerWarning(int $type, string $message) : void
	{}

    /**
     * @return array
     */
    public static function getErrorList() : array
    {
        return ErrorHandler::$error_list;
    }

    /**
     * @return bool
     */
    public static function existsErrors() : bool
    {
        return (count(ErrorHandler::$error_list) > 0);
    }

    /**
     * @param int $type
     * @param string $message
     * @param string $file
     * @param int $line
     * @param mixed $context
     */
    public static function handleServerError(int $type, string $message, string $file, int $line, mixed $context) : void
	{
		$trace = debug_backtrace();
		array_shift($trace);
		array_shift($trace);
		
		switch(RUN_MODE)
		{
			case RELEASE_MODE:
				$error = self::buildErrorPage(self::$errors[$type], $message, $file, $line, $context, $trace);
				LogsManager::logError(self::$errors[$type], $message, $file, $line, $context, $trace);
				self::reportError($error);
				
				//if(in_array($type, [E_WARNING, E_NOTICE, E_DEPRECATED])) return null;

				self::stop();
				self::showInternalServerError();
				exit;
			
			case TESTING_MODE:
				$error = self::buildErrorPage(self::$errors[$type], $message, $file, $line, $context, $trace);
				LogsManager::logError(self::$errors[$type], $message, $file, $line, $context, $trace);
				self::stop();
				
				header("Status: 500 Server Error");
				echo $error;
				exit;
			
			case DEBUG_MODE:
				$error = self::buildErrorPage(self::$errors[$type], $message, $file, $line, $context, $trace);
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
        $trace = debug_backtrace();
        array_shift($trace);
        array_shift($trace);

        $context = $exception->getTrace();

        switch(RUN_MODE)
        {
            case RELEASE_MODE:
                $error = self::buildErrorPage($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $context, $trace);
                LogsManager::logError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $context, $trace);
                self::reportError($error);

                self::stop();
                self::showInternalServerError();

            case TESTING_MODE:
                $error = self::buildErrorPage($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $context, $trace);
                LogsManager::logError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $context, $trace);
                self::stop();

                header("Status: 500 Server Error");
                echo $error;
                exit;

            case DEBUG_MODE:
                $error = self::buildErrorPage($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $context, $trace);
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
     * @param mixed $context
     * @param mixed $backtrace
     * @return string
     */
    private static function buildErrorPage(string $type, string $message, string $file, int $line, mixed $context, mixed &$backtrace) : string
	{
		$stack = '';
		foreach($backtrace as $inv)
		{
			$stack .= sprintf('# %s (<b>%s</b>)<br> &nbsp; &nbsp; => ', (isset($inv['file']) ? $inv['file'] : ''), (isset($inv['line']) ? $inv['line'] : ''));
			if(isset($inv['class']))
				$stack .= sprintf('%s%s%s', 
					$inv['class'], $inv['type'], $inv['function']);
			else
				$stack .= $inv['function'];
			
			$args = json_encode($inv['args']);
			$stack .= '(' . substr($args, 1, -1) . ')<br><br>';
		}
		
		$html = '<html><head><title>Internal Server Error</title></head><body>'.
		'<h2 style="color:red">Internal Server Error</h2><p>'.
			sprintf('<b>Error</b>: %s<br><b>Message</b>: %s<br><b>File</b>: %s<br><b>Line</b>:%s<br><b>Context</b>:<pre>%s</pre><br></p>', 
					$type, $message, $file, $line, @var_export($context, true)).
		'<b>Call stack</b><br>'. $stack .
		'</body></html>';
		
		return $html;
	}

    /**
     * @param string $message
     * @throws Exception
     */
    private static function reportError(string $message) : void
	{
        if(!class_exists('EmailSender', false))
            import('Libs.Email.EmailSender');

        $sender = new EmailSender();

        $sender->Connect();
        $sender->SendMail('flpmireles@gmail.com;ymlee0919@gmail.com', 'info@cubarentalcompare.com', 'Error in CubaRentalCompare', $message);
        $sender->Close();
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