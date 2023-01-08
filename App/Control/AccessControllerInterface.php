<?php

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
     * @param RoutingCallback $callback
     * @return string One of the defined constants
     */
    public function checkAccess(RoutingCallback $callback) : string;
}