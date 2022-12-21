<?php

class EmailDispatcher
{

    private string $sender;

    public function __construct()
    {

    }

    public function connect() : bool
    {
        return true;
    }

    public function setSender(string $senderEmail) : void
    {
        $this->sender = $senderEmail;
    }

    public function send(string $destination, string $subject, string $message, mixed $attachment = null) : bool
    {
        $hFile = fopen("mail.html","a+t");
        $header = "\nFrom: $this->sender;\n To:$destination\n; Subject:$subject;<br>\n";
        fwrite($hFile, $header);
        fwrite($hFile, $message . "\n\n");
        fflush($hFile);
        fclose($hFile);
        return true;
    }

    public function release()
    {

    }

    public function getError() : string
    {

    }

}