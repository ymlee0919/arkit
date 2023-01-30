<?php

namespace CMD\Core;

use Arkit\Core\Control\Access\AccessControllerInterface;
use Arkit\Core\Control\Routing\RoutingHandler;

class AccessController implements AccessControllerInterface
{

    /**
     * @inheritDoc
     */
    public function checkAccess(RoutingHandler $handler): string
    {
        return AccessControllerInterface::ACCESS_GRANTED;
    }
}