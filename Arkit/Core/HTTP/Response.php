<?php

namespace Arkit\Core\HTTP;

use Arkit\Core\Base\FunctionAddress;
use Arkit\Core\Persistence\Client\CookieStore;

/**
 * Handle the response to the client
 */
final class Response
{
    /**
     * @var ?Response\DispatcherInterface
     */
    private ?Response\DispatcherInterface $dispatcher;

    /**
     * Internal array values
     * @var array
     */
    private array $values;

    /**
     * Input errors
     *
     * @var array
     */
    private array $inputErrors;

    /**
     * Messages
     *
     * @var array
     */
    private array $messages;

    /**
     * Response status
     *
     * @var int
     */
    private int $status;

    /**
     * Http Response Headers
     *
     * @var array
     */
    private array $headers;
    
    /**
     * Flag to indicate if dispatching process is started
     * @var bool 
     */
    private bool $dispatching;

    /**
     * Function to execute before display the template
     * @var ?FunctionAddress
     */
    private ?FunctionAddress $onBeforeDisplay;

    /**
     * Function to execute when page not found
     * @var ?FunctionAddress
     */
    private ?FunctionAddress $onNotFound;

    /**
     * Function to execute before when access denied
     * @var ?FunctionAddress
     */
    private ?FunctionAddress $onAccessDenied;

    /**
     * Function to execute before when access denied
     * @var ?FunctionAddress
     */
    private ?FunctionAddress $onForbiddenAccess;

    /**
     *  Cookies sent by the browser
     * @var ?CookieStore
     */
    private ?CookieStore $cookies;

    /**
     * Constructor of the class. Initilize all internal fields
     */
    public function __construct()
    {
        $this->onBeforeDisplay = null;
        $this->onNotFound = null;
        $this->onAccessDenied = null;
        $this->cookies = null;
        $this->dispatcher = null;

        $this->values = [];
        $this->inputErrors = [];
        $this->messages = [];
        $this->headers = [];
        $this->dispatching = false;
        $this->status = 200;
    }

    //// INTERNAL ASSIGNMENT ------------------------------
    /**
     * Initialize the object with the internal configuration.
     * 
     * @param array $config Internal configuration
     * @return void
     */
    public function init(array &$config): void
    {
        if (isset($config['onBeforeDisplay']))
            $this->onBeforeDisplay = FunctionAddress::fromString($config['onBeforeDisplay']);

        if (isset($config['onNotFound']))
            $this->onNotFound = FunctionAddress::fromString($config['onNotFound']);

        if (isset($config['onAccessDenied']))
            $this->onAccessDenied = FunctionAddress::fromString($config['onAccessDenied']);

        if (isset($config['onForbiddenAccess']))
            $this->onForbiddenAccess = FunctionAddress::fromString($config['onForbiddenAccess']);
    }

    /**
     * Set a dispatcher to process the response to the client
     * 
     * @param Response\DispatcherInterface $dispatcher Dispatchet that process the response
     * @return void
     */
    public function setDispatcher(Response\DispatcherInterface $dispatcher) : void
    {
        // Set the internal dispatcher
        $this->dispatcher = $dispatcher;
    }

    /**
     * Set http response status
     *
     * @param int $status Header status
     * @return $this
     */
    public function setStatus(int $status) : self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Set/add a http response header
     * 
     * @param string $header Header name
     * @param string|null $value Header value
     * @return void
     */
    public function setHeader(string $header, ?string $value = null) : void
    {
        if(is_null($value))
        {
            $parts = explode(':', $header);

            if(!isset($parts[1]))
                throw new \InvalidArgumentException('Invalid header value. You must provide a Header: Value string or $Header and $Value parameters');

            $header = trim($parts[0]);
            $value = trim($parts[1]);
        }

        $this->headers[$header] = $value;
    }

    //// EVENTS ----------------------------------------------

    /**
     * Set onBeforeDisplay event handler. This event will be thron just before return the response
     *
     * @param FunctionAddress $onBeforeDisplay Function address to handle the event before dispatch the payload
     * @return void
     */
    public function onBeforeDisplay(FunctionAddress $onBeforeDisplay) : void
    {
        $this->onBeforeDisplay = $onBeforeDisplay;
    }

    /**
     * @return void
     */
    private function beforeDisplay(): void
    {
        if (!is_null($this->onBeforeDisplay))
        {
            $className = $this->onBeforeDisplay->getClassName();
            $functionName = $this->onBeforeDisplay->getFunctionName();
            $output = new $className();
            $output->$functionName();
        }
    }

    /**
     * Set onNotFound event handler.
     *
     * @param FunctionAddress $onNotFound Function address to handle the event when request is not found
     * @return void
     */
    public function onNotFound(FunctionAddress $onNotFound) : void
    {
        $this->onNotFound = $onNotFound;
    }

    /**
     * Respond not found error - 404 Response
     *
     * @throws \Exception
     */
    public function throwNotFound(): void
    {
        if (!empty(\Arkit\App::$Model))
            \Arkit\App::$Model->release();

        if (!is_null($this->onNotFound)) {
            $className = $this->onNotFound->getClassName();
            $functionName = $this->onNotFound->getFunctionName();
            $output = new $className();
            $output->$functionName();
        } else {
            header('Status: 404');
            readfile(dirname(__FILE__) . '/Response/Template/defaults/404.html');
        }

        exit;
    }

    /**
     * Set onAccessDenied event handler
     *
     * @param FunctionAddress $onAccessDenied Function address to handle the event when access is denied
     * @return void
     */
    public function onAccessDenied(FunctionAddress $onAccessDenied) : void
    {
        $this->onAccessDenied = $onAccessDenied;
    }

    /**
     * Respond unauthorized error - 401 Response
     * @return void
     */
    public function throwAccessDenied(): void
    {
        if (!empty(\Arkit\App::$Model))
            \Arkit\App::$Model->release();

        if (!is_null($this->onAccessDenied)) {
            $className = $this->onAccessDenied->getClassName();
            $functionName = $this->onAccessDenied->getFunctionName();
            $output = new $className();
            $output->$functionName();
        } else {
            header('Status: 401');
            readfile(dirname(__FILE__) . '/Response/Template/defaults/401.html');
        }

        exit;
    }

    /**
     * Set onForbiddenAccess event handler
     *
     * @param FunctionAddress $onForbiddenAccess Function address to handle the event when access is forbidden
     * @return void
     */
    public function onForbiddenAccess(FunctionAddress $onForbiddenAccess) : void
    {
        $this->onForbiddenAccess = $onForbiddenAccess;
    }

    /**
     * Respond forbidden error - 403 Response
     * @return void
     */
    public function throwForbiddenAccess(): void
    {
        if (!empty(\Arkit\App::$Model))
            \Arkit\App::$Model->release();

        if (!is_null($this->onForbiddenAccess)) {
            $className = $this->onForbiddenAccess->getClassName();
            $functionName = $this->onForbiddenAccess->getFunctionName();
            $output = new $className();
            $output->$functionName();
        } else {
            header('Status: 403');
            readfile(dirname(__FILE__) . '/Response/Template/defaults/403.html');
        }

        exit;
    }

    /**
     * Throw internal 400 error because invalid domain
     * @return void
     */
    public function throwInvalidRequest(): void
    {
        header('Status: 400');
        readfile(dirname(__FILE__) . '/Response/Template/defaults/400.html');
        exit;
    }

    /**
     * Encode an entry to html entities
     *
     * @param string|array $param Entry to transform
     * @param bool $utf8Encode Encode to utf-8
     * @return void
     */
    public function toHtmlEntities(string|array &$param, bool $utf8Encode = true): void
    {
        if (is_array($param)) {
            foreach (array_keys($param) as $key) {
                if (strcmp($key, 'literal') == 0) continue;

                if(!is_null($param[$key]))
                {
                    $value = $param[$key];
                    $this->toHtmlEntities($value, $utf8Encode);
                    $param[$key] = $value;
                }
            }
        } else {
            if ($utf8Encode)
                $param = htmlentities(utf8_encode($param), ENT_QUOTES, 'UTF-8');
            else
                $param = htmlentities($param, ENT_QUOTES, 'UTF-8');
        }
    }

    /**
     * Get response the cookies list
     * 
     * @return CookieStore
     */
    public function getCookies(): CookieStore
    {
        if (is_null($this->cookies))
            $this->cookies = new CookieStore();

        return $this->cookies;
    }

//    /**
//     * @param ?string $cacheId
//     * @return bool
//     * @throws \SmartyException
//     */
//    public function inCache(string $cacheId = null): bool
//    {
//        if (RUN_MODE != RELEASE_MODE || !!$this->template) return false;
//        $this->template->setCaching(\Smarty::CACHING_LIFETIME_CURRENT);
//
//        \Arkit\Core\Monitor\ErrorHandler::stop();
//        $result = @$this->template->isCached($this->tpl_name, $cacheId);
//        \Arkit\Core\Monitor\ErrorHandler::init();
//
//        return $result;
//    }

    //// OUTPUT ASSIGNMENT ------------------------------

    /**
     * Assign output values taken from a yaml file
     * 
     * @param string $field Output variable name. This variable will contains all values.
     * @param string $filePath Absolute file path to read the values
     * @param bool $encodeFirst Encode values to html entities
     * @param bool $toUtf8 Encode to utf-8. It works only if $encodeFirst is true.
     * @return self
     */
    public function assignFromFile(string $field, string $filePath, bool $encodeFirst = true, bool $toUtf8 = false): self
    {
        $value = \Arkit\App::readConfig($filePath);
        if ($encodeFirst)
            $this->toHtmlEntities($value, $toUtf8);

        if(is_null($this->dispatcher))
            $this->values[$field] = $value;
        else
            $this->dispatcher->assign($field, $value);

        return $this;
    }

    /**
     * Assign a value to the output
     * 
     * @param string|array $field Output variable name. This will be the name into the client side.
     * @param mixed|null $value Value to set
     * @param bool $encodeFirst Encode values to html entities
     * @param bool $toUtf8 Encode to utf-8. It works only if $encodeFirst is true.
     * @return self
     */
    public function assign(string|array $field, mixed $value = null, bool $encodeFirst = true, bool $toUtf8 = false): self
    {
        if (!is_object($value) && $encodeFirst)
            $this->toHtmlEntities($value, $toUtf8);

        if(!is_array($field))
        {
            if(!is_null($this->dispatcher) && $this->dispatching)
                $this->dispatcher->assign($field, $value);
            else
                $this->values[$field] = $value;
        }
        else
        {
            if(!is_null($this->dispatcher) && $this->dispatching)
                $this->dispatcher->assignValues($field);
            else
                $this->values = $this->values + $field;
        }

        return $this;
    }

    /**
     * Send an input error to the output. 
     * 
     * @param string $fieldName Input field name that provoke the the error
     * @param string $error Error message 
     * @param bool $encode Encode the error message to html entities
     * @param bool $toUtf8 Encode to utf-8. It works only if $encodeFirst is true.
     * @return self
     */
    public function inputError(string $fieldName, string $error, bool $encode = true, bool $toUtf8 = false): self
    {
        if($encode)
            $this->toHtmlEntities($error, $toUtf8);

        if(!is_null($this->dispatcher) && $this->dispatching)
            $this->dispatcher->inputError($fieldName, $error);
        else
            $this->inputErrors[$fieldName] = $error;

        return $this;
    }

    /**
     * Send some input errors to the output. 
     * 
     * @param array $errors Array of errors. Each key must be the name of the file that provoke the error. The associated value is taken as the error message.
     * @param bool $encode Encode error messages to html entities
     * @param bool $toUtf8 Encode to utf-8. It works only if $encode is true.
     * @return self
     */
    public function inputErrors(array $errors, bool $encode = true, bool $toUtf8 = false): self
    {
        if($encode)
            $this->toHtmlEntities($errors, $toUtf8);

        if(!is_null($this->dispatcher) && $this->dispatching)
            $this->dispatcher->inputErrors($errors);
        else
            $this->inputErrors = $this->inputErrors + $errors;

        return $this;
    }

    /**
     * Send an error to the output. 
     * 
     * @param string $errorType Error type
     * @param string $message Error message
     * @param bool $encode Encode error message to html entities
     * @param bool $toUtf8 Encode the message to utf-8. It works only if $encode is true.
     * @return self
     */
    public function error(string $errorType, string $message, bool $encode = true, bool $toUtf8 = false): self
    {
        if($encode)
            $this->toHtmlEntities($message, $toUtf8);

        if(!is_null($this->dispatcher) && $this->dispatching)
            $this->dispatcher->error($errorType, $message);
        else
            $this->messages[$errorType] = $message;

        return $this;
    }

    /**
     * Send a warning to the output
     * @param string $message Warning message
     * @param bool $encode Encode warning message to html entities
     * @param bool $toUtf8 Encode the message to utf-8. It works only if $encode is true.
     * @return self
     */
    public function warning(string $message, bool $encode = true, bool $toUtf8 = false): self
    {
        if($encode)
            $this->toHtmlEntities($message, $toUtf8);

        if(!is_null($this->dispatcher) && $this->dispatching)
            $this->dispatcher->warning($message);
        else
            $this->messages['WARNING'] = $message;

        return $this;
    }

    /**
     * Send a success message to the output
     * 
     * @param string $message Message
     * @param bool $encode Encode the message to html entities
     * @param bool $toUtf8 Encode the message to utf-8. It works only if $encode is true.
     * @return self
     */
    public function success(string $message, bool $encode = true, bool $toUtf8 = false): self
    {
        if($encode)
            $this->toHtmlEntities($message, $toUtf8);

        if(!is_null($this->dispatcher) && $this->dispatching)
            $this->dispatcher->success($message);
        else
            $this->messages['SUCCESS'] = $message;

        return $this;
    }

    //// DISPATCHING ------------------------------
    /**
     * Fetch headers to the output
     * 
     * @return void
     */
    private function fetchHeaders() : void
    {
        foreach ($this->headers as $headerName => $value)
            if(strtolower($headerName) !== 'status')
                header("$headerName: $value");
    }

    /**
     * Send the response to the client
     * 
     * @param string|null $resource Resource used by the dispacther
     * @param array|null $arguments Arguments used by the dispatcher
     * @return void
     */
    public function dispatch(?string $resource = null, ?array $arguments = null) : void
    {
        // Call before display trigger
        $this->beforeDisplay();

        // Fetch the headers
        http_response_code($this->status);
        $this->fetchHeaders();

        // Fetch the cookies
        if (!is_null($this->cookies))
            $this->cookies->dispatch();

        // Release all database connections
        if (!empty(\Arkit\App::$Model))
            \Arkit\App::$Model->release();
        
        // Set flag dispatching
        $this->dispatching = true;

        // Set all values to dispatcher
        $this->setDispatcherValues();
        
        // Dispatch
        $this->dispatcher->dispatch($resource, $arguments);

        exit;
    }

    /**
     * @return void
     */
    private function setDispatcherValues() : void
    {
        // Assign all values, errors and messages
        if(!empty($this->values))
            $this->dispatcher->assignValues($this->values);

        if(!empty($this->inputErrors))
            $this->dispatcher->inputErrors($this->inputErrors);

        if(!empty($this->messages))
        {
            foreach ($this->messages as $type => $message)
                if($type === 'WARNING')
                    $this->dispatcher->warning($message);
                elseif($type === 'SUCCESS')
                    $this->dispatcher->success($message);
                else
                    $this->dispatcher->error($type, $message);
        }
    }

    /**
     * Display a template. Set a TemplateDispatcher as internal dispatcher.
     *
     * @param string $template Template name or full template name
     * <ul>
     *  <li>If set the template name, the folder is 'view' at same level of the current controller</li>
     *  <li>Provide full path for custom folder</li>
     * </ul>
     * @param string|bool|null $cache Cache id or flag to indicate if cache as static content
     * @return void
     * @throws \SmartyException
     * @throws \Exception
     * @see \Arkit\Core\HTTP\Response\TemplateDispatcher
     */
    public function displayTemplate(string $template, string|bool $cache = null): void
    {
        $dispatcher = new Response\TemplateDispatcher(null);
        if(true === $cache)
            $dispatcher->cacheAfterDisplay();

        $this->setDispatcher($dispatcher);
        $this->dispatch($template, (!empty($cache) && is_string($cache)) ? ['cache' => $cache] : null);
        /*
        if(str_starts_with($template, 'extends:'))
        {
            $this->setDispatcher(new Response\TemplateDispatcher(null));
            $this->dispatch($template, (!empty($cacheId)) ? ['cache' => $cacheId] : null);
        }
        else
        {
            $tplPathInfo = pathinfo($template);
            $this->setDispatcher(new Response\TemplateDispatcher(($tplPathInfo['dirname'] !== '.') ? $tplPathInfo['dirname'] : null));
            $this->dispatch($tplPathInfo['basename'], (!empty($cacheId)) ? ['cache' => $cacheId] : null);
        }
        */
    }

    /**
     * Redirect to the url build by router. Set a RedirectDispatcher as internal dispatcher.
     *
     * @param string $urlId Rule id of the current url router
     * @param array|null $params (optional) Parameters to build the url
     * @see \Arkit\Core\HTTP\Response\RedirectDispatcher
     */
    public function redirectTo(string $urlId, ?array $params = null): void
    {
        // Build url to dispatch
        $url = \Arkit\App::$Router->buildUrl($urlId, $params);

        $this->setDispatcher(new Response\RedirectDispatcher());
        $this->dispatch($url);
    }

    /**
     * Redirect to a given URL. Set a RedirectDispatcher as internal dispatcher.
     *
     * @param string $url Url to redirect
     * @see \Arkit\Core\HTTP\Response\RedirectDispatcher
     */
    public function redirectToUrl(string $url): void
    {
        $this->setDispatcher(new Response\RedirectDispatcher());
        $this->dispatch($url);
    }

    /**
     * Dispatch values in JSON format. Set a JsonDispatcher as internal dispatcher.
     *
     * @return void
     * @see \Arkit\Core\HTTP\Response\JsonDispatcher
     */
    public function toJSON(): void
    {
        $this->setDispatcher(new Response\JsonDispatcher());
        $this->dispatch(null);
    }

}