<?php

namespace Arkit\Core\Persistence\Cache;


/**
 * Cache engine over Memcache
 */
class MemcacheCacheEngine implements CacheInterface
{
    /**
     * @var \Memcache
     */
    private \Memcache $cache;

    /**
     * @var bool
     */
    private bool $enabled;

    /**
     * @var string
     */
    private string $prefix;

    /**
     * @var int
     */
    private int $expireTime;

    /**
     * @inheritDoc
     */
    public function init(array &$config): bool
    {
        // Verify is enabled
        $this->enabled = (extension_loaded('memcache') || class_exists('Memcache'));

        if (!$this->enabled) return false;

        // Create and configure Memcache
        $this->cache = new \Memcache();

        $this->enabled = $this->cache->connect('127.0.0.1', 11211);
        if (!$this->enabled)
            return false;

        $this->cache->addServer('127.0.0.1', 11211, false, 1);

        // Set class configuration
        $this->expireTime = $config['expiry'] ?? 86400;
        $this->prefix = $config['prefix'] ?? '_memCache';

        return true;
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value, int $expire = null): bool
    {
        if (!$this->enabled) return false;
        $key = $this->prefix . $key;
        return $this->cache->set($key, $value, 0, (is_null($expire)) ? $this->expireTime : $expire);
    }

    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
     */
    public function remove(string $key): bool
    {
        if (!$this->enabled)
            return false;
        $key = $this->prefix . $key;
        return $this->cache->delete($key);
    }

    /**
     * @inheritDoc
     */
    public function clean(): bool
    {
        if (!$this->enabled)
            return false;
        return $this->cache->flush();
    }

    /**
     * @inheritDoc
     */
    public function getLastError(): string
    {
        if (!$this->enabled)
            $error = 'Memcached class is not defined';
        else
            $error = 'No error reported';

        return $error;
    }

    /**
     * @inheritDoc
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}

?>