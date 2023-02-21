<?php

// Include the PHP Unit autoload
require __DIR__ . '/PHPUnit/vendor/autoload.php';

// Include the Arkit autoload
require dirname(__DIR__ ) . '/App/conf.php';
require dirname(__DIR__ ) . '/App/inc.php';
require dirname(__DIR__ ) . '/App/loader.php';

$loader = Loader::getInstance();
$loader->register();
$loader->addNamespace('Arkit', dirname(__DIR__) . '/App/');
$loader->addNamespace('ArkitTest', dirname(__DIR__) . '/test/ArkitTest/');