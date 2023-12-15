<?php

namespace Arkit\Core\Control\Access;

use Arkit\Core\Control\Routing\RoutingHandler;

/**
 * Interface for Access Controller clases.
 * All classes that handle access control must implement this interface.
 */
interface AccessControllerInterface
{

    /**
     * Evaluate the access of the given routing handler
     *
     * @param RoutingHandler $handler
     * @return AccessResult Result access checking
     */
    public function checkAccess(RoutingHandler $handler): AccessResult;
}