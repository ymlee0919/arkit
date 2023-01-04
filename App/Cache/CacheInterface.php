<?php

Interface CacheInterface
{
    public function init() : bool;

    public function set(string $key, mixed $value, ?int $expire = null) : bool;

    public function get(string $key) : mixed;

    public function remove(string $key) : bool;

    public function clean() : bool;

    public function isEnable() : bool;

    public function getLastError() : string;
}

?>