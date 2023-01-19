<?php

namespace Arkit\Core\Monitor\Log;

use \Arkit\Core\HTTP\Request;

class FileLogsHandler implements LogsHandlerInterface
{
    /**
     * Directory to write logs
     * @var string
     */
    protected string $outputDirectory = 'resources/logs';

    /**
     * Request made
     * @var Request
     */
    protected Request $request;

    /**
     * @param $config
     */
    public function __construct(&$config)
    {
        if (isset($config['output_directory']))
            $this->outputDirectory = $config['output_directory'];
    }

    public function init(): void
    {
        // Return if directory is created
        if (is_dir(\Arkit\App::fullPath($this->outputDirectory)))
            return;

        // Create directories if not exists
        $parts = explode('/', $this->outputDirectory);
        $current = \Arkit\App::$ROOT_DIR;

        foreach ($parts as $directory) {
            if (empty($directory)) continue;
            $current .= '/' . $directory;
            if (!is_dir($current))
                mkdir($current);
        }

        $this->outputDirectory = \Arkit\App::fullPath($this->outputDirectory);
    }

    /**
     * @param string $content
     * @param string $filePath
     * @return bool
     */
    private function write(string $content, string $filePath): bool
    {
        if (!$hFile = @fopen($filePath, 'ab'))
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
    public function registerRequest(Request &$request): bool
    {
        $this->request = $request;

        $content = strtr("\n[{moment}] {method} {domain}{url} FROM: {from}\nCookies: {cookies}\nParameters: {parameters}\n", [
            '{moment}' => date('d-m-Y H:i:s', $_SERVER['REQUEST_TIME']),
            '{method}' => strtoupper($_SERVER['REQUEST_METHOD']),
            '{domain}' => $_SERVER['SERVER_NAME'],
            '{url}' => urldecode($_SERVER['REQUEST_URI']),
            '{from}' => $_SERVER['SERVER_ADDR'],
            '{cookies}' => json_encode($_COOKIE),
            '{parameters}' => json_encode($_REQUEST),
        ]);

        // Build fileName
        $filePath = $this->outputDirectory . date('Y.m.d') . '-trace.log';

        return $this->write($content, $filePath);
    }

    /**
     * {@inheritDoc}
     */
    public function registerLog(string $logType, string $message, ?array $context = null): bool
    {
        // Build the content
        $content = strtr("\n[{moment}] {logType}: {message}\n", [
            '{moment}' => date('d-m-Y H:i:s'),
            '{logType}' => $logType,
            '{message}' => $message
        ]);
        if (!is_null($context))
            foreach ($context as $key => $value)
                $content .= strtr(" {key}: {value}\n", [
                    '{key}' => $key,
                    '{value}' => is_array($value) ? json_encode($value) : $value
                ]);

        // Build fileName
        $filePath = $this->outputDirectory . date('Y.m.d') . '-logs.log';

        return $this->write($content, $filePath);
    }

    /**
     * {@inheritDoc}
     */
    public function registerError(string $errorType, string $message, string $file, int $line, mixed &$backtrace): bool
    {
        // Build call stack
        $stack = '';
        foreach ($backtrace as $inv) {
            $stack .= sprintf(' # %s, line %s :: ', ((isset($inv['file'])) ? $inv['file'] : '(NO FILE)'), ((isset($inv['line'])) ? $inv['line'] : '(NO LINE)'));
            $stack .= (isset($inv['class'])) ? $inv['class'] . $inv['type'] . $inv['function'] : $inv['function'];
            $stack .= "\n";
        }

        // Build the logs message
        $content = strtr("[{moment}] {errorType}: {message}\nRequest: {method}::{domain}{url} FROM: {from}\nFile: {file}, line {line}\n{callStack}", [
            '{moment}' => date('d-m-Y H:i:s', $_SERVER['REQUEST_TIME']),
            '{method}' => strtoupper($_SERVER['REQUEST_METHOD']),
            '{domain}' => $_SERVER['SERVER_NAME'],
            '{url}' => urldecode($_SERVER['REQUEST_URI']),
            '{from}' => $_SERVER['SERVER_ADDR'],
            '{errorType}' => $errorType,
            '{message}' => $message,
            '{file}' => $file,
            '{line}' => $line,
            '{callStack}' => $stack
        ]);
        $content .= "----------------------------------------------------------------------\n";

        $filePath = $this->outputDirectory . date('Y.m.d') . '-errors.log';
        return $this->write($content, $filePath);
    }
}