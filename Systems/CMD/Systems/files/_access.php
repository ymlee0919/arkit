<?php

namespace SystemName\Access;
/**
 * Class for control the access to the system
 */
class AccessControl implements \Arkit\Core\Control\Access\AccessControllerInterface
{

    /**
     * @var \Arkit\Helper\Access\AccessControlHelper
     */
    private $controller;

    /**
     *
     */
    public function __construct()
    {
        $this->controller = new \Arkit\Helper\Access\AccessControlHelper();

        // Build configuration
        $config = [
            'roles_source' => \Arkit\App::fullPathFromSystem('/_config/roles.yaml'),
            'tasks_source' => \Arkit\App::fullPathFromSystem('/_config/tasks.yaml')
        ];

        $this->controller->init($config);

        unset($config);
    }

    /**
     * @inheritDoc
     */
    public function checkAccess(\Arkit\Core\Control\Routing\RoutingHandler $handler): string
    {
        // Check the rol: Guest
        if(!$this->controller->checkRoutingAccess($handler->getRuleId(), 'guest'))
            return self::ACCESS_FORBIDDEN;

        return self::ACCESS_GRANTED;
    }
}