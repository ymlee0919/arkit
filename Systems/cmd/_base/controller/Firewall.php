<?php

/**
 * Class Firewall
 */
class Firewall implements AccessControllerInterface
{

    public function checkAccess(RoutingCallback $callback): string
    {
        return self::ACCESS_GRANTED;
    }
}