<?php

namespace Arkit\Core\Control\Access;

/**
 * Enum for AccessControllerInterface::checkAccess
 * 
 * @see Arkit\Core\Control\Access\AccessControllerInterface
 */
enum AccessResult
{
    /**
     * Access granted
     */
    case Granted;

    /**
     * Access forbidden because insufficient privileges
     */
    case Forbbiden;

    /**
     * Access denied because not registered
     */
    case Denied;

}