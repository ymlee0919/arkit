<?php

/**
 * Class Firewall
 */
class Firewall {

    /**
     * @return bool
     */
    public static function Process()
    {
        // Start the session
        Session::start();

        return true;
    }
}