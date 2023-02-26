<?php

namespace ArkitTest;

use PHPUnit\Framework\TestCase;

class AppBasicsTest extends TestCase
{
    protected \Arkit\App $app;

    public function setUp(): void
    {
        parent::setUp();

        $this->app = \Arkit\App::getInstance();
        $this->app->init();
    }

    /**
     * @covers \Arkit\App
     */
    public function testFullPath()
    {
        // Build full file size
        $sourceFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'config.yaml';

        // Build target file
        $targetFile = \Arkit\App::fullPath('test/ArkitTest/files/config.yaml');

        $this->assertSame($sourceFile, $targetFile);
    }

    /**
     * @covers \Arkit\App
     */
    public function testReadConfig()
    {
        // Build target file
        $targetFile = \Arkit\App::fullPath('test/ArkitTest/files/config.yaml');

        // Build the result
        $result = [
            'config' => [
                'var1' => 'Value1'
            ]
        ];

        $config = \Arkit\App::readConfig($targetFile);

        $this->assertSame($config, $result);
    }

}