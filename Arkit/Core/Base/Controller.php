<?php

namespace Arkit\Core\Base;

use Arkit\Core\Control\Access\AccessControllerInterface;

/**
 * Base class for handler request. Implements the Template Method pattern.
 *
 * This is the order for call methods:
 * 1.- init(): Initialize the handler
 * 2.- validateIncomingRequest(): Check headers and http request information
 * 3.- getAccessController(): Return an object for authorization
 * 4.- prepare(): Prepare the last details before call the requested method
 * 5.- Invoke the requested method
 * 
 * @package Arkit\Core\Base
 */
abstract class Controller
{
    /**
     * Initialize the handler
     *
     * @param array|null $config Configuration array
     * @return void
     */
    public abstract function init(?array $config = null) : void;

    /**
     * Validate the incoming request.
     * Check headers and http request information
     *
     * @return bool
     */
    public abstract function validateIncomingRequest() : bool;


    /**
     * Return and object to validate if the client can access the requested resource
     *
     * @return AccessControllerInterface
     */
    public abstract function getAccessController() : AccessControllerInterface;

    /**
     * Prepare the handler before attend the request
     *
     * @return void
     */
    public abstract function prepare() : void;
}