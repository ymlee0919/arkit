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

        $this->app = \Arkit\App::getInstance();
        $this->app->init();
        
        \Loader::getInstance()->loadDependencies();

        \Arkit\App::$Crypt = new \Arkit\Core\Security\Crypt();
        \Arkit\App::$Crypt->init(\Arkit\App::$config['crypt']);

        \Arkit\App::loadInputValidator();
        $this->validator = \Arkit\App::$InputValidator;
    }

}