<?php

namespace CMD\Core;

use Arkit\Core\HTTP\Request\UrlEncodedParser;

class Controller extends \Arkit\Core\Base\Controller
{

    /**
     * @inheritDoc
     */
    public function init(?array $config = null): void
    {
        \Arkit\App::startSession();
    }

    /**
     * @inheritDoc
     */
    public function validateIncomingRequest(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function prepare(): void
    {
        // Load the form validator if the request is not GET
        if('GET' != strtoupper(\Arkit\App::$Request->getRequestMethod())  )
        {
            \Arkit\App::loadInputValidator();
            \Arkit\App::$Request->setBodyParser(new UrlEncodedParser());
            \Arkit\App::$Request->processBody();
        }
    }
}