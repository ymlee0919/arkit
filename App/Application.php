<?php

$dir = dirname(__FILE__);

require $dir. '/YAML/YamlReader.php';

require $dir . '/Request/Request.php';
require $dir . '/Response/Response.php';

require $dir . '/Routing/DomainRouter.php';
require $dir . '/Routing/RouterInterface.php';
require $dir . '/Routing/Router.php';

require $dir . '/Error/ErrorHandler.php';
require $dir . '/Logs/LogsManager.php';

require $dir . '/Session/Session.php';
require $dir . '/Cookie/CookieStore.php';

require $dir . '/Model/Model.php';
require $dir . '/Base/FunctionAddress.php';
require $dir . '/Control/AccessControllerInterface.php';

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
     * @var ?Response
     */
    public static ?Response $Response = null;

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
     * @var ?RouterInterface
     */
    public static ?RouterInterface $Router = null;

    /**
     * Logs manager
     * @var ?LogsManager
     */
    public static ?LogsManager $Logs = null;

    /**
     * Session manager
     * @var ?Session
     */
    public static ?Session $Session = null;

    /**
     * Constructor of the class
     */
    public function __construct()
	{	
		// Load the configuration
        self::$config = [];
        self::$store = [];
	}

    /**
     *
     */
    public function __destruct()
	{
        self::$ROOT_DIR = null;
        self::$config = null;
        self::$store = null;
	}

    /**
     * @throws Exception
     */
    public function init() : void
    {
        self::$ROOT_DIR = getcwd();

        // Init configuration
        self::$config = YamlReader::ReadFile( self::$ROOT_DIR . '/App/Config/config.yaml' );

        // Load the logs manager
        self::$Logs = new LogsManager( self::$config['logs']);
        self::$Logs->init();

        // Init the errors handler
        ErrorHandler::init();

        // Set cookies defaults
        Cookie::setDefaults([
            // Set the default domain
            'domain' => $_SERVER['SERVER_NAME'],
            // Set secure according the request
            'secure' => !empty($_SERVER['HTTPS'])
        ]);

        // Set time zone
        date_default_timezone_set(self::$config['env']['time_zone']);

        // Load cache
        $cacheClass = self::$config['cache']['handler'] . 'CacheEngine';
        import($cacheClass,'App.Cache.' . $cacheClass);
        self::$Cache = new $cacheClass(self::$config['cache']);

        // Load output
        self::$Response = new Response();

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

        self::$Logs->logRequest($request);

        // Process the request
        $request->preProcess();

        // If not valid after a primary processing, throw a wrong page
        if(!$request->isValid())
            self::$Response->throwWrongPage();

        $domainConfig = self::readConfig(self::fullPath('App/Config/routing.yaml'));
        $domainRouter = new DomainRouter($domainConfig);

        $routerPath = $domainRouter->route($request);
        if(!$routerPath)
            self::$Response->throwWrongPage();

        // Get the system
        self::$store['SYSTEM'] = explode('/', $routerPath)[0];

        // Get the router
        self::$Router = self::getRouter($routerPath);
        $routing = self::$Router->route($request->getRequestUrl(), $request->getRequestMethod());

        if(is_null($routing))
            self::$Response->throwWrongPage();

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
        $valid = self::$Request->validate(self::$config['request']);
        if(!$valid)
            self::$Response->throwWrongPage();

        // Init session and start it
        self::$Session = Session::getInstance();
        self::$Session->init(self::$config['session']);
        self::$Session->start();

        // Execute firewall first, if it is set
        if(isset(self::$config['access']))
        {
            $controllerAddress = FunctionAddress::fromString(self::$config['access']['controller']);
            if(is_null($controllerAddress))
                throw new Exception('Invalid access controller path provided');

            // Import the file
            $controllerAddress->importFrom('Systems', self::$store['SYSTEM']);

            // Create the class
            $controllerClass = $controllerAddress->getClassName();
            $accessController = new $controllerClass();

            // Check the class implements the AccessControllerInterface
            if(!$accessController instanceof AccessControllerInterface)
                throw new Exception('Invalid Access Controller Class');

            $result = $accessController->checkAccess($routingCallback);

            unset($controllerAddress);
            unset($controllerClass);

            switch ($result)
            {
                case AccessControllerInterface::ACCESS_DENIED:
                    self::$Response->throwWrongPage();
                    break;

                case AccessControllerInterface::ACCESS_FORBIDDEN:
                    self::$Response->throwForbiddenPage();
                    break;
            }
        }

        // Load the model
        if(isset(self::$config['model']) && isset(self::$config['model']['name']))
        {
            $model = self::$config['model']['name'];
            import($model, 'Model.' . $model . '.' . $model);

            $model = $model . '\\' . $model;
            self::$Model = $model::getInstance();
            if(! self::$Model instanceof Model)
                throw new Exception('Invalid model class provided');

            self::$Model->load();

            unset($model);
        }

        // Load the form validator if the request is not GET
        if( 'GET' != strtoupper(self::$Request->getRequestMethod())  )
        {
            self::loadFormValidator();
            self::$Request->processPost(self::$config['request']);
        }

        // Load the controller class
        $fnAddress = FunctionAddress::fromString($routingCallback->getCallback());

        // Build the inclusion string address
        $fnAddress->importFrom('Systems', self::$store['SYSTEM']);

        // Create the class object
        $className = $fnAddress->getClassName();
        $controller = new $className();

        // Get the method
        $method = $fnAddress->getFunctionName();

//        $items = array();
//        preg_match_all("/^([A-Za-z._-]+)\/([A-Za-z._-]+)::([A-Za-z._-]+)$/", $routingCallback->getCallback(), $items);
//
//        // Items[1] : Include
//        $include = sprintf("Systems.%s.%s", self::$store['SYSTEM'], $items[1][0]);
//        // Import the file
//        import($items[1][0], $include);
//
//        // Items[2] : Class
//        $class = $items[2][0];
//        // Create the class
//        $controller = new $class();
//
//        // Items[3] : Method
//        $method = $items[3][0];

        // Set the default directory to the output
        $tokens = explode('.', $fnAddress->getFilePart());
        array_pop($tokens);array_pop($tokens);
        self::$Response->setWorkingDir(implode('/', $tokens));

        // Clean the memory before call the method
//        unset($items);
        unset($include);
        unset($router);
        unset($tokens);

        // Call the methods
        $ref_method = new ReflectionMethod($className, $method);
        if($routingCallback->haveParameters())
            $ref_method->invokeArgs($controller, array_values($routingCallback->getParameters()));
        else
            $ref_method->invoke($controller);
	}

    /**
     * @param string $path
     * @return ?RouterInterface
     * @throws Exception
     */
    public static function getRouter(string &$path) : ?RouterInterface
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
                $rules = YamlReader::ReadFile($full_path);
                $router->setRules($rules);
                $router->setSign($md5);

                self::$Cache->set($key, $router);
            }

            unset($key);
        }
        else
        {
            $router = new Router();
            $rules = YamlReader::ReadFile($full_path);
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
		return YamlReader::ReadFile($path);
	}


    /**
     * @return void
     * @throws Exception
     */
    public static function loadFormValidator() : void
	{
		if(is_null(self::$Form)){
			import('FormValidator', 'App.Form.FormValidator');
			self::$Form = new FormValidator();
            self::$Form->init(self::$config['form']);
		}
	}

}
