<?php

namespace TheNameSpace;

use Arkit\Services\Email\EmailDispatcher;

/**
 * Helper class for sending email.
 * Allow to separate the request processing for email creating and dispatching
 */
class emailHelper
{
    /**
     * Custom message
     * @var string
     */
    private string $message;

    /**
     * Email dispatcher
     * @var EmailDispatcher
     */
    private EmailDispatcher $dispatcher;

    /**
     *
     */
    public function __construct()
    {
        $this->dispatcher = new EmailDispatcher();

        $this->message = '';
    }

    /**
     * Build the custom message
     * @return void
     */
    public function buildMessage()
    {
        // TODO: Build the message
    }

    /**
     * Send the email
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function sendEmail() : bool
    {
        $config = \Arkit\App::$config['email'];
        $this->dispatcher->connect($config);

        $success = $this->dispatcher->setFrom('from@gmail.com','Company')
            ->addDestination('client@email.eml', 'ClientName')
            ->setMessage('Subject', $this->message)
            ->dispatch();

        return $success;
    }

    /**
     * Retrieve the internal dispatcher
     * @return EmailDispatcher
     */
    public function getDispatcher() : EmailDispatcher
    {
        return $this->dispatcher;
    }

}