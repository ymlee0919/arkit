<?php

require(dirname(__FILE__) . '/CacheEngine.php');

use Memcached;


class MemCache extends CacheEngine
{
	private Memcached $cache;

	private static ?MemCache $instance = NULL;

	private function __construct()
	{
        if (extension_loaded('memcached'))
		{
			$this->cache = new Memcached(App::$config['cache']['master_key']);
			$this->cache->addServer('localhost', 11211);

			// Set options to memcached
			$this->cache->setOption(Memcached::HAVE_IGBINARY, TRUE);
			$this->cache->setOption(Memcached::OPT_SERIALIZER, Memcached::SERIALIZER_IGBINARY);
			$this->cache->setOption(Memcached::OPT_PREFIX_KEY, App::$config['cache']['prefix']);

			$this->enabled = true;
		}
	}

	public static function getInstance() : MemCache
	{
		if( is_null(self::$instance) )
            self::$instance = new MemCache();

		return self::$instance;
	}


	public function set(string $key, mixed $value, int $expire = null) : bool
	{
		if(!$this->enabled) return false;
		return $this->cache->set($key, $value, (is_null($expire)) ? App::$config['cache']['expire_time'] : $expire);
	}

	public function get(string $key) : mixed
	{
		if(!$this->enabled) return false;
		return $this->cache->get($key);
	}

	public function remove(string $key) : bool
	{
		if(!$this->enabled)
            return false;
		return $this->cache->delete($key);
	}

	public function clean() : bool
	{
		if(!$this->enabled)
            return false;
		return $this->cache->flush();
	}

	public function getLastError() : stdClass
	{
		$error = new stdClass;

		if(!$this->enabled)
		{
			$error->code = 0;
			$error->message = 'Memcached class is not defined';
		}
		else
		{
			$error->code = $this->cache->getResultCode();
			$error->message = $this->cache->getResultMessage();
		}

		return $error;
	}
}

?>