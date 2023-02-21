<?php

namespace ArkitTest;

use PHPUnit\Framework\TestCase;

class CryptTest extends TestCase
{
    protected \Arkit\App $app;

    protected \Arkit\Core\Security\Crypt $crypt;

    public function setUp(): void
    {
        parent::setUp();

        $this->app = new \Arkit\App();
        $this->app->init();

        $this->crypt = new \Arkit\Core\Security\Crypt();
        $config = [];
        $this->crypt->init($config);
    }

    /**
     * @covers \Arkit\Core\Security\Crypt
     */
    public function testSmoothCrypt() : void
    {
        // Pattern text
        $data = 'This is a test for Crypt::smoothEncrypt and Crypt::smoothDecrypt. Does it works fine?';

        // Encode
        $code = $this->crypt->smoothEncrypt($data);

        // Decode the result code
        $result = $this->crypt->smoothDecrypt($code);

        // Validate the pattern and the result are equals
        $this->assertSame($data, $result);
    }

    /**
     * @covers \Arkit\Core\Security\Crypt
     */
    public function testRandomString() : void
    {
        $random1 = $this->crypt->getRandomString(16);
        $random2 = $this->crypt->getRandomString(16);

        // Validate the pattern and the result are equals
        $this->assertNotEquals($random1, $random2);
    }

    /**
     * @covers \Arkit\Core\Security\Crypt
     */
    public function testStrongCrypt() : void
    {
        // Pattern text
        $data = 'This is a test for Crypt::smoothEncrypt and Crypt::smoothDecrypt. Does it works fine?';
        $key = $this->crypt->getRandomString(32);

        // Encode
        $code = $this->crypt->strongEncrypt($data, $key);

        // Decode the result code
        $result = $this->crypt->strongDecrypt($code, $key);

        // Validate the pattern and the result are equals
        $this->assertSame($data, $result);
    }

}