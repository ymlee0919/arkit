<?php

/**
 * Class LogsManager
 */
final class LogsManager
{

    /**
     * @param mixed $type
     * @param string $message
     * @param string $file
     * @param int $line
     * @param mixed $context
     * @param mixed $backtrace
     * @return void
     */
    public static function logError(mixed $type, string $message, string $file, int $line, mixed &$context, mixed &$backtrace) : void
	{
		$stack = '';
		foreach($backtrace as $inv)
		{
			$stack .= sprintf('# %s, line %s :: ', ((isset($inv['file'])) ? $inv['file'] : '(NO FILE)'), ((isset($inv['line'])) ? $inv['line'] : '(NO LINE)'));
			
			if(isset($inv['class']))
				$stack .= sprintf('%s%s%s', 
					$inv['class'], $inv['type'], $inv['function']) . "\n";
			else
				$stack .= $inv['function']  . "\n";
		}
		
		// Build the logs message
		$log = sprintf("[%s] %s :: %s FROM: %s\n%s: %s on file %s, line %s\n%s",
			date('d-m-Y H:i:s', $_SERVER['REQUEST_TIME']),
			$_SERVER['REQUEST_METHOD'],
			$_SERVER['REQUEST_URI'],
			$_SERVER['SERVER_ADDR'],
			$type, $message, $file, $line,
			$stack
		);
		$log .= "----------------------------------------------------------------------\n";
		
		$hFile = fopen(App::fullPath(App::$config['logs_error']), 'a+t');
		fwrite($hFile, $log);
		fflush($hFile);
		fclose($hFile);
	}
}