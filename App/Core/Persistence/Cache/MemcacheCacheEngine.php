<?php

namespace Arkit\Core\Persistence\Cache;


class MemcacheCacheEngine implements CacheInterface
{
    private \Memcache $cache;

    private bool $enabled;

    private string $prefix;

    private int $expireTime;

    private function __construct(array &$config)
    {
        $this->expireTime = $config['expiry'] ?? 86400;
        $this->prefix = $config['prefix'] ?? '_memCache';

        $this->enabled = (extension_loaded('memcache'));
    }

    public function init(): bool
    {
        if (!$this->enabled) return false;

        $this->cache = new \Memcache();

        $this->enabled = $this->cache->connect('localhost', 11211);
        if (!$this->enabled)
            return false;

        $this->cache->addServer('localhost', 11221, true, 1);
    }

    public function set(string $key, mixed $value, int $expire = null): bool
    {
        if (!$this->enabled) return false;
        $key = $this->prefix . $key;
        return $this->cache->set($key, $value, 0, (is_null($expire)) ? $this->expireTime : $expire);
    }

    public function get(string $key): mixed
    {
        if (!$this->enabled) return false;

        $flags = false;
        $key = $this->prefix . $key;
        $data = $this->cache->get($key, $flags);

        // check for unmatched key (i.e. $flags is untouched)
        if ($flags === false) {
            return null;
        }

        return $data;
    }

    public function remove(string $key): bool
    {
        if (!$this->enabled)
            return false;
        $key = $this->prefix . $key;
        return $this->cache->delete($key);
    }

    public function clean(): bool
    {
        if (!$this->enabled)
            return false;
        return $this->cache->flush();
    }

    public function getLastError(): string
    {
        if (!$this->enabled)
            $error = 'Memcached class is not defined';
        else
            $error = 'No error reported';

        return $error;
    }

    public function isEnable(): bool
    {
        return $this->enabled;
    }
}

?>