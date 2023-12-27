<?PHP
//$time = microtime(true);

require 'Arkit/conf.php';
require 'Arkit/inc.php';
require 'Arkit/loader.php';

$loader = Loader::getInstance();
$loader->register();
$loader->addNamespace('Arkit', __DIR__ . '/Arkit');

$clientRequest = new Arkit\Core\HTTP\Request();

$app = \Arkit\App::getInstance();
$app->init();
$app->dispatch($clientRequest);

//$time1 = microtime(true);
//
//$diff = bcsub( bcmul($time1, 1000, 0),  bcmul($time, 1000, 0), 0);
//$memory = memory_get_usage(true);
//echo '<br>Time: ' .$diff. ' ms<br>Memory: ' . bcdiv($memory, 1024, 2) . ' KB';