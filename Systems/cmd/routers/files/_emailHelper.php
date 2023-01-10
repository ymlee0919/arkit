<?php

import('EmailDispatcher', 'Services.Email.EmailDispatcher');

class emailHelper
{
    private string $message;

    private EmailDispatcher $dispatcher;

    public function __construct()
    {
        $this->dispatcher = new EmailDispatcher();

        $this->message = '';
    }

    public function buildMessage()
    {
        // TODO: Build the message
    }

    public function sendEmail() : bool
    {
        $config = App::$config['email'];
        $this->dispatcher->connect($config);

        $success = $this->dispatcher->setFrom('from@gmail.com','Company')
            ->addDestination('client@email.eml', 'ClientName')
            ->setMessage('Subject', $this->message)
            ->dispatch();

        return $success;
    }

}