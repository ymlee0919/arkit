<?php

namespace Arkit\Core\HTTP;

use Arkit\Core\Base\FunctionAddress;
use Arkit\Core\HTTP\Response\Template;
use Arkit\Core\Persistence\Client\CookieStore;

/**
 * Class Output
 */
final class Response
{
    /**
     * @var ?Response\Template
     */
    private ?Response\Template $template;

    /**
     * @var ?string
     */
    private ?string $tpl_name;

    /**
     * @var ?string
     */
    private ?string $default_dir;

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
        $this->template = null;
        $this->tpl_name = null;
        $this->default_dir = null;
        $this->onBeforeDisplay = null;
        $this->onPageNotFound = null;
        $this->onAccessDenied = null;
        $this->cookies = null;
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

    /**
     * @param string $module
     * @returns void
     */
    public function setWorkingDir(string $module): void
    {
        $this->default_dir = \Arkit\App::fullPath('Systems/' . $module);
    }

    /**
     * @param ?string $call
     * @returns void
     */
    public function beforeDisplay(string $call = null): void
    {
        if (is_null($call) && isset(\Arkit\App::$config['onBeforeDisplay']))
            $this->onBeforeDisplay = \Arkit\App::$config['onBeforeDisplay'];
        else
            $this->onBeforeDisplay = $call;
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
            $this->onPageNotFound->importFrom('System', \Arkit\App::$store['SYSTEM']);
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
            $this->onAccessDenied->importFrom('System', \Arkit\App::$store['SYSTEM']);
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
            $this->onForbiddenAccess->importFrom('System', \Arkit\App::$store['SYSTEM']);
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

    /**
     * Load a template given the Page and the filename
     * @param string $filename
     * @param ?string $directory
     * @returns void
     * @throws \Exception
     */
    public function loadTemplate(string $filename, string $directory = null): void
    {
        $this->template = new Response\Template((is_null($directory)) ? $this->default_dir . "/view" : $directory);
        $this->tpl_name = $filename;

        // Set the current working directory
        $this->template->assign('CWD', \Arkit\App::$ROOT_DIR);

        $this->template->assign('URL', \Arkit\App::$Request->getRequestUrl());
    }

    /**
     * @param ?string $cacheId
     * @return bool
     * @throws \SmartyException
     */
    public function inCache(string $cacheId = null): bool
    {
        if (RUN_MODE != RELEASE_MODE || !!$this->template) return false;
        $this->template->setCaching(\Smarty::CACHING_LIFETIME_CURRENT);

        \Arkit\Core\Monitor\ErrorHandler::stop();
        $result = @$this->template->isCached($this->tpl_name, $cacheId);
        \Arkit\Core\Monitor\ErrorHandler::init();

        return $result;
    }

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

        if (!!$this->template)
            $this->template->assign($field, $value);
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
        if (!!$this->template) {
            if (!is_object($value))
                if ($encodeFirst) $this->toHtmlEntities($value, $toUtf8);

            $this->template->assign($field, $value);
        }
    }

    /**
     * Append a value to a template
     * @param string|array $field
     * @param mixed $value
     * @param bool $merge
     * @returns void
     */
    public function append(string|array $field, mixed $value, bool $merge = true): void
    {
        if (!!$this->template)
            $this->template->append($field, $value, $merge);
    }

    /**
     * @throws \Exception
     */
    private function execBeforeDisplay(): void
    {
        if (!isset($this->default_dir)) return;

        // Get the package
        $tokens = explode("/", $this->default_dir);
        array_pop($tokens);

        // Extract the file to import, the class name and the method
        $items = array();
        preg_match_all("/^([A-Za-z._-]+)\/([A-Za-z._-]+)::([A-Za-z._-]+)$/", $this->onBeforeDisplay, $items);

        // Items[1] : Include
        $include = sprintf("Systems.%s.%s", \Arkit\App::$store['SYSTEM'], $items[1][0]);
        // Import the file
        \Loader::import($items[1][0], $include);

        // Items[2] : Class
        $class = $items[2][0];
        // Create the class
        $obj = new $class();

        // Items[3] : Method
        $method = $items[3][0];

        // Call the method
        $obj->$method();
    }

    /**
     * Read session vars and assign them to current template
     * @param string ...$list Comma separated list of session vars
     */
    public function setSessionVars(string ...$list): void
    {
        $vars = func_get_args();

        foreach ($vars as $var) {
            if (\Arkit\App::$Session->is_set($var)) {
                $value = \Arkit\App::$Session->get($var);
                $this->toHtmlEntities($value, false);
                $this->template->assign($var, $value);
            }
        }
    }


    /**
     * Display the loaded template
     * @param string|null $cacheId
     * @return void
     * @throws \SmartyException
     * @throws \Exception
     */
    public function displayTemplate(string $cacheId = null): void
    {
        if (!!$this->template) {
            if (!is_null($this->cookies))
                $this->cookies->dispatch();
            if (isset(\Arkit\App::$store['CSRF'])) {
                $this->template->assign('CSRF_INPUT', \Arkit\App::$store['CSRF']['HTML']);
                $this->template->assign('CSRF_CODE', \Arkit\App::$store['CSRF']['CODE']);
            }
            // Check if there is a function to execute before show the template
            if (!is_null($this->onBeforeDisplay)) $this->execBeforeDisplay();
            \Arkit\Core\Monitor\ErrorHandler::stop();

            if (!empty(\Arkit\App::$Model))
                \Arkit\App::$Model->release();

            if (!!$cacheId)
                $this->template->display($this->tpl_name, $cacheId);
            else
                $this->template->display($this->tpl_name);
        } else {
            if (!empty(\Arkit\App::$Model))
                \Arkit\App::$Model->release();

            die("NO TEMPLATE TO DISPLAY");
        }
    }

    /**
     * Get the current template
     * @return Template
     */
    public function getTemplate(): Template
    {
        return $this->template;
    }

    public function getCookies(): CookieStore
    {
        if (is_null($this->cookies))
            $this->cookies = new CookieStore();

        return $this->cookies;
    }

    /**
     * Redirect to the url build by router
     * @param string $urlId
     * @param array|null $params
     */
    public function redirectTo(string $urlId, ?array $params = null): void
    {
        if (!empty(\Arkit\App::$Model))
            \Arkit\App::$Model->release();

        $url = \Arkit\App::$Router->buildUrl($urlId, $params);
        header("Location: $url");
        exit;
    }

    /**
     * Redirect to a given URL
     * @param string $url
     */
    public function redirectToUrl(string $url): void
    {
        if (!empty(\Arkit\App::$Model))
            \Arkit\App::$Model->release();

        header("Location: $url");
        exit;
    }

    /**
     * @param mixed $content
     * @param bool $disconnect
     * @param bool $encode_first
     * @returns void
     */
    public function write(mixed $content, bool $disconnect = false, bool $encode_first = true): void
    {
        if ($disconnect)
            if (!empty(\Arkit\App::$Model))
                \Arkit\App::$Model->release();

        if ($encode_first)
            $this->toHtmlEntities($content);

        if (is_array($content))
            echo json_encode($content);
        else
            echo $content;
    }

    /**
     * @param mixed $var
     * @param bool $die
     * @returns void
     */
    public function display(mixed $var, bool $die = true): void
    {
        echo "<pre>\n";
        //var_dump($var);
        echo htmlentities($var);
        echo "\n</pre>\n";

        if ($die) exit;
    }

}