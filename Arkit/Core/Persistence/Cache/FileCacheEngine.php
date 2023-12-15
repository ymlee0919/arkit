<?php

namespace Arkit\Core\Persistence\Cache;

/**
 * Cache engine over file
 */
class FileCacheEngine implements CacheInterface
{
    /**
     * @var string
     */
    private string $cacheDirectory;

    /**
     * @var string
     */
    private string $prefix;

    /**
     * @var int
     */
    private int $expireTime;

    /**
     * Constructor of the class
     */
    public function __construct()
    {
        $this->cacheDirectory = \Arkit\App::fullPath('/resources/cache');

    }

    /**
     * @inheritDoc
     */
    public function init(array &$config): bool
    {
        $this->prefix = $config['prefix'] ?? '_cache';
        $this->expireTime = $config['expiry'] ?? 86400;

        if (!is_dir($this->cacheDirectory))
            mkdir($this->cacheDirectory);

        return true;
    }


    /**
     * @param string $key
     * @return string
     */
    private function buildFileName(string $key): string
    {
        return $this->cacheDirectory . '/' . $this->prefix . '.' . md5($key) . '.cache';
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value, ?int $expire = null): bool
    {
        $filename = $this->buildFileName($key);

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

    /**
     * @inheritDoc
     */
    public function get(string $key): mixed
    {
        $filename = $this->buildFileName($key);
        if (!is_file($filename)) return null;

        // Check if the file expire
        $diff = time() - filemtime($filename);
        if ($diff > $this->expireTime) {
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

    /**
     * @inheritDoc
     */
    public function remove(string $key): bool
    {
        $filename = $this->buildFileName($key);
        @unlink($filename);
        unset($filename);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function clean(): bool
    {
        $hDir = dir($this->cacheDirectory);

        while (false !== ($entry = $hDir->read())) {
            if ($entry == '.' || $entry == '..') continue;
            unlink($this->cacheDirectory . $entry);
        }

        $hDir->close();

        unset($hDir);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getLastError(): string
    {
        return 'File cache class is not defined';
    }

    /**
     * @inheritDoc
     */
    public function isEnabled(): bool
    {
        return true;
    }
}

?>