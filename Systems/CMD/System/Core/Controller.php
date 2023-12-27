<?php

namespace CMD\System\Core;

use Arkit\Core\Control\Access\AccessControllerInterface;
use Arkit\Core\HTTP\Request\UrlEncodedParser;

class Controller extends \Arkit\Core\Base\Controller
{
    protected ?string $baseTpl;

    /**
     * @inheritDoc
     */
    public function init(?array $config = null): void
    {
        \Arkit\App::startSession();

        $this->baseTpl = \Arkit\App::fullPathFromSystem('/_base/view/base.tpl');
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
            \Arkit\App::$Request->processPayload();
        }
    }

    /**
     * @inheritDoc
     */
    public function getAccessController(): AccessControllerInterface
    {
        return new \CMD\System\Core\AccessController();
    }
}