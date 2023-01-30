<?php

namespace System\Core;

use Arkit\Core\Control\Access\AccessControllerInterface;
use System\Access\AccessControl;

class Controller extends \Arkit\Core\Base\Controller
{

    /**
     * @inheritDoc
     */
    public function init(?array $config = null): void
    {
        // Start the session or make other stuff
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
    public function getAccessController() : AccessControllerInterface
    {
        // Create the access controller
        $accessController = new AccessControl();

        // Set some information

        // Return it
        return  $accessController;
    }
    /**
     * @inheritDoc
     */
    public function prepare(): void
    {
        // Load the form validator if the request is not GET
        if('GET' != strtoupper(\Arkit\App::$Request->getRequestMethod())  )
        {
            // Load the input validator
            \Arkit\App::loadInputValidator();

            // Set the custom request payload processor
            // TODO: Implement or remove the comment

            // Process the body
            \Arkit\App::$Request->processBody();
        }
    }
}