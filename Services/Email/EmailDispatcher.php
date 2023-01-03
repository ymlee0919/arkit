<?php

import('PHPMailer\PHPMailer\PHPMailer', 'Libs.PHPMailer.PHPMailer');
import('PHPMailer\PHPMailer\SMTP', 'Libs.PHPMailer.SMTP');
import('PHPMailer\PHPMailer\POP3', 'Libs.PHPMailer.POP3');
import('PHPMailer\PHPMailer\Exception', 'Libs.PHPMailer.Exception');

/**
 *
 */
class EmailDispatcher
{
    /**
     * @var \PHPMailer\PHPMailer\PHPMailer|null
     */
    private ?PHPMailer\PHPMailer\PHPMailer $sender;

    /**
     * @var bool
     */
    private bool $sent;

    /**
     * @var string
     */
    private string $lastError;

    /**
     * @var array
     */
    private array $config;

    /**
     *
     */
    public function __construct()
    {
        $this->sender = null;
        $this->sent = false;
        $this->lastError = '';
    }

    /**
     * @param array $config
     * @return bool
     */
    public function connect(array &$config) : bool
    {
        $this->config = $config;

        $this->sender = new PHPMailer\PHPMailer\PHPMailer(true);

        $this->sender->SMTPDebug = 0;                                 // Enable verbose debug output
        $this->sender->isSMTP();                                      // Set mailer to use SMTP
        $this->sender->Host = $config['smtp']['server'];              // Specify main and backup SMTP servers
        $this->sender->SMTPAuth = true;                               // Enable SMTP authentication
        $this->sender->Username = $config['imap']['user'];            // SMTP username
        $this->sender->Password = $config['imap']['pass'];            // SMTP password
        $this->sender->SMTPSecure = $config['imap']['flag'];          // Enable TLS encryption, `ssl` also accepted
        $this->sender->Port = $config['smtp']['port'];

        $this->sent = false;

        return true;
    }

    /**
     * @param string $email
     * @param string|null $name
     * @return $this
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function setFrom(string $email, ?string $name = null) : self
    {
        $this->sender->setFrom($email, $name);
        return $this;
    }

    /**
     * @param string $email
     * @param string|null $name
     * @return $this
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function addDestination(string $email, ?string $name = null) : self
    {
        $this->sender->addAddress($email, $name);
        return $this;
    }

    /**
     * @param string $email
     * @param string|null $name
     * @return $this
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function addCC(string $email, ?string $name = null) : self
    {
        $this->sender->addCC($email, $name);
        return $this;
    }

    /**
     * Set the content of the message
     * @param string $subject
     * @param string $message
     * @param string|null $summary
     * @return $this
     */
    public function setMessage(string $subject, string $message, ?string $summary = null) : self
    {
        $this->sender->Subject = $subject;
        $this->sender->Body = $message;
        if(!is_null($summary))
            $this->sender->AltBody = $summary;

        return $this;
    }

    /**
     * Add and attachment
     * @param string $filePath Full file path
     * @param ?string $fileName File name inside the email
     * @return $this
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function addAttachment(string $filePath, ?string $fileName = null) : self
    {
        $this->sender->addAttachment($filePath, $fileName);
        return $this;
    }

    /**
     * Dispatch the message
     * @return bool
     */
    public function dispatch() : bool
    {
        if($this->sent)
        {
            $this->sender = null;
            $this->connect($this->config);
        }

        $this->sender->CharSet = 'UTF-8';
        $this->sender->Encoding = 'base64';
        $this->sender->isHTML(true);

        try{
            $this->sender->send();
        }catch (Exception $ex) {
            $this->lastError = $this->sender->ErrorInfo;
            return false;
        }

        $this->sent = true;
        return true;
    }

    /**
     * Release the connection
     * @return void
     */
    public function release() : void
    {
        $this->sender = null;
        $this->lastError = '';
    }

    /**
     * Get the last error reported
     *
     * @return string
     */
    public function getError() : string
    {
        return $this->lastError;
    }

}