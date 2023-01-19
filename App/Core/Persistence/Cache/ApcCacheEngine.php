<?php

namespace Arkit\Core\Persistence\Cache;

/**
 *
 */
class ApcCacheEngine implements CacheInterface
{
    /**
     * Indicate if cache is enabled or not
     * @var bool
     */
    private bool $isEnable;

    /**
     * Default prefix
     * @var string
     */
    private string $prefix;

    /**
     * Defaulr expired time
     * @var int
     */
    private int $expireTime;

    /**
     * Configuration for engine
     * @param array $config
     */
    public function __construct(array &$config)
    {
        $this->expireTime = $config['expiry'] ?? 86400;
        $this->prefix = $config['prefix'] ?? '_apcuCached';
    }

    /**
     * @inheritDoc
     */
    public function init(): bool
    {
        $this->isEnable = apcu_enabled();

        if (!$this->isEnable)
            return false;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value, ?int $expire = null): bool
    {
        if (!$this->isEnable)
            return false;

        $key = $this->prefix . $key;
        return apcu_store($key, $value, (is_null($expire)) ? $this->expireTime : $expire);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): mixed
    {
        if (!$this->isEnable)
            return false;

        $success = true;
        $key = $this->prefix . $key;
        $value = apcu_fetch($key, $success);
        if (!!$success)
            return $value;

        return false;
    }

    /**
     * @inheritDoc
     */
    public function remove(string $key): bool
    {
        if (!$this->isEnable)
            return false;

        $key = $this->prefix . $key;
        return apcu_delete($key);
    }

    /**
     * @inheritDoc
     */
    public function clean(): bool
    {
        if (!$this->isEnable)
            return false;

        return apcu_clear_cache();
    }

    /**
     * @inheritDoc
     */
    public function isEnable(): bool
    {
        return $this->isEnable();
    }

    /**
     * @inheritDoc
     */
    public function getLastError(): string
    {
        return 'APC is not active';
    }
}