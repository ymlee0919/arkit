<?PHP
//$time = microtime(true);

require 'App/Server/conf.php';
require 'App/Server/inc.php';
require 'App/Server/Core/Application.php';

$clientRequest = new Request();

$app = new App();
$app->init();
$app->dispatch($clientRequest);

//$time1 = microtime(true);
//
//$diff = bcsub( bcmul($time1, 1000, 0),  bcmul($time, 1000, 0), 0);
//$memory = memory_get_usage(true);
//echo '<br>Time: ' .$diff. ' ms<br>Memory: ' . bcdiv($memory, 1024, 2) . ' KB';