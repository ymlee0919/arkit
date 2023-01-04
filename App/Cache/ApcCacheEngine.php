<?php

require(dirname(__FILE__) . '/CacheInterface.php');

class ApcCacheEngine implements CacheInterface
{
    private bool $isEnable;

    private string $prefix;

    private int $expireTime;

    public function __construct(array &$config)
    {
        $this->expireTime   = $config['expiry'] ?? 86400;
        $this->prefix       = $config['prefix'] ?? '_apcuCached';
    }

    public function init(): bool
    {
        $this->isEnable = apcu_enabled();

        if(!$this->isEnable)
            return false;

        return true;
    }

    public function set(string $key, mixed $value, ?int $expire = null): bool
    {
        if(!$this->isEnable)
            return false;

        $key = $this->prefix . $key;
        return apcu_store($key, $value, (is_null($expire)) ? $this->expireTime : $expire);
    }

    public function get(string $key): mixed
    {
        if(!$this->isEnable)
            return false;

        $success = true;
        $key = $this->prefix . $key;
        $value = apcu_fetch($key, $success);
        if(!!$success)
            return $value;

        return false;
    }

    public function remove(string $key): bool
    {
        if(!$this->isEnable)
            return false;

        $key = $this->prefix . $key;
        return apcu_delete($key);
    }

    public function clean(): bool
    {
        if(!$this->isEnable)
            return false;

        return apcu_clear_cache();
    }

    public function isEnable(): bool
    {
        return $this->isEnable();
    }

    public function getLastError(): string
    {
        return 'APC is not active';
    }
}