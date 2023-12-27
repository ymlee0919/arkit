<?php

namespace CMD\System\Core;

use Arkit\Core\Control\Access\AccessControllerInterface;
use Arkit\Core\Control\Routing\RoutingHandler;
use Arkit\Core\Control\Access\AccessResult;

class AccessController implements AccessControllerInterface
{

    /**
     * @inheritDoc
     */
    public function checkAccess(RoutingHandler $handler): AccessResult
    {
        return AccessResult::Granted;
    }
}