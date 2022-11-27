<?php

require(dirname(__FILE__) . '/CacheEngine.php');

class FileCache extends CacheEngine
{
	public function __construct()
	{
		$this->enabled = true;
	}
	
	private static function buildFileName(string $key) : string
	{
		return App::fullPath('/resources/cache/') . App::$config['cache']['prefix'] . '.' . md5($key) . '.cache';
	}

	public function set(string $key, mixed $value, ?int $expire = null) : bool
	{
		$filename = self::buildFileName($key);

        $hFile = fopen($filename, 'w+');
        flock($hFile, LOCK_EX);
        fwrite($hFile, serialize($value));
        fflush($hFile);
        flock($hFile, LOCK_UN);
        fclose($hFile);

        unset($filename);
        unset($hFile);

        return true;
	}
	
	public function get(string $key) : mixed
	{
		$filename = self::buildFileName($key);
		if(!is_file($filename)) return null;
		
		// Check if the file expire
		$diff = time() - filemtime($filename);
		if($diff > App::$config['cache']['expire_time'])
		{
			$this->remove($key);
			return null;
		}

        $hFile = fopen($filename, 'r+');
        flock($hFile, LOCK_SH);
        $data = file_get_contents($filename);
        flock($hFile, LOCK_UN);
        fclose($hFile);

        unset($filename);
        unset($diff);
        unset($hFile);

		return unserialize($data);
	}
	
	public function remove(string $key) : bool
	{
		$filename = self::buildFileName($key);
        @unlink($filename);
        unset($filename);
        return true;
	}
	
	public function clean() : bool
	{
		$hDir = dir(App::fullPath('/resources/cache/'));

		while (false !== ($entry = $hDir->read())) {
		   if($entry == '.' || $entry == '..') continue;
		   unlink(App::fullPath('/resources/cache/') . $entry);
		}

		$hDir->close();

		unset($hDir);

        return true;
	}
	
	public function getLastError() : stdClass
	{
		$error = new stdClass;

		$error->code = 0;
		$error->message = 'Memcached class is not defined';		
		
		return $error;
	}
}

?>