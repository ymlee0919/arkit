<?php

namespace Arkit\Core\Persistence\Cache;

/**
 * Interface for cache engine
 */
interface CacheInterface
{

    /**
     * Init the cache engine
     *
     * @param array $config Configuration
     * @return bool
     */
    public function init(array &$config): bool;

    /**
     * Set a value under a key
     * @param string $key
     * @param mixed $value
     * @param int|null $expire
     * @return bool
     */
    public function set(string $key, mixed $value, ?int $expire = null): bool;

    /**
     * Get a value under a key
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * Remove the value under a key
     * @param string $key
     * @return bool
     */
    public function remove(string $key): bool;

    /**
     * Remove all values
     * @return bool
     */
    public function clean(): bool;

    /**
     * Return if is enabled after initialization
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Return the last error occurred
     * @return string
     */
    public function getLastError(): string;
}

?>