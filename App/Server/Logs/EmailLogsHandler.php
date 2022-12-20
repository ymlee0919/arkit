<?php

class EmailLogsHandler implements LogsHandlerInterface
{
    private string $senderInbox;

    private string $destinationEmail;

    private ?EmailDispatcher $dispatcher;

    public function __construct(array &$config)
    {
        $this->senderInbox      = $config['sender'];
        $this->destinationEmail = $config['responsible_email'];

        if(import('EmailDispatcher', 'Services.Email.EmailDispatcher'))
        {
            $this->dispatcher = new EmailDispatcher();
            $this->dispatcher->setSender($this->senderInbox);
        }
    }

    private function send(string $message)
    {
        $this->dispatcher->connect();
        $this->dispatcher->send($this->destinationEmail, 'Internal Server Error - ' . $_SERVER['SERVER_NAME'], $message);
        $this->dispatcher->release();
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

        $this->send($content);
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

        $this->send($content);
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

        $this->send($content);
    }
}