<?php

namespace Arkit\Core\Control\Access;

use Arkit\Core\Control\Routing\RoutingHandler;

/**
 *
 */
interface AccessControllerInterface
{

    /**
     * Access forbidden because insufficient privileges
     */
    const ACCESS_FORBIDDEN = 'FORBIDDEN';

    /**
     * Access granted
     */
    const ACCESS_GRANTED = 'GRANTED';

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