<?php

import('AccessControlHelper', 'Helper.Access.AccessControlHelper');

/**
 * Class for control the access to the system
 */
class _System_AccessControl implements AccessControllerInterface
{

    private AccessControlHelper $controller;

    public function __construct()
    {
        $this->controller = new AccessControlHelper();

        // Build configuration
        $config = [
            'roles_source' => App::fullPathFromSystem('/_config/roles.yaml'),
            'tasks_source' => App::fullPathFromSystem('/_config/tasks.yaml')
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
        if(!$this->controller->checkRoutingAccess($callback->getRuleId(), 'guest'))
            return self::ACCESS_FORBIDDEN;

        return self::ACCESS_GRANTED;
    }
}