<?php

namespace ArkitTest;

use PHPUnit\Framework\TestCase;

class InputValidatorTest extends TestCase
{
    protected \Arkit\App $app;

    public function setUp(): void
    {
        parent::setUp();

        $this->app = new \Arkit\App();
        $this->app->init();
    }

    /**
     * @covers \Arkit\Core\Filter\Input\CSRFHandler
     */
    public function testCSRFCode()
    {
        $formId = 'Custom-Form';

        $config = [];
        $handler = new \Arkit\Core\Filter\Input\CSRFHandler();
        $handler->init($config);

        $csrfCode = $handler->generateCode($formId);
        $success = $handler->validateCode($formId, $csrfCode);

        $this->assertSame($success, \Arkit\Core\Filter\Input\CSRFHandler::CSRF_VALIDATION_SUCCESS, 'Invalid CSRF Code');
    }

}