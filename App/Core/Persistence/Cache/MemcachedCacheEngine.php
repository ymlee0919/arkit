<?php

namespace Arkit\Core\Persistence\Cache;


class MemcachedCacheEngine implements CacheInterface
{
    private \Memcached $cache;

    private bool $enabled;

    private string $masterKey;

    private string $prefix;

    private int $expireTime;

    private function __construct(array &$config)
    {
        $this->masterKey = $config['master_key'] ?? 'memcachedKey';
        $this->expireTime = $config['expiry'] ?? 86400;
        $this->prefix = $config['prefix'] ?? '_memCached';

        $this->enabled = (extension_loaded('memcached'));
    }

    public function init(): bool
    {
        if (!$this->enabled) return false;

        $this->cache = new \Memcached($this->masterKey);
        $this->cache->addServer('localhost', 11211, 1);

        // Check the server is active
        $status = $this->cache->getStats();
        if (!isset($status['localhost:11211']))
            return $this->enabled = false;

        // Set options to memcached
        $this->cache->setOption(\Memcached::HAVE_IGBINARY, TRUE);
        $this->cache->setOption(\Memcached::OPT_SERIALIZER, Memcached::SERIALIZER_IGBINARY);
        $this->cache->setOption(\Memcached::OPT_PREFIX_KEY, $this->prefix);

        return true;
    }

    public function set(string $key, mixed $value, int $expire = null): bool
    {
        if (!$this->enabled) return false;
        return $this->cache->set($key, $value, (is_null($expire)) ? $this->expireTime : $expire);
    }

    public function get(string $key): mixed
    {
        if (!$this->enabled) return false;

        $data = $this->cache->get($key);

        // check for unmatched key
        if ($this->cache->getResultCode() === \Memcached::RES_NOTFOUND) {
            return null;
        }

        return $data;
    }

    public function remove(string $key): bool
    {
        if (!$this->enabled)
            return false;

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
            $error = $this->cache->getResultMessage();

        return $error;
    }

    public function isEnable(): bool
    {
        return $this->enabled;
    }
}

?>