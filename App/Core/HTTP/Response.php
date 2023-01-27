<?php

namespace Arkit\Core\HTTP;

use Arkit\Core\Base\FunctionAddress;
use Arkit\Core\Persistence\Client\CookieStore;

/**
 * Class Output
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
    private ?FunctionAddress $onPageNotFound;

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
     * Constructor of the class
     */
    public function __construct()
    {
        $this->onBeforeDisplay = null;
        $this->onPageNotFound = null;
        $this->onAccessDenied = null;
        $this->cookies = null;
        $this->dispatcher = null;

        $this->values = [];
        $this->inputErrors = [];
        $this->messages = [];
        $this->headers = [];
        $this->dispatching = false;
    }

    public function init(array &$config): void
    {
        if (isset($config['onBeforeDisplay']))
            $this->onBeforeDisplay = FunctionAddress::fromString($config['onBeforeDisplay']);

        if (isset($config['onPageNotFound']))
            $this->onPageNotFound = FunctionAddress::fromString($config['onPageNotFound']);

        if (isset($config['onAccessDenied']))
            $this->onAccessDenied = FunctionAddress::fromString($config['onAccessDenied']);

        if (isset($config['onForbiddenAccess']))
            $this->onForbiddenAccess = FunctionAddress::fromString($config['onForbiddenAccess']);
    }

    public function setDispatcher(Response\DispatcherInterface $dispatcher) : void
    {
        // Set the internal dispatcher
        $this->dispatcher = $dispatcher;
    }

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

    private function fetchHeaders() : void
    {
        foreach ($this->headers as $headerName => $value)
            header("$headerName: $value");
    }

    /**
     * @returns void
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
     * Throw the 404 page
     * @throws \Exception
     */
    public function throwPageNotFound(): void
    {
        if (!empty(\Arkit\App::$Model))
            \Arkit\App::$Model->release();

        if (!is_null($this->onPageNotFound)) {
            $className = $this->onPageNotFound->getClassName();
            $functionName = $this->onPageNotFound->getFunctionName();
            $output = new $className();
            $output->$functionName();
        } else {
            header('Status: 404');
            readfile(dirname(__FILE__) . '404.html');
        }

        exit;
    }

    /**
     *
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
            readfile(dirname(__FILE__) . '401.html');
        }

        exit;
    }

    /**
     *
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
            readfile(dirname(__FILE__) . '403.html');
        }

        exit;
    }

    /**
     * Throw internal 400 error because invalid domain
     */
    public function throwInvalidRequest(): void
    {
        header('Status: 400');
        readfile(dirname(__FILE__) . '400.html');
    }

    /**
     * Encode to html entities
     * @param string|array $param
     * @param bool $utf8Encode
     * @returns void
     */
    public function toHtmlEntities(string|array &$param, bool $utf8Encode = true): void
    {
        if (is_array($param)) {
            foreach (array_keys($param) as $key) {
                if (strcmp($key, 'literal') == 0) continue;

                $value = $param[$key];
                $this->toHtmlEntities($value, $utf8Encode);
                $param[$key] = $value;
            }
        } else {
            if ($utf8Encode)
                $param = htmlentities(utf8_encode($param), ENT_QUOTES, 'UTF-8');
            else
                $param = htmlentities($param, ENT_QUOTES, 'UTF-8');
        }
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

    /**
     * Assign a values to the template from a file
     * @param string $field
     * @param string $filePath
     * @param bool $encodeFirst
     * @param bool $toUtf8
     * @return void
     */
    public function assignFromFile(string $field, string $filePath, bool $encodeFirst = true, bool $toUtf8 = false): void
    {
        $value = \Arkit\App::readConfig($filePath);
        if ($encodeFirst)
            $this->toHtmlEntities($value, $toUtf8);

        if(is_null($this->dispatcher))
            $this->values[$field] = $value;
        else
            $this->dispatcher->assign($field, $value);
    }

    /**
     * Assing a value to the template
     * @param string|array $field
     * @param mixed|null $value
     * @param bool $encodeFirst
     * @param bool $toUtf8
     */
    public function assign(string|array $field, mixed $value = null, bool $encodeFirst = true, bool $toUtf8 = false): void
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
    }

    public function inputError(string $fieldName, string $error, bool $encode = true, bool $toUtf8 = false): void
    {
        if($encode)
            $this->toHtmlEntities($error, $toUtf8);

        if(!is_null($this->dispatcher) && $this->dispatching)
            $this->dispatcher->inputError($fieldName, $error);
        else
            $this->inputErrors[$fieldName] = $error;
    }

    public function inputErrors(array $errors, bool $encode = true, bool $toUtf8 = false): void
    {
        if($encode)
            $this->toHtmlEntities($errors, $toUtf8);

        if(!is_null($this->dispatcher) && $this->dispatching)
            $this->dispatcher->inputErrors($errors);
        else
            $this->inputErrors = $this->inputErrors + $errors;
    }

    public function error(string $errorType, string $message, bool $encode = true, bool $toUtf8 = false): void
    {
        if($encode)
            $this->toHtmlEntities($message, $toUtf8);

        if(!is_null($this->dispatcher) && $this->dispatching)
            $this->dispatcher->error($errorType, $message);
        else
            $this->messages[$errorType] = $message;
    }

    public function warning(string $message, bool $encode = true, bool $toUtf8 = false): void
    {
        if($encode)
            $this->toHtmlEntities($message, $toUtf8);

        if(!is_null($this->dispatcher) && $this->dispatching)
            $this->dispatcher->warning($message);
        else
            $this->messages['WARNING'] = $message;
    }

    public function success(string $message, bool $encode = true, bool $toUtf8 = false): void
    {
        if($encode)
            $this->toHtmlEntities($message, $toUtf8);

        if(!is_null($this->dispatcher) && $this->dispatching)
            $this->dispatcher->success($message);
        else
            $this->messages['SUCCESS_MESSAGE'] = $message;
    }

    public function dispatch(string $resource, ?array $arguments = null) : void
    {
        // Call before display trigger
        $this->beforeDisplay();

        // Fetch the headers
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
    }
    
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

    public function getCookies(): CookieStore
    {
        if (is_null($this->cookies))
            $this->cookies = new CookieStore();

        return $this->cookies;
    }


    /**
     * Display a template
     * @param string $template Template name or full template name
     * <ul>
     *  <li>If set the template name, the folder is 'view' at same level of the current controller</li>
     *  <li>Provide full path for custom folder</li>
     * </ul>
     * @param string|null $cacheId
     * @return void
     * @throws \SmartyException
     * @throws \Exception
     */
    public function displayTemplate(string $template, string $cacheId = null): void
    {
        $tplPathInfo = pathinfo($template);
        $this->setDispatcher(new Response\TemplateDispatcher(($tplPathInfo['dirname'] !== '.') ? $tplPathInfo['dirname'] : null));
        $this->dispatch($tplPathInfo['basename'], (!empty($cacheId)) ? ['cache' => $cacheId] : null);
    }

    /**
     * Redirect to the url build by router
     * @param string $urlId
     * @param array|null $params
     */
    public function redirectTo(string $urlId, ?array $params = null): void
    {
        // Build url to dispatch
        $url = \Arkit\App::$Router->buildUrl($urlId, $params);

        $this->setDispatcher(new Response\RedirectDispatcher());
        $this->dispatch($url);
    }

    /**
     * Redirect to a given URL
     * @param string $url
     */
    public function redirectToUrl(string $url): void
    {
        $this->setDispatcher(new Response\RedirectDispatcher());
        $this->dispatch($url);
    }

    /**
     * @returns void
     */
    public function toJSON(): void
    {
        $this->setDispatcher(new Response\JsonDispatcher());
        $this->dispatch(null);
    }

}