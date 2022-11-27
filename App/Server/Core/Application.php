<?php

$dir = dirname(__FILE__);

require $dir . '/ConfigReader.php';
require $dir . '/Output.php';
require $dir . '/Request.php';
require $dir . '/Router.php';
require dirname($dir) . '/Error/ErrorHandler.php';
require dirname($dir) . '/Error/LogsManager.php';
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
     * @var ?array
     */
	public static ?array $config = null;
	
	/**
     * @var ?string
     */
	public static ?string $ROOT_DIR = null;

    /**
     * @var ?Request
     */
    public static ?Request $Request = null;

    /**
     * @var ?Output
     */
    public static ?Output $Output = null;

    /**
     * @var ?CacheEngine
     */
    public static ?CacheEngine $Cache = null;

    /**
     * @var ?FormValidator
     * @static var
     */
    public static ?FormValidator $Form = null;

	/**
     * @var ?Model
     */
    public static ?Model $Model = null;

    /**
     * @var ?Router
     */
    public static ?Router $Router = null;

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

        $cacheClass = self::$config['cache']['class'];
        import('App.Server.Cache.' . $cacheClass);
        self::$Cache = new $cacheClass();
        self::$Output = new Output();
    }

    /**
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public function dispatch(Request &$request) : void
    {
        self::$Request = $request;

        $valid = $request->process();

        if(!$valid) self::$Output->throwWrongPage();

        // Find the router by the domain
        $domain = $request->getRequestedDomain();

        if(!isset(self::$config['routing'][$domain]))
            self::$Output->throwWrongPage();

        $router = self::$config['routing'][$domain];
        if(is_array($router))
        {
            if(!$request->isEmptyUrl())
            {
                $item = $request->getUrlLevel(1);
                if(!isset($router[$item])) self::$Output->throwWrongPage();
                $router = $router[$item];
            }
            else
                $router = $router['_empty'];
        }

        // Get the package
        $tokens = explode('/', $router);
        self::$store['PACKAGE'] = $tokens[0];

        // Get the router
        self::$Router = self::getRouter($router);
        $result = self::$Router->route($request->getRequestUrl(), $request->getRequestMethod());

        if(!$result)
            self::$Output->throwWrongPage();

        // Store the routing result
        self::$store['ROUTING'] = $result;

        // Execute the callback of the routing action
        $this->invoke($result['callback'], $result['parameters']);
    }

    /**
     * @param string $callback
     * @param array $parameters
     * @throws Exception
     */
    private function invoke(string &$callback, array &$parameters) : void
	{
        // Load the config for the package
        $configFile = self::$ROOT_DIR . '/Packages/' . self::$store['PACKAGE'] . '/_config/config.yaml';
        if(is_file($configFile))
        {
            $pkConfig = self::readConfig($configFile);
            self::$config = array_replace_recursive($pkConfig, self::$config);
            unset($pkConfig);
        }

        // Execute firewall first, if it is set
        if(isset(self::$config['firewall']))
        {
            import(sprintf("Packages.%s.%s", self::$store['PACKAGE'], self::$config['firewall']));
            if(!Firewall::Process())
                die('<h1>Forbidden Access !!!!</h1>');
        }

        if(isset(self::$config['model']))
        {
            $model = self::$config['model']['autoload'];
            import('Model.' . $model . '.' . $model);

            $model = $model . '\\' . $model;
            self::$Model = $model::getInstance();
            self::$Model->load();

            unset($model);
        }

        // Load the form validator if the request is not GET
        if( 'GET' != strtoupper(self::$Request->getRequestMethod())  )
        {
            import('App.Server.Form.FormValidator');
            self::$Form = new FormValidator();
            self::$Request->processPost();
        }

        // Load the controller class
        $items = array();
        preg_match_all("/^([A-Za-z._-]+)\/([A-Za-z._-]+)::([A-Za-z._-]+)$/", $callback, $items);

        // Items[1] : Include
        $include = sprintf("Packages.%s.%s", self::$store['PACKAGE'], $items[1][0]);
        // Import the file
        import($include);

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
        if(count($parameters) > 0)
            $ref_method->invokeArgs($controller, array_values($parameters));
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
        $full_path = self::$ROOT_DIR . '/Packages/' . $path;
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
    public static function fullPathFromPackage(string $relPath) : string
    {
        return self::$ROOT_DIR . '/Packages/' . self::$store['PACKAGE'] . (('/' == $relPath[0]) ? '' : '/') . $relPath;
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
		if(null == self::$Form){
			import('App.Server.Form.FormValidator');
			self::$Form = new FormValidator();
		}
	}

}
