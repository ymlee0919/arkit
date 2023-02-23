<?php
namespace ArkitTest\InputValidation;

use PHPUnit\Framework\TestCase;

abstract class ValidationTest extends TestCase
{
    protected \Arkit\App $app;

    protected \Arkit\Core\Filter\InputValidator $validator;

    public function setUp(): void
    {
        parent::setUp();

        $this->app = new \Arkit\App();
        $this->app->init();

        \Arkit\App::$Crypt = new \Arkit\Core\Security\Crypt();
        \Arkit\App::$Crypt->init(\Arkit\App::$config['crypt']);

        \Arkit\Core\Monitor\ErrorHandler::stop();

        \Arkit\App::loadInputValidator();
        $this->validator = \Arkit\App::$InputValidator;
    }

}