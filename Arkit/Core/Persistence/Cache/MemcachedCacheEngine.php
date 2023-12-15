<?php

namespace Arkit\Core\Persistence\Cache;


/**
 * Cache Engine over Memcached
 */
class MemcachedCacheEngine implements CacheInterface
{
    /**
     * @var \Memcached
     */
    private \Memcached $cache;

    /**
     * @var bool
     */
    private bool $enabled;

    /**
     * @var string
     */
    private string $masterKey;

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
        $this->enabled = (extension_loaded('memcached') || class_exists('Memcached'));

        if (!$this->enabled) return false;

        // Set class configuration
        $this->masterKey = $config['master_key'] ?? 'memcachedKey';
        $this->expireTime = $config['expiry'] ?? 86400;
        $this->prefix = $config['prefix'] ?? '_memCached';

        // Create and configure Memcached
        $this->cache = new \Memcached($this->masterKey);
        $this->cache->addServer('localhost', 11211, 1);

        // Check the server is active
        $status = $this->cache->getStats();
        if (!isset($status['localhost:11211']))
            return $this->enabled = false;

        // Set options to memcached
        $this->cache->setOption(\Memcached::HAVE_IGBINARY, TRUE);
        //$this->cache->setOption(\Memcached::OPT_SERIALIZER, \Memcached::SERIALIZER_IGBINARY);
        $this->cache->setOption(\Memcached::OPT_PREFIX_KEY, $this->prefix);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value, int $expire = null): bool
    {
        if (!$this->enabled) return false;
        return $this->cache->set($key, $value, (is_null($expire)) ? $this->expireTime : $expire);
    }

    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
     */
    public function remove(string $key): bool
    {
        if (!$this->enabled)
            return false;

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
            $error = $this->cache->getResultMessage();

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