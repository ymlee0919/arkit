<?php

/**
 * Class Model
 */
abstract class Model
{
    public abstract function beginTransaction() : void;

    public abstract function commit() : void;

    public abstract function load() : void;

    public abstract function rollback() : void;

    public abstract function loadClass($className) : void;

    public abstract function release() : void;
}