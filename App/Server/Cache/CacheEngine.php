<?php

abstract class CacheEngine
{
	protected bool $enabled = false;
	
	public function isEnable() : bool
	{
		return $this->enabled;
	}
	
	public abstract function set(string $key, mixed $value, ?int $expire = null) : bool;
	
	public abstract function get(string $key) : mixed;
	
	public abstract function remove(string $key) : bool;
	
	public abstract function clean() : bool;
	
	public abstract function getLastError() : stdClass;
}

?>