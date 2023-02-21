<?php

namespace ArkitTest;

use PHPUnit\Framework\TestCase;

class DotEnvTest extends TestCase
{

    /**
     * @covers Arkit\Core\Config\DotEnv
     */
    public function testDotEnv()
    {
        // Build target file
        $targetFolder = \Arkit\App::fullPath('test/ArkitTest/files');

        // Build the result
        $result = [
            'DATABASE' => 'localhost',
            'USER' => 'root',
            'PASSWORD' => '890gHwkrs*'
        ];

        $dotEnv = new \Arkit\Core\Config\DotEnv($targetFolder);
        $dotEnv->init();

        foreach($result as $key => $value)
            $this->assertSame($value, $dotEnv[$key]);
    }

}