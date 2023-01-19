<?PHP
//$time = microtime(true);

require 'App/conf.php';
require 'App/inc.php';
require 'App/loader.php';

$loader = Loader::getInstance();
$loader->register();
$loader->addNamespace('Arkit', __DIR__ . '/App');

$clientRequest = new Arkit\Core\HTTP\Request();

$app = new Arkit\App();
$app->init();
$app->dispatch($clientRequest);

//$time1 = microtime(true);
//
//$diff = bcsub( bcmul($time1, 1000, 0),  bcmul($time, 1000, 0), 0);
//$memory = memory_get_usage(true);
//echo '<br>Time: ' .$diff. ' ms<br>Memory: ' . bcdiv($memory, 1024, 2) . ' KB';