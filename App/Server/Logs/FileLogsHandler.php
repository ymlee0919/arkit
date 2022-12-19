<?php

class FileLogsHandler implements LogsHandlerInterface
{
    /**
     * Directory to write logs
     * @var string
     */
    protected string $outputDirectory = 'App/Logs/';

    /**
     * File to write errors
     * @var string
     */
    protected string $outputFileErrors = 'App/logs/errors.log';

    /**
     * Request made
     * @var Request
     */
    protected Request $request;

    /**
     * @param $config
     */
    public function __construct($config)
    {
        if(isset($config['logs_destination']))
            $this->outputDirectory = $config['logs_destination'];

        if(isset($config['errors_destination']))
            $this->outputDirectory = $config['errors_destination'];

        $this->outputDirectory = App::fullPath($this->outputDirectory);
        $this->outputFileErrors = App::fullPath($this->outputFileErrors);
    }

    /**
     * @param string $content
     * @param string $filePath
     * @return bool
     */
    private function write(string $content, string $filePath) : bool
    {
        if (! $hFile = @fopen($filePath, 'ab'))
            return false;

        flock($hFile, LOCK_EX);

        $result = null;

        for ($written = 0, $length = strlen($content); $written < $length; $written += $result) {
            if (($result = fwrite($hFile, substr($content, $written))) === false) {
                // if we get this far, we'll never see this during travis-ci
                // @codeCoverageIgnoreStart
                break;
            }
        }

        flock($hFile, LOCK_UN);
        fclose($hFile);

        return is_int($result);
    }

    /**
     * {@inheritDoc}
     */
    public function registerRequest(Request &$request) : bool
    {
        $this->request = $request;

    }

    /**
     * {@inheritDoc}
     */
    public function register(string $logType, string $message, ?array $context = null) : bool
    {
        // Build the content
        $content = strtr("[{moment}] {logType}: {message}\n",[
            '{moment}'  =>  date('d-m-Y H:i:s'),
            '{logType}' => $logType,
            '{message}' => $message
        ]);
        if(!is_null($context))
            foreach ($context as $key => $value)
                $content .= '  ' . strval($key) . ': ' . (is_array($value)) ? json_encode($value) : strval($value) . "\n";

        // Build fileName
        $filePath = $this->outputDirectory . 'log-' . date('Y-m-d') . '.log';

        return $this->write($content, $filePath);
    }

    /**
     * {@inheritDoc}
     */
    public function registerError(string $errorType, string $message, string $file, int $line, mixed &$backtrace) : bool
    {
        // Build call stack
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
        $content = strtr("[{moment}] {method} {domain}{url} FROM: {from}\n{errorType}: {message} reported on file {file}, line {line}\n{callStack}",[
            '{moment}'    => date('d-m-Y H:i:s', $_SERVER['REQUEST_TIME']),
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
        $content .= "----------------------------------------------------------------------\n";

        return $this->write($content, $this->outputFileErrors);
    }
}