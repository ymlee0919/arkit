<?php

/**
 *
 */
class EmailLogsHandler implements LogsHandlerInterface
{
    /**
     * @var string|mixed
     */
    private string $senderAccount;

    /**
     * @var string|mixed
     */
    private string $destinationEmail;

    /**
     * @var EmailDispatcher|null
     */
    private ?EmailDispatcher $dispatcher;

    /**
     * @param array $config
     */
    public function __construct(array &$config)
    {
        $this->senderAccount    = $config['sender_account'];
        $this->destinationEmail = $config['destination_email'];
    }

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        if(import('EmailDispatcher', 'Services.Email.EmailDispatcher'))
        {
            $this->dispatcher = new EmailDispatcher();
            $this->dispatcher->setSender($this->senderAccount);
        }
    }

    /**
     * @param string $message
     * @return bool
     */
    private function send(string $message) : bool
    {
        if($this->dispatcher->connect())
        {
            $success = $this->dispatcher->send($this->destinationEmail, 'Internal Server Error - ' . $_SERVER['SERVER_NAME'], $message);
            $this->dispatcher->release();
        }
        else
            $success = false;

        return $success;
    }

    /**
     * @inheritDoc
     */
    public function registerRequest(Request &$request): bool
    {
        $content = strtr("[ {moment} ] <br> Request: [{method}] {domain}{url} <br>FROM: {from}<br>Cookies: {cookies}",[
            '{moment}'    => date('d-m-Y H:i:s', $_SERVER['REQUEST_TIME']),
            '{method}'    => strtoupper($_SERVER['REQUEST_METHOD']),
            '{domain}'    => $_SERVER['SERVER_NAME'],
            '{url}'       => urldecode($_SERVER['REQUEST_URI']),
            '{from}'      => $_SERVER['SERVER_ADDR'],
            '{cookies}'   => json_encode($_COOKIE)
        ]);

        return $this->send($content);
    }

    /**
     * @inheritDoc
     */
    public function registerLog(string $logType, string $message, ?array $context = null): bool
    {
        // Build the content
        $content = strtr("[ {moment} ]<br> {logType}: <br> &nbsp; {message}",[
            '{moment}'  =>  date('d-m-Y H:i:s'),
            '{logType}' => $logType,
            '{message}' => $message
        ]);
        if(!is_null($context))
            foreach ($context as $key => $value)
                $content .= '  ' . strval($key) . ': ' . (is_array($value)) ? json_encode($value) : strval($value) . "<br>";

        return $this->send($content);
    }

    /**
     * @inheritDoc
     */
    public function registerError(string $errorType, string $message, string $file, int $line, mixed &$backtrace): bool
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

        return $this->send($content);
    }
}