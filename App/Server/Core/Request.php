<?php

/*
 * Class for manage the request
 * URL format: site.com/router[/page][/options]
 * */

/**
 * Class Request
 */
final class Request {

    /**
     * Requested URL
     * @var ?string
     */
    private ?string $request = null;

    /**
     * URL parameters of the request
     * @var array
     */
    private array $options;
	
    /**
     * Levels of the page
     * @var array
     */
    private array $levels;
    
    /**
     * Parameters passed by post
     * @var array
     */
    private array $params;
    
    /**
     * Array to store shared values
     * @var array
     */
    private array $values;
	
    /**
     * Flag to indicate if the request is valid, given the configuration parameters
     * @var bool
     */
    private bool $isValid = true;

    /**
     *
     */
    public function __construct()
	{
        // Initialize fields
        $this->isValid      = true;
        $this->request      = null;

        $this->options      = [];
        $this->params       = [];
        $this->values       = [];
        $this->levels       = [];
        $this->url_params   = [];
	}

    /**
     * Process the request
     * @return bool
     */
    public function process() : bool
	{
        mb_internal_encoding('UTF-8');
        mb_detect_order(array('UTF-8', 'ASCII'));
		
        $url = trim(urldecode($_SERVER['REQUEST_URI']));

        $this->write();

		// Check the length of the url
        if(!!strpos($url, 'aclk'))
            $url = explode('aclk', $url)[0];

        if(isset($url[App::$config['url']['max_length']])) return false;

        $url = $this->request = str_replace('/?', '?', $url);

        if(strlen($url) == 1) return true;
        if(strlen($url) < 1) return false;


		// Validate the pattern of the url
        if(strlen($url) > 1 && !preg_match_all("/^(\/[0-9a-zA-Z-]+(\/)?){1,}(\?([A-Za-z_]{2,}=[@A-Za-z0-9\._-]+)(&[A-Za-z_]{2,}=[@A-Za-z0-9\._-]+)*)?$/", $url)) return false;

		// Split the url  by the ?
		$parts = explode('?', $url);

        // Treat the / and the end of the url
        if(substr($parts[0], -1) == '/')
        {
            $parts[0] = substr($parts[0], 0, -1);
            $url = $this->request = implode('?', $parts);
        }
		
		//// Trear the first part of the url
        // Split by slash
        $levels = explode('/', $parts[0]);
		array_shift($levels);


        // Validate the count of levels
        if(isset($levels[App::$config['url']['max_levels']])) return false;

        // Process each token and check syntax
		reset($levels);
        foreach($levels as $item)
        {
			$length = strlen($item);
            if($length >= App::$config['url']['min_level_length'] && $length <= App::$config['url']['max_level_length'])
				array_push($this->levels, $item);
			else
				return false;
        }
		unset($levels);

		// Treat the parameters by get
		if(isset($parts[1]))
		{
			// Split the url options by the &
	        $opts = explode("&", $parts[1]);
			if(isset($opts[App::$config['url']['max_get_params']])) return false;
			
			reset($opts);
	        foreach($opts as $item)
			{
				$tokens = explode('=', $item);
				if(isset($tokens[0][App::$config['url']['max_get_param_name']])) return false;
				
				if(isset($tokens[1][App::$config['url']['max_get_size']])) return false;
				
				$this->options[$tokens[0]] = $tokens[1];
			}
			unset($opts);
		}

		unset($parts);
		
		return true;
	}

    /**
     * Process values sent by post
     */
    public function processPost() : void
    {
        // If the url is steel valid and the page is not null, take the parameters set by post
        $i = 0; $max = App::$config['url']['max_post_size'];
        foreach($_POST as $key => $value)
        {
            if($i >= App::$config['url']['max_post_params']) break;

            if(!is_array($value))
            {
                if(isset($key[App::$config['url']['max_post_param_name']])) continue;

                if(!mb_check_encoding($key, 'UTF-8'))
                    continue;
                if(mb_detect_encoding($value) == 'UTF-8')
                    $value = mb_substr($value, 0, $max);
                elseif(mb_detect_encoding($value, 'ASCII'))
                    $value = utf8_encode( mb_substr($value, 0, $max) );
                else
                    continue;

                $this->params[$key] = $value;
            }
            else
            {
                if(isset($value[App::$config['url']['max_post_array_size']])) continue;
                $list = [];
                foreach($value as $val)
                {
                    if(mb_detect_encoding($val) == 'UTF-8')
                        $list[] = mb_substr($val, 0, $max);
                    elseif(mb_detect_encoding($val, 'ASCII'))
                        $list[] = utf8_encode( mb_substr($val, 0, $max) );
                    else
                        continue;
                }

                $this->params[$key] = $list;
            }

            unset($_POST[$key]);
            $i++;

        }
    }

    /**
     * Get a string representation of the request
     */
    public function toString() : void
    {
        echo "Request: " . $this->request . "<br>\n";
        echo "Levels: " . print_r($this->levels, true) . "<br>\n";
        echo "Options: " . print_r($this->options, true) . "<br>\n";
        echo "URL Parameters: " . print_r($this->url_params, true) . "<br>\n";
        echo "Request Method: " . $_SERVER['REQUEST_METHOD'] . "<br>\n";
		echo "IsValid: " . (($this->isValid) ? "Yes" : "No");
    }

    /**
     * Check if the url is valid
     * @returns bool
     */
    public function isValid() : bool
	{
		return $this->isValid;
	}

    /**
     * Check if the url is emply (have not levels)
     * @returns bool
     */
    public function isEmptyUrl() : bool
	{
		return ( count($this->levels) == 0 );
	}

    /**
     * Get all parameters passed by url
     * @return array
     */
    public function GetAll() : array
	{
		return $this->options;
	}

    /**
     * Get the level of url given an 1-based index
     * @param $level
     * @return string|null
     */
    public function getUrlLevel($level) : ?string
	{
		if($level <= 0 || $level > count($this->levels)) return null;
		return $this->levels[$level - 1];
	}

    /**
     * Get an array of the url leves
     * @return array
     */
    public function getUrlLeves() : array
    {
        return $this->levels;
    }


    /**
     * Get the value of a parameter passed by url
     * @param $option
     * @return string|null
     */
    public function Get(string $option) : ?string
	{
		if(isset($this->options[$option]))
		return $this->options[$option];
		return null;
	}

    /**
     * Get the name of parameters passed by url
     * @return array
     */
    public function namesGet() : array
    {
        return array_keys($this->options);
    }
    
    /** 
     * Unset parameters in the url
     * @param string $name
     */
    public function unsetGet(string $name) : void
    {
    	if(isset($this->options[$name]))
    		unset($this->options[$name]);
    }

    /**
     * Get all fields sent by post
     * @return array
     */
    public function PostAll() : array
	{
		return $this->params;
	}

    /**
     * Get a post value given the name
     * @param $param
     * @returns mixed
     */
    public function Post($param) : mixed
	{
		if(isset($this->params[$param]))
		    return $this->params[$param];
		return null;
	}

    /**
     * Check if a post value was sent
     * @param string $param
     * @returns bool
     */
    public function isSetPost($param) : bool
	{
		return in_array($param, array_keys($this->params));
	}

    /**
     * Store a value
     * @param string $key
     * @param mixed $value
     */
    public function setValue(string $key, mixed $value) : void
	{
		$this->values[$key] = $value;
	}

    /**
     * Get an stored value
     * @param string $key
     * @return mixed
     */
    public function getValue(string $key) : mixed
	{
		if(isset($this->values[$key]))
		    return $this->values[$key];
		return null;
	}

    /**
     * Get all stored values
     * @return array
     */
    public function getValues() : array
	{
		return $this->values;
	}

    /**
     * Get the requested method
     * @return string
     */
    public function getRequestMethod() : string
	{
		return strtoupper($_SERVER['REQUEST_METHOD']);
	}

    /**
     * Get the requested url
     * @return null|string
     */
    public function getRequestUrl() : ?string
	{
		return $this->request;
	}

    /**
     * @param string $url
     */
    public function setRequestUrl(string $url) : void
    {
        $this->request = $url;

        $levels = explode('/', $url);
        array_shift($levels);

        $this->levels = $levels;
    }

    public function write() : void
    {
        // Open file
        $filename = dirname(dirname(dirname(__FILE__))) . "/Logs/" . date("Y.m.d") . ".txt";
        $hfile = fopen($filename,"a+t");

        // Write session
        $text = "IP:" . $_SERVER["REMOTE_ADDR"] . "\n";
        $text .= "URL: " . urldecode($_SERVER['REQUEST_URI']) . "\n";
        $text .= "TIME: " . date("Y-m-d H:i:s") . "\n";
        $text .= "-------------------------------------------------\n";
        fwrite($hfile, $text);
        fflush($hfile);
        fclose($hfile);
    }

    /**
     * @return string
     */
    public function getRequestedDomain() : string
    {
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * @return string
     */
    public function getRequestedProtocolAndDomain() : string
    {
        return ((!empty($_SERVER['HTTPS'])) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];
    }
}