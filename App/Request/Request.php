<?php

/**
 * Class Request
 */
final class Request {

    /**
     * Requested URL
     * @var ?string
     */
    private ?string $_url = null;

    /**
     * URL parameters of the request
     * @var array
     */
    private array $_get;

    /**
     * Parameters passed by post
     * @var array
     */
    private array $_post;
	
    /**
     * Levels of the page
     * @var array
     */
    private array $levels;

    /**
     * Flag to indicate if the request is valid, given the configuration parameters
     * @var bool
     */
    private bool $isValid;

    /**
     *  Cookies sent by the browser
     * @var ?CookieStore
     */
    private ?CookieStore $cookies;

    /**
     *
     */
    public function __construct()
	{
        // Initialize fields
        $this->isValid = true;
        $this->_url    = null;
        $this->cookies = null;

        $this->_get    = [];
        $this->_post   = [];
        $this->levels  = [];
	}

    /**
     * Process the request
     * @return void
     */
    public function preProcess() : void
	{
        mb_internal_encoding('UTF-8');
        mb_detect_order(array('UTF-8', 'ASCII'));

        $this->_url = trim(urldecode($_SERVER['REQUEST_URI']));

		// Check the length of the url
        if(!!strpos($this->_url, 'aclk'))
            $this->_url = explode('aclk', $this->_url)[0];

        // Replace '/?' by '?' eg: /search/?q=query by /search?q=query
        $this->_url = str_replace('/?', '?', $this->_url);

        if(strlen($this->_url) == 1)
            return;
        if(strlen($this->_url) < 1)
        {
            $this->isValid = false;
            return;
        }

		// Validate the pattern of the url
        if(strlen($this->_url) > 1 && !preg_match_all("/^(\/[0-9a-zA-Z-]+(\/)?)+(\?([A-Za-z_]{2,}=[@A-Za-z0-9\._-]+)(&[A-Za-z_]{2,}=[@A-Za-z0-9\._-]+)*)?$/", $this->_url))
        {
            $this->isValid = false;
            return;
        }

        $this->_url = str_replace('/?', '?', $this->_url);

        // Separate url from get parameters
        $urlParts = explode('?', $this->_url);
		
		//// Treat the first part of the url
        // Split by slash
        $this->levels = explode('/', $urlParts[0]);
        // Remove the first item, is always null
		array_shift($this->levels);

		// Treat the parameters by get
		if(isset($urlParts[1]))
		{
			// Split the url options by the &
	        $opts = explode("&", $urlParts[1]);

	        foreach($opts as $item)
			{
				$tokens = explode('=', $item);
				$this->_get[$tokens[0]] = $tokens[1];
			}
			unset($opts);
		}

		unset($urlParts);
	}

    /**
     * Validate the request given some rules
     * @param array $rules Validation rules
     * @return bool
     */
    public function validate(array &$rules) : bool
    {
        // Validate max length url
        if(isset($rules['max_length']) && isset($this->_url[$rules['max_length']]))
            return $this->isValid = false;

        // Validate the number of parameters sent by url
        if(isset($rules['max_get_params']) && count($this->_get) > $rules['max_get_params'])
            return $this->isValid = false;

        // Validate the name and the value of each get parameter
        foreach ($this->_get as $name => $value)
        {
            if(isset($rules['max_get_name_size']) && isset($name[$rules['max_get_name_size']]))
                return $this->isValid = false;

            if(isset($rules['max_get_value_size']) && isset($value[$rules['max_get_value_size']]))
                return $this->isValid = false;
        }

        return true;
    }


    /**
     * @param array $config
     * @return void
     */
    public function processPost(array &$config) : void
    {
        // If the url is steel valid and the page is not null, take the parameters set by post
        $i = 0;
        $max = (isset($config['max_post_value_size'])) ? $config['max_post_value_size'] : 1024000000;
        $pattern = (isset($config['post_param_name_format'])) ? '/^'. $config['post_param_name_format'] .'$/' : null;

        foreach($_POST as $key => $value)
        {
            // If exceed the number of available parameters
            if(isset($config['max_post_params']) && $i >= $config['max_post_params']) {
                App::$Logs->notice("Parameter '$key' skipped. Maximum allowed.");
                break;
            }
            // Validate name length
            if(isset($config['max_post_name_size']) && isset($key[$config['max_post_name_size']]))
            {
                App::$Logs->notice("Parameter name '$key' have invalid size.");
                continue;
            }
            // Validate name
            if(!is_null($pattern) && !preg_match($pattern, $key))
            {
                App::$Logs->notice("Parameter name '$key' mismatch $pattern pattern.");
                continue;
            }

            if(!is_array($value))
            {
                if(!mb_check_encoding($key, 'UTF-8'))
                {
                    App::$Logs->notice("Parameter name '$key' have not UTF-8 encoding.");
                    continue;
                }
                if(mb_detect_encoding($value) == 'UTF-8')
                {
                    if(mb_strlen($value, 'UTF-8') > $max)
                        App::$Logs->notice("Parameter '$key' truncate to $max characters.");
                    $value = mb_substr($value, 0, $max);
                }
                elseif(mb_detect_encoding($value, 'ASCII'))
                {
                    if(mb_strlen($value, 'ASCII') > $max)
                        App::$Logs->notice("Parameter '$key' truncate to $max characters.");
                    $value = utf8_encode( mb_substr($value, 0, $max) );
                }
                else
                    continue;

                $this->_post[$key] = $value;
            }
            else
            {
                if( isset($config['max_post_array_size']) && isset($value[$config['max_post_array_size']]))
                {
                    App::$Logs->notice("Parameter name '$key' exceed the {$config['max_post_array_size']} elements.");
                    continue;
                }
                $list = []; $i = -1;
                foreach($value as $val)
                {
                    $i++;
                    if(mb_detect_encoding($val) == 'UTF-8')
                    {
                        if(mb_strlen($val, 'UTF-8') > $max)
                            App::$Logs->notice("Parameter '$key [$i]' truncate to $max characters.");
                        $list[] = mb_substr($val, 0, $max);
                    }
                    elseif(mb_detect_encoding($val, 'ASCII'))
                    {
                        if(mb_strlen($val, 'ASCII') > $max)
                            App::$Logs->notice("Parameter '$key [$i]' truncate to $max characters.");
                        $list[] = utf8_encode( mb_substr($val, 0, $max) );
                    }
                    else
                        continue;
                }

                $this->_post[$key] = $list;
            }

            unset($_POST[$key]);
            $i++;
        }
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
     * Get the level of url given an 1-based index
     * @param int $level
     * @return string|null
     */
    public function getUrlLevel(int $level) : ?string
	{
		if($level <= 0 || $level > count($this->levels)) return null;
		return $this->levels[$level - 1];
	}

    /**
     * Get an array of the url levels
     * @return array
     */
    public function getUrlLevels() : array
    {
        return $this->levels;
    }

    /**
     * Get all parameters passed by url
     * @return array
     */
    public function getAllGetParams() : array
    {
        return $this->_get;
    }

    /**
     * Get the value of a parameter passed by url
     * @param string $option
     * @return string|null
     */
    public function getGetParam(string $option) : ?string
	{
		if(isset($this->_get[$option]))
		return $this->_get[$option];
		return null;
	}

    /**
     * Get all fields sent by post
     * @return array
     */
    public function getAllPostParams() : array
	{
		return $this->_post;
	}

    /**
     * Get a post value given the name
     * @param string $param
     * @return mixed
     */
    public function getPostParam(string $param) : mixed
	{
		if(isset($this->_post[$param]))
		    return $this->_post[$param];
		return null;
	}

    /**
     * Check if a post value was sent
     * @param string $paramName
     * @return bool
     */
    public function isSetPostParam(string $paramName) : bool
	{
		return isset($this->_post[$paramName]);
	}

    /**
     * Get a post value given the name
     * @param string $param
     * @return mixed
     */
    public function getFileParam(string $param) : mixed
	{
		if(isset($_FILES[$param]))
            return $_FILES[$param];
		return null;
	}

    /**
     * Check if a post value was sent
     * @param string $paramName
     * @return bool
     */
    public function isSetFileParam(string $paramName) : bool
	{
		return isset($_FILES[$paramName]);
	}

    /**
     * @return CookieStore
     */
    public function getCookies() : CookieStore
    {
        if(is_null($this->cookies))
            $this->cookies = CookieStore::fromServerRequest();

        return  $this->cookies;
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
		return $this->_url;
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