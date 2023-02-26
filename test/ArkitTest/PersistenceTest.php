<?php

namespace ArkitTest;

use PHPUnit\Framework\TestCase;

class PersistenceTest extends TestCase
{
    protected \Arkit\App $app;

    public function setUp(): void
    {
        parent::setUp();

        $this->app = \Arkit\App::getInstance();
        $this->app->init();
    }

    /**
     * @covers \Arkit\Core\Persistence\Cache\FileCacheEngine
     */
    public function testFileCache() : void
    {
        // Init cache
        $fileCache = new \Arkit\Core\Persistence\Cache\FileCacheEngine();
        $config = [];
        $fileCache->init($config);

        // Prepare values
        $fieldName = 'Field';
        $value = 'Field value';

        // Store and retrieve
        $fileCache->set($fieldName, $value);
        $retrieve = $fileCache->get($fieldName);

        $this->assertSame($value, $retrieve);
    }

    /**
     * @covers \Arkit\Core\Persistence\Cache\ApcCacheEngine
     */
    public function testApcCache() : void
    {
        // Init cache
        $apcCache = new \Arkit\Core\Persistence\Cache\ApcCacheEngine();
        $config = [];
        $apcCache->init($config);
        if(!$apcCache->isEnabled())
        {
            echo 'Apc not enabled, test can not be done';
            $this->assertTrue(true);
            return;
        }

        // Prepare values
        $fieldName = 'Field';
        $value = 'Field value';

        // Store and retrieve
        $apcCache->set($fieldName, $value);
        $retrieve = $apcCache->get($fieldName);

        $this->assertSame($value, $retrieve);
    }

    /**
     * @covers \Arkit\Core\Persistence\Cache\MemcacheCacheEngine
     */
    public function testMemCache() : void
    {
        // Init cache
        $memCache = new \Arkit\Core\Persistence\Cache\MemcacheCacheEngine();
        $config = [];
        $memCache->init($config);
        if(!$memCache->isEnabled())
        {
            echo 'Memcache not enabled, test can not be done';
            $this->assertTrue(true);
            return;
        }

        // Prepare values
        $fieldName = 'Field';
        $value = 'Field value';

        // Store and retrieve
        $memCache->set($fieldName, $value);
        $retrieve = $memCache->get($fieldName);

        $this->assertSame($value, $retrieve);
    }

    /**
     * @covers \Arkit\Core\Persistence\Cache\MemcachedCacheEngine
     */
    public function testMemCached() : void
    {
        // Init cache
        $memCached = new \Arkit\Core\Persistence\Cache\MemcachedCacheEngine();
        $config = [];
        $memCached->init($config);
        if(!$memCached->isEnabled())
        {
            echo 'Apc not enabled, test can not be done';
            $this->assertTrue(true);
            return;
        }

        // Prepare values
        $fieldName = 'Field';
        $value = 'Field value';

        // Store and retrieve
        $memCached->set($fieldName, $value);
        $retrieve = $memCached->get($fieldName);

        $this->assertSame($value, $retrieve);
    }

}