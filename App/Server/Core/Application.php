<?php

$dir = dirname(__FILE__);

require dirname($dir). '/Config/ConfigReader.php';

require $dir . '/Output.php';
require $dir . '/Request.php';

require dirname($dir) . '/Routing/DomainRouter.php';
require dirname($dir) . '/Routing/RouterInterface.php';
require dirname($dir) . '/Routing/Router.php';

require dirname($dir) . '/Error/ErrorHandler.php';
require dirname($dir) . '/Logs/LogsManager.php';

require dirname($dir) . '/Session/SessionManager.php';
require dirname($dir) . '/Model/Model.php';

/**
 * Class Application
 * Manage the application
 */
final class App {
	
	/**
     * Array to store global values
     * @var ?array
     */
	public static ?array $store = null;

	/**
     * Application configuration
     * @var ?array
     */
	public static ?array $config = null;
	
	/**
     * Application root directory
     * @var ?string
     */
	public static ?string $ROOT_DIR = null;

    /**
     * Request
     * @var ?Request
     */
    public static ?Request $Request = null;

    /**
     * Output
     * @var ?Output
     */
    public static ?Output $Output = null;

    /**
     * Cache manager
     * @var ?CacheInterface
     */
    public static ?CacheInterface $Cache = null;

    /**
     * Form validator
     * @var ?FormValidator
     * @static var
     */
    public static ?FormValidator $Form = null;

	/**
     * Model
     * @var ?Model
     */
    public static ?Model $Model = null;

    /**
     * Router
     * @var ?Router
     */
    public static ?Router $Router = null;

    /**
     * Logs manager
     * @var ?LogsManager
     */
    public static ?LogsManager $Logs = null;

    /**
     * Constructor of the class
     */
    public function __construct()
	{	
		// Load the configuration
		$app_path = getcwd();

        self::$ROOT_DIR = $app_path;
        self::$config = ConfigReader::ReadFile( $app_path . '/App/Config/config.yaml' );

        self::$store = [];

        unset($app_path);
	}

    /**
     *
     */
    public function __destruct()
	{
		self::$config = null;
		self::$store = null;
		self::$ROOT_DIR = null;
	}

    /**
     * @throws Exception
     */
    public function init() : void
    {
        ErrorHandler::init();

        // Set time zone
        date_default_timezone_set(self::$config['env']['time_zone']);

        // Load cache
        $cacheClass = self::$config['cache']['class'] . 'CacheHandler';
        import($cacheClass,'App.Server.Cache.' . $cacheClass);
        self::$Cache = new $cacheClass(self::$config['cache']);

        // Load output
        self::$Output = new Output();

        unset($cacheClass);
    }

    /**
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public function dispatch(Request &$request) : void
    {
        self::$Request = $request;

        // Process the request
        $request->preProcess();

        // If not valid after a primary processing, throw a wrong page
        if(!$request->isValid())
            self::$Output->throwWrongPage();

        $domainConfig = self::readConfig(self::fullPath('App/Config/routing.yaml'));
        $domainRouter = new DomainRouter($domainConfig);

        $routerPath = $domainRouter->route($request);
        if(!$routerPath)
            self::$Output->throwWrongPage();

        // Get the system
        self::$store['SYSTEM'] = explode('/', $routerPath)[0];

        // Get the router
        self::$Router = self::getRouter($routerPath);
        $routing = self::$Router->route($request->getRequestUrl(), $request->getRequestMethod());

        if(is_null($routing))
            self::$Output->throwWrongPage();

        // Store the routing result
        self::$store['ROUTING'] = $routing;

        // Execute the callback of the routing action
        $this->invoke($routing);
    }

    /**
     * @param RoutingCallback $routingCallback
     * @throws Exception
     */
    private function invoke(RoutingCallback &$routingCallback) : void
	{
        // Load the config for the package
        $configFile = self::$ROOT_DIR . '/Systems/' . self::$store['SYSTEM'] . '/_config/config.yaml';
        if(is_file($configFile))
        {
            $pkConfig = self::readConfig($configFile);
            self::$config = array_replace_recursive($pkConfig, self::$config);
            unset($pkConfig);
        }

        // Validate the request given the new configuration
        $valid = self::$Request->validate(self::$config['url']);
        if(!$valid)
            self::$Output->throwWrongPage();

        // Execute firewall first, if it is set
        if(isset(self::$config['firewall']))
        {
            import('Firewall', sprintf("Systems.%s.%s", self::$store['SYSTEM'], self::$config['firewall']));
            if(!Firewall::Process())
                die('<h1>Forbidden Access !!!!</h1>');
        }

        // Load the model
        if(isset(self::$config['model']) && isset(self::$config['model']['autoload']))
        {
            $model = self::$config['model']['autoload'];
            import($model, 'Model.' . $model . '.' . $model);

            $model = $model . '\\' . $model;
            self::$Model = $model::getInstance();
            self::$Model->load();

            unset($model);
        }

        // Load the form validator if the request is not GET
        if( 'GET' != strtoupper(self::$Request->getRequestMethod())  )
        {
            import('FormValidator','App.Server.Form.FormValidator');
            self::$Form = new FormValidator();
            self::$Request->processPost(self::$config['url']);
        }

        // Load the controller class
        $items = array();
        preg_match_all("/^([A-Za-z._-]+)\/([A-Za-z._-]+)::([A-Za-z._-]+)$/", $routingCallback->getCallback(), $items);

        // Items[1] : Include
        $include = sprintf("Systems.%s.%s", self::$store['SYSTEM'], $items[1][0]);
        // Import the file
        import($items[1][0], $include);

        // Items[2] : Class
        $class = $items[2][0];
        // Create the class
        $controller = new $class();

        // Items[3] : Method
        $method = $items[3][0];

        // Set the default directory to the output
        $tokens = explode('.', $items[1][0]);
        array_pop($tokens);array_pop($tokens);
        self::$Output->setWorkingDir(implode('/', $tokens));

        // Clean the memory before call the method
        unset($items);
        unset($include);
        unset($router);
        unset($tokens);

        // Call the methods
        $ref_method = new ReflectionMethod($class, $method);
        if($routingCallback->haveParameters())
            $ref_method->invokeArgs($controller, array_values($routingCallback->getParameters()));
        else
            $ref_method->invoke($controller);
	}

    /**
     * @param string $path
     * @return ?Router
     * @throws Exception
     */
    public static function getRouter(string &$path) : ?Router
	{
		$router = null;
        $full_path = self::$ROOT_DIR . '/Systems/' . $path;
        $md5 = md5_file($full_path);

        if(self::$Cache->isEnable())
        {
            $key = 'router.' . $path;
            $router = self::$Cache->get($key);
            if(!$router || $router->getSign() != $md5)
            {
                if(!!$router)
                {
                    unset($router);
                    $router = null;
                }

                $router = new Router();
                $rules = ConfigReader::ReadFile($full_path);
                $router->setRules($rules);
                $router->setSign($md5);

                self::$Cache->set($key, $router);
            }

            unset($key);
        }
        else
        {
            $router = new Router();
            $rules = ConfigReader::ReadFile($full_path);
            $router->setRules($rules);
        }

        unset($full_path);
        unset($md5);

        return $router;
    }

    /**
     * @param string $relPath
     * @return string
     */
    public static function fullPath(string $relPath) : string
	{
		return self::$ROOT_DIR . (('/' == $relPath[0]) ? '' : '/') . $relPath;
	}

    /**
     * @param string $relPath
     * @return string
     */
    public static function fullPathFromSystem(string $relPath) : string
    {
        return self::$ROOT_DIR . '/Systems/' . self::$store['SYSTEM'] . (('/' == $relPath[0]) ? '' : '/') . $relPath;
    }

    /**
     * @param string $path
     * @return array
     */
    public static function readConfig(string $path) : array
	{
		return ConfigReader::ReadFile($path);
	}


    /**
     * @return void
     * @throws Exception
     */
    public static function loadFormValidator() : void
	{
		if(is_null(self::$Form)){
			import('FormValidator', 'App.Server.Form.FormValidator');
			self::$Form = new FormValidator();
		}
	}

}
