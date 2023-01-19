<?php

namespace Arkit\Core\Persistence\Cache;

class FileCacheEngine implements CacheInterface
{
    private string $cacheDirectory;

    private string $prefix;

    private int $expireTime;

    public function __construct(array &$config)
    {
        $this->cacheDirectory = \Arkit\App::fullPath('/resources/cache');
        $this->prefix = $config['prefix'] ?? '_cache';
        $this->expireTime = $config['expiry'] ?? 86400;
    }

    public function init(): bool
    {
        if (!is_dir($this->cacheDirectory))
            mkdir($this->cacheDirectory);

        return true;
    }

    private function buildFileName(string $key): string
    {
        return $this->cacheDirectory . '/' . $this->prefix . '.' . md5($key) . '.cache';
    }

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

    public function remove(string $key): bool
    {
        $filename = $this->buildFileName($key);
        @unlink($filename);
        unset($filename);
        return true;
    }

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

    public function getLastError(): string
    {
        return 'File cache class is not defined';
    }

    public function isEnable(): bool
    {
        return true;
    }
}

?>