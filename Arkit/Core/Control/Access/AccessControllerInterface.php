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
     * Access granted
     */
    const ACCESS_GRANTED = 'GRANTED';

    /**
     * Access forbidden because insufficient privileges
     */
    const ACCESS_FORBIDDEN = 'FORBIDDEN';

    /**
     * Access denied because not registered
     */
    const ACCESS_DENIED = 'DENIED';

    /**
     * Evaluate the access tu the given routing callback
     *
     * @param RoutingHandler $handler
     * @return string One of the defined constants
     */
    public function checkAccess(RoutingHandler $handler): string;
}