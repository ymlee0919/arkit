<?php
namespace Arkit;

use Arkit\Core\Config\DotEnv;

/**
 * Application controller class. Implements the singleton pattern.
 */
final class App
{
    
    /**
     * Array to store global values. It can be used to share values between objects.
     *
     * @var ?array
     * @example \Arkit\App::$store['info'] = '...'
     * @api
     */
    public static ?array $store = null;

    /**
     * Store the global application configuration
     *
     * @var ?array
     * @api
     */
    public static ?array $config = null;
    
    /**
     * Application root directory
     *
     * @var ?string
     * @api
     */
    public static ?string $ROOT_DIR = null;

    /**
     * Request handler
     *
     * @var ?Core\HTTP\RequestInterface
     * @api
     */
    public static $Request = null;

    /**
     * Response handler
     *
     * @var ?Core\HTTP\Response
     * @api
     */
    public static $Response = null;

    /**
     * Cache handler
     *
     * @var ?Core\Persistence\Cache\CacheInterface
     * @api
     */
    public static $Cache = null;

    /**
     * Form input validator
     *
     * @var ?Core\Filter\InputValidator
     * @api
     */
    public static $InputValidator = null;

    /**
     * Business model class
     *
     * @var ?Core\Persistence\Database\Model
     * @api
     */
    public static $Model = null;

    /**
     * Router for url
     *
     * @var ?Core\Control\Routing\RouterInterface
     * @api
     */
    public static $Router = null;

    /**
     * Logs handler
     *
     * @var ?Core\Monitor\Logger
     * @api
     */
    public static $Logs = null;

    /**
     * Session vars handler
     *
     * @var ?Core\Persistence\Server\Session
     * @api
     */
    public static $Session = null;

    /**
     * Cryptography algorithms provider
     *
     * @var ?Core\Security\Crypt\CryptInterface
     * @api
     */
    public static $Crypt = null;


    /**
     * Environment vars handler
     *
     * @var ?Core\Config\DotEnv
     * @see \Arkit\Core\Config\DotEnv
     * @api
     */
    public static $Env = null;

    /**
     * Singleton instance of the class
     *
     * @var ?App
     */
    private static $instance = null;

    private function __construct() { }

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
     * Return the unique instance of the class
     *
     * @return \Arkit\App
     * @api
     */
    public static function getInstance() : \Arkit\App
    {
        if(is_null(self::$instance))
        {
            self::$instance = new App();
            self::$store = [];
        }

        return self::$instance;
    }

    /**
     * Init the application
     * 
     * @throws \Exception
     */
    public function init() : void
    {
        self::$ROOT_DIR = clean_file_address( dirname(__FILE__, 2) );

        // Init configuration
        self::$config = Core\Config\YamlReader::ReadFile(self::$ROOT_DIR . '/Arkit/Config/config.yaml');

        // Read environment vars
        self::$Env = new DotEnv(self::$ROOT_DIR . '/Arkit/Config');
        self::$Env->init();
        self::initRunMode();

        // Load the logs manager
        self::$Logs = new Core\Monitor\Logger(self::$config['logs']);
        self::$Logs->init();

        // Init the errors handler
        Core\Monitor\ErrorHandler::init();

        // Set time zone
        date_default_timezone_set(self::$config['env']['time_zone']);

        // Load cache
        $cacheClass = '\\Arkit\\Core\\Persistence\\Cache\\' . self::$config['cache']['handler'] . 'CacheEngine';
        self::$Cache = new $cacheClass();
        self::$Cache->init(self::$config['cache']);

        // Load output
        self::$Response = new Core\HTTP\Response();

        unset($cacheClass);
    }

    /**
     * Init application according defined run mode
     *
     * @return void
     */
    private static function initRunMode()
    {
        switch(self::$Env['RUN_MODE'])
        {
            case RELEASE_MODE:
                error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

                ini_set('display_errors', 'Off');
                ini_set('display_startup_errors', '0');
                ini_set('output_buffering', '4096');
                ini_set('implicit_flush', 'Off');

                if(ini_get('opcache.enable'))
                {
                    ini_set('opcache.memory_consumption',128);
                    ini_set('opcache.interned_strings_buffer', 16);
                    ini_set('opcache.max_accelerated_files',7963);
                    ini_set('opcache.revalidate_freq', 3600);
                    ini_set('opcache.fast_shutdown', 1);
                    ini_set('opcache.max_wasted_percentage', 15);
                    ini_set('opcache.enable_cli',false);
                    ini_set('opcache.use_cwd',true);

                    ini_set('opcache.jit','tracing');
                    ini_set('opcache.jit_buffer_size','64M');
                    ini_set('opcache.jit_max_recursive_calls','7');
                }
                break; // END OF RELEASE_MODE

            case TESTING_MODE:
                error_reporting(-1);
                ini_set('display_errors', 'Off');
                ini_set('display_startup_errors', '1');
                ini_set('output_buffering', '4096');
                ini_set('implicit_flush', 'Off');
                ini_set('opcache.enable',0);
                break;

            case DEBUG_MODE:
                error_reporting(-1);
                ini_set('display_errors', 'On');
                ini_set('output_buffering', 'Off');
                ini_set('implicit_flush', 'On');
                ini_set('opcache.enable',0);
                opcache_reset();
                break;
        }
    }

    /**
     * Dispatch a giver request. This method in invoqued automatically by index.php
     * 
     * @param  Core\HTTP\RequestInterface $request
     * @return void
     * @throws \Exception
     */
    public function dispatch(Core\HTTP\RequestInterface &$request) : void
    {
        // If implements static resources, try to send response from cache
        if($request->getRequestMethod() == 'GET')
            if(\Arkit\Core\Persistence\Statics\StaticContent::getInstance()->cacheToOutput())
                return;

        // Load dependencies
        \Loader::getInstance()->loadDependencies();

        // Set the request
        self::$Request = $request;

        // Log the request
        self::$Logs->logRequest($request);

        // Set cookies defaults
        Core\Persistence\Client\Cookie::setDefaults(
            [
                // Set the default domain
                'domain' => $_SERVER['SERVER_NAME'],
                // Set secure according the request
                'secure' => $request->isSecure()
            ]
        );

        // Route by domain
        $domainConfig = self::readConfig(self::fullPath('Arkit/Config/routing.yaml'));
        $domainRouter = new Core\Control\Routing\DomainRouter($domainConfig);
        $routerPath = $domainRouter->route($request);
        if(!$routerPath)
            self::$Response->throwInvalidRequest();

        // Get the system
        self::$store['SYSTEM'] = explode('/', $routerPath)[0];

        // Load the system configuration
        $configFile = self::$ROOT_DIR . '/Systems/' . self::$store['SYSTEM'] . '/Config/config.yaml';
        if(is_file($configFile))
        {
            $pkConfig = self::readConfig($configFile);
            self::$config = array_replace_recursive($pkConfig, self::$config);
            unset($pkConfig);
        }
        unset($configFile);

        // Init the response
        if(isset(self::$config['response']))
            self::$Response->init(self::$config['response']);

        // Init the request
        if(isset(self::$config['request']))
            self::$Request->init(self::$config['request']);

        // Load the crypt
        if(isset(self::$config['crypt']) && is_array(self::$config['crypt']))
        {
            self::$Crypt = new Core\Security\Crypt();
            self::$Crypt->init(self::$config['crypt']);
        }

        // Validate the request
        $valid = self::$Request->validate();
        if(!$valid)
            self::$Response->throwNotFound();

        // Get the router
        self::$Router = self::getRouter($routerPath);
        $routing = self::$Router->route($request->getRequestUrl(), $request->getRequestMethod());

        if(is_null($routing))
            self::$Response->throwNotFound();

        // Store the routing result
        self::$store['ROUTING'] = $routing;

        // Execute the callback of the routing action
        $this->invoke($routing);
    }

    /**
     * Invoque a function of routing handler
     * 
     * @param  Core\Control\Routing\RoutingHandler $routingHandler
     * @throws \Exception
     */
    private function invoke(Core\Control\Routing\RoutingHandler &$routingHandler) : void
    {
        // Load the handler class
        $fnAddress = Core\Base\FunctionAddress::fromString($routingHandler->getHandler());

        // Register the namespace
        \Loader::getInstance()->addNamespace(self::$store['SYSTEM'], self::fullPathFromSystem('/'));

        // Create the class object
        $className = $fnAddress->getClassName();
        $controller = new $className();

        // Validate is a controller class
        if(!$controller instanceof Core\Base\Controller)
            throw new \Exception('Controller class of handler must extends from Core\\Base\\Controller');

        // Initialize
        $controller->init();

        // Validate the incoming request
        if(!$controller->validateIncomingRequest())
            self::$Response->throwAccessDenied();

        // Check access to the method
        $accessController = $controller->getAccessController();
        $result = $accessController->checkAccess($routingHandler);

        switch ($result)
        {
            case Core\Control\Access\AccessResult::Denied:
                self::$Response->throwAccessDenied();
                break;

            case Core\Control\Access\AccessResult::Forbbiden:
                self::$Response->throwForbiddenAccess();
                break;
        }

        // Prepare before invoke the handler function
        $controller->prepare();

        // Get the method
        $method = $fnAddress->getFunctionName();

        // Clean the memory before call the method
        // unset($items);
        unset($include);
        unset($router);
        unset($tokens);

        // Store the controller name result
        self::$store['CONTROLLER'] = $className;

        // Call the methods
        $ref_method = new \ReflectionMethod($className, $method);
        if($routingHandler->haveParameters())
            $ref_method->invokeArgs($controller, array_values($routingHandler->getParameters()));
        else
            $ref_method->invoke($controller);
    }

    /**
     * Return the Url Router given the path
     * 
     * @param string $path Absolute path to the file router definition
     * @return ?Core\Control\Routing\RouterInterface Url router
     * @throws \Exception
     * @api
     */
    public static function getRouter(string &$path) : ?Core\Control\Routing\RouterInterface
    {
        $full_path = self::$ROOT_DIR . '/Systems/' . $path;
        $md5 = md5_file($full_path);

        if(self::$Cache->isEnabled())
        {
            $key = 'router.' . $path;
            $router = self::$Cache->get($key);
            if(!$router || $router->getSign() != $md5)
            {
                if(!!$router) {
                    unset($router);
                    $router = null;
                }

                $router = new Core\Control\Routing\Router();
                $rules = Core\Config\YamlReader::ReadFile($full_path);
                $router->setRules($rules);
                $router->setSign($md5);

                self::$Cache->set($key, $router);
            }

            unset($key);
        }
        else
        {
            $router = new Core\Control\Routing\Router();
            $rules = Core\Config\YamlReader::ReadFile($full_path);
            $router->setRules($rules);
        }

        unset($full_path);
        unset($md5);

        return $router;
    }

    /**
     * Load the form input validator. 
     * 
     * @return void
     * @throws \Exception
     * @api
     */
    public static function loadInputValidator() : void
    {
        if(is_null(self::$InputValidator))
        {
            self::$InputValidator = new Core\Filter\InputValidator();
            self::$InputValidator->init(self::$config['validation']);
        }
    }

    /**
     * Start the session handler. It must be called before use any session var.
     * 
     * @return void
     * @api
     */
    public static function startSession() : void
    {
        // Init session and start it
        if(is_null(self::$Session))
        {
            self::$Session = Core\Persistence\Server\Session::getInstance();
            self::$Session->init(self::$config['session']);
            self::$Session->start();
        }
    }

    /**
     * Load the model to work with.
     * 
     * @param string|null $modelName Name of the model to be loaded. If not set, the model will be taken form the configuration
     * @return void
     * @throws \Exception
     * @api
     */
    public static function loadModel(?string $modelName) : void
    {
        $model = $modelName ?? self::$config['model']['name'];
        $modelClassName = 'Model\\' . $model . '\\' . $model;

        \Loader::getInstance()->addNamespace('Model\\' . $model, self::fullPath('Model\\' . $model));

        if(!class_exists($modelClassName))
            throw new \Exception('The Model class "' . $model . '" is not defined');

        if(!method_exists($modelClassName, 'getInstance'))
            throw new \Exception('The Model class "' . $model . '" must be a Singleton class with getInstance method');

        self::$Model = $modelClassName::getInstance();
        if(! self::$Model instanceof Core\Persistence\Database\Model)
            throw new \Exception('Invalid model class provided');

        unset($model);
        unset($modelClassName);
    }

    /**
     * Read a yaml file from an absolute path
     * 
     * @param string $path Absolute path of the file
     * @return array Configuration
     * @api
     */
    public static function readConfig(string $path) : array
    {
        return Core\Config\YamlReader::ReadFile($path);
    }

    /**
     * Return absolute path form a relative path. Relative path is taken form the current working directory
     * @param  string $relPath Relative path
     * @return string Absolute path form a relative path
     * @api
     */
    public static function fullPath(string $relPath) : string
    {
        return clean_file_address(self::$ROOT_DIR . DIRECTORY_SEPARATOR . $relPath);
    }

    /**
     * Return absolute path from a relative path inside the active System directory.
     * 
     * @param string $relPath Relative path
     * @return string Absolute path from a relative path inside the active System directory
     * @api
     */
    public static function fullPathFromSystem(string $relPath) : string
    {
        return clean_file_address(self::$ROOT_DIR . DIRECTORY_SEPARATOR . 'Systems'. DIRECTORY_SEPARATOR . self::$store['SYSTEM'] . DIRECTORY_SEPARATOR . $relPath);
    }
}
