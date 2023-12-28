<?php

namespace Model\ModelName;

class Master
{

    /**
     * Internal instance
     *
     * @var Master
     */
    private static ?Master $instance = null;

    private function __construct()
    {

    }

    public static function getInstance() : Master
    {
        if(is_null(self::$instance))
            self::$instance = new Master();
        
        return self::$instance;
    }

    // User defined functions
}