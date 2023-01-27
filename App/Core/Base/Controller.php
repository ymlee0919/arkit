<?php

namespace Arkit\Core\Base;

/**
 * Base class for handler request
 */
abstract class Controller
{
    /**
     * Initialize the handler
     *
     * @param array|null $config
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
     * Prepare the handler before attend the request
     *
     * @return void
     */
    public abstract function prepare() : void;
}