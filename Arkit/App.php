<?php

namespace Arkit;

use Arkit\Core\Config\DotEnv;

/**
 * Class Application
 * Manage the application
 */
final class App
{
    
    /**
     * Array to store global values
     *
     * @var ?array
     */
    public static ?array $store = null;

    /**
     * Application configuration
     *
     * @var ?array
     */
    public static ?array $config = null;
    
    /**
     * Application root directory
     *
     * @var ?string
     */
    public static ?string $ROOT_DIR = null;

    /**
     * Request
     *
     * @var ?Core\HTTP\RequestInterface
     */
    public static $Request = null;

    /**
     * Output
     *
     * @var ?Core\HTTP\Response
     */
    public static  $Response = null;

    /**
     * Cache manager
     *
     * @var ?Core\Persistence\Cache\CacheInterface
     */
    public static $Cache = null;

    /**
     * Form validator
     *
     * @var ?Core\Filter\InputValidator
     * @static var
     */
    public static $InputValidator = null;

    /**
     * Model
     *
     * @var ?Core\Persistence\Database\Model
     */
    public static $Model = null;

    /**
     * Router
     *
     * @var ?Core\Control\Routing\RouterInterface
     */
    public static $Router = null;

    /**
     * Logs manager
     *
     * @var ?Core\Monitor\Logger
     */
    public static $Logs = null;

    /**
     * Session manager
     *
     * @var ?Core\Persistence\Server\Session
     */
    public static $Session = null;

    /**
     * Crypt manager
     *
     * @var ?Core\Security\Crypt\CryptInterface
     */
    public static $Crypt = null;


    /**
     * Environment vars manager
     *
     * @var ?Core\Config\DotEnv
     */
    public static $Env = null;

    /**
     * Static instance
     *
     * @var ?App
     */
    private static $instance = null;

    private function __construct()
    {

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

    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new App();
            self::$store = [];
        }

        return self::$instance;
    }

    /**
     * @throws \Exception
     */
    public function init() : void
    {
        self::$ROOT_DIR = clean_file_address( dirname(__FILE__, 2) );

        // Init configuration
        self::$config = Core\Config\YamlReader::ReadFile(self::$ROOT_DIR . '/App/Config/config.yaml');

        // Read environment vars
        self::$Env = new DotEnv(self::$ROOT_DIR . '/App/Config');
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

    private static function initRunMode()
    {
        if(!defined('RUN_MODE'))
            define('RUN_MODE', self::$Env['RUN_MODE'] ?? RELEASE_MODE);

        switch(RUN_MODE)
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
     * @param  Core\HTTP\RequestInterface $request
     * @return void
     * @throws \Exception
     */
    public function dispatch(Core\HTTP\RequestInterface &$request) : void
    {
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
        $domainConfig = self::readConfig(self::fullPath('App/Config/routing.yaml'));
        $domainRouter = new Core\Control\Routing\DomainRouter($domainConfig);
        $routerPath = $domainRouter->route($request);
        if(!$routerPath)
            self::$Response->throwInvalidRequest();

        // Get the system
        self::$store['SYSTEM'] = explode('/', $routerPath)[0];

        // Load the system configuration
        $configFile = self::$ROOT_DIR . '/Systems/' . self::$store['SYSTEM'] . '/_config/config.yaml';
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
            case Core\Control\Access\AccessControllerInterface::ACCESS_DENIED:
                self::$Response->throwAccessDenied();
                break;

            case Core\Control\Access\AccessControllerInterface::ACCESS_FORBIDDEN:
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
     * @param  string $path
     * @return ?Core\Control\Routing\RouterInterface
     * @throws \Exception
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
     * @return void
     * @throws \Exception
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
     * Start the session
     * @return void
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
     * Load the model
     * @param string|null $modelName
     * @return void
     * @throws \Exception
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
     * @param  string $path
     * @return array
     */
    public static function readConfig(string $path) : array
    {
        return Core\Config\YamlReader::ReadFile($path);
    }

    /**
     * @param  string $relPath
     * @return string
     */
    public static function fullPath(string $relPath) : string
    {
        return clean_file_address(self::$ROOT_DIR . DIRECTORY_SEPARATOR . $relPath);
    }

    /**
     * @param  string $relPath
     * @return string
     */
    public static function fullPathFromSystem(string $relPath) : string
    {
        return clean_file_address(self::$ROOT_DIR . DIRECTORY_SEPARATOR . 'Systems'. DIRECTORY_SEPARATOR . self::$store['SYSTEM'] . DIRECTORY_SEPARATOR . $relPath);
    }
}
