<?php

import('AccessControlHelper', 'Helper.Access.AccessControlHelper');

/**
 * Class for control the access to the system
 */
class AdministrationAccessControl implements AccessControllerInterface
{

    private AccessControlHelper $controller;

    public function __construct()
    {
        $this->controller = new AccessControlHelper();

        // Build configuration
        $config = [
            'path' => App::fullPathFromSystem('/_config/access.yaml')
        ];

        $this->controller->init($config);

        unset($config);
    }

    /**
     * @inheritDoc
     */
    public function checkAccess(RoutingCallback $callback): string
    {
        // Check the rol: Guest
        if(!$this->controller->validateAccess($callback->getRuleId(), 'guest'))
            return self::ACCESS_FORBIDDEN;

        return self::ACCESS_GRANTED;
    }
}