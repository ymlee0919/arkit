<?php

/**
 * Class Output
 */
final class Output
{
    /**
     * @var ?Template
     */
    private ?Template $template;

    /**
     * @var ?string
     */
    private ?string $tpl_name;

    /**
     * @var ?string
     */
    private ?string $default_dir;
	
	/**
     * @var ?string function to execute before display the template
     */
	private ?string $onBeforeDisplay;

    /**
     * Constructor of the class
     */
    public function __construct()
	{
		$this->template = null;
		$this->tpl_name = null;
		$this->default_dir = null;
		$this->onBeforeDisplay = null;
	}

    /**
     * @param string $module
     * @returns void
     */
    public function setWorkingDir(string $module) : void
	{
		$this->default_dir = App::fullPath( 'Systems/' . App::$store['SYSTEM'] . '/' . $module);
	}

    /**
     * @param ?string $call
     * @returns void
     */
    public function beforeDisplay(string $call = null) : void
	{
        if(is_null($call) && isset(App::$config['onBeforeDisplay']))
            $this->onBeforeDisplay = App::$config['onBeforeDisplay'];
        else
		    $this->onBeforeDisplay = $call;
	}

    /**
     * Throw the 404 page
     * @throws Exception
     */
    public function throwWrongPage() : void
    {
        if(!empty(App::$Model))
            App::$Model->release();

        // Set the package
        if(!isset(App::$store['SYSTEM']))
            App::$store['SYSTEM'] = 'GardenCruz';

        // Set the working directory
        $this->setWorkingDir('_base');

		header('Status: 404');
        $this->loadTemplate('404.tpl');

        // Set CDN
        $this->assign('CDN', '');

        $this->displayTemplate();
        exit;
    }

    /**
     *
     */
    public function throwForbiddenPage() : void
    {

    }

    /**
     * Encode to html entities
     * @param string|array $param
     * @param bool $encode
     * @returns void
     */
    public function toHtmlEntities(string|array &$param, bool $encode = true) : void
    {
        if(is_array($param))
        {
            foreach(array_keys($param) as $key)
            {
                if(strcmp($key, 'literal') == 0) continue;

                $value = $param[$key];
                $this->toHtmlEntities($value, $encode);
                $param[$key] = $value;
            }
        }
        else
        {
            if($encode)
                $param = htmlentities(utf8_encode($param),ENT_QUOTES,'UTF-8');
            else
                $param = htmlentities($param,ENT_QUOTES,'UTF-8');
        }
    }

    /**
     * Load a template given the Page and the filename
     * @param string $filename
     * @param ?string $directory
     * @returns void
     * @throws Exception
     */
    public function loadTemplate(string $filename, string $directory = null) : void
	{
        import('Template','App.Server.View.Template');

		$this->template = new Template((is_null($directory)) ? $this->default_dir . "/view" : $directory);
		$this->tpl_name = $filename;
		
		// Set the current working directory
		$this->template->assign('CWD', App::$ROOT_DIR);

		$this->template->assign('URL', App::$Request->getRequestUrl());
	}

    /**
     * @param ?string $cacheId
     * @return bool
     * @throws SmartyException
     */
    public function inCache(string $cacheId = null) : bool
    {
        if(RUN_MODE != RELEASE_MODE || !!$this->template) return false;
        $this->template->setCaching(Smarty::CACHING_LIFETIME_CURRENT);

        ErrorHandler::stop();
        $result = @$this->template->isCached($this->tpl_name, $cacheId);
        ErrorHandler::init();

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
	public function assignFromFile(string $field, string $filePath, bool $encodeFirst = true, bool $toUtf8 = false) : void
	{
		$value = App::readConfig($filePath);
		if($encodeFirst)
			$this->toHtmlEntities($value, $toUtf8);

		if(!!$this->template)
			$this->template->assign($field, $value);
	}

    /**
     * Assing a value to the template
     * @param string|array $field
     * @param mixed|null $value
     * @param bool $encodeFirst
     * @param bool $toUtf8
     */
    public function assign(string|array $field, mixed $value = null, bool $encodeFirst = true, bool $toUtf8 = false) : void
	{
		if(!!$this->template)
        {
            if(!is_object($value))
                if($encodeFirst) $this->toHtmlEntities($value, $toUtf8);

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
	public function append(string|array $field, mixed $value, bool $merge = true) : void
	{
		if(!!$this->template)
			$this->template->append($field, $value, $merge);
	}

    /**
     * @throws Exception
     */
    private function execBeforeDisplay() : void
	{
        if(!isset($this->default_dir)) return;

        // Get the package
        $tokens = explode("/", $this->default_dir);
        array_pop($tokens);

        // Extract the file to import, the class name and the method
        $items = array();
        preg_match_all("/^([A-Za-z._-]+)\/([A-Za-z._-]+)::([A-Za-z._-]+)$/", $this->onBeforeDisplay, $items);

        // Items[1] : Include
        $include = sprintf("Systems.%s.%s", App::$store['SYSTEM'], $items[1][0]);
        // Import the file
        import($items[1][0], $include);

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
    public function setSessionVars(string ...$list) : void
    {
        $vars = func_get_args();

        foreach($vars as $var)
        {
            if(App::$Session->is_set($var))
            {
            	$value = App::$Session->get($var);
            	$this->toHtmlEntities($value, false);
                $this->template->assign($var, $value);
			}
		}
    }


    /**
     * Display the loaded template
     * @param string|null $cacheId
     * @return void
     * @throws SmartyException
     * @throws Exception
     */
    public function displayTemplate(string $cacheId = null) : void
	{
		if(!!$this->template)
		{
            if(isset(App::$store['CSRF']))
            {
                $this->template->assign('CSRF_INPUT', App::$store['CSRF']['HTML']);
                $this->template->assign('CSRF_CODE', App::$store['CSRF']['CODE']);
            }
			// Check if there is a function to execute before show the template
			if(!is_null($this->onBeforeDisplay))$this->execBeforeDisplay();
			ErrorHandler::stop();

            if(!empty(App::$Model))
                App::$Model->release();

            if(!!$cacheId)
			    $this->template->display($this->tpl_name, $cacheId);
            else
			    $this->template->display($this->tpl_name);
		}
		else
        {
            if(!empty(App::$Model))
                App::$Model->release();

            die("NO TEMPLATE TO DISPLAY");
        }
	}
	
	/**
	* Get the current template
	* @return Template
	*/
	public function getTemplate() : Template
	{
		return $this->template;
	}

    /**
     * Redirect to the url build by router
     * @param string $urlId
     * @param array|null $params
     */
    public function redirectTo(string $urlId, ?array $params = null) : void
	{
        if(!empty(App::$Model))
            App::$Model->release();

        $url = App::$Router->buildUrl($urlId, $params);
		header("Location: $url");
		exit;
	}

    /**
     * Redirect to a given URL
     * @param string $url
     */
    public function redirectToUrl(string $url) : void
	{
        if(!empty(App::$Model))
            App::$Model->release();

		header("Location: $url");
		exit;
	}

    /**
     * @param mixed $content
     * @param bool $disconnect
     * @param bool $encode_first
     * @returns void
     */
    public function write(mixed $content, bool $disconnect = false, bool $encode_first = true) : void
    {
        if($disconnect)
            if(!empty(App::$Model))
                App::$Model->release();

        if($encode_first)
            $this->toHtmlEntities($content);

        if(is_array($content))
            echo json_encode($content);
        else
            echo $content;
    }

    /**
     * @param mixed $var
     * @param bool $die
     * @returns void
     */
    public function display(mixed $var, bool $die = true) : void
    {
        echo "<pre>\n";
        //var_dump($var);
        echo htmlentities($var);
        echo "\n</pre>\n";

        if($die) exit;
    }

}