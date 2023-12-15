<?php

namespace Arkit\Core\HTTP;

use Arkit\Core\Persistence\Client\CookieStore;
use Arkit\Core\HTTP\Request\PayloadParserInterface;

/**
 * Client request handler
 */
final class Request implements RequestInterface
{

    /**
     * Requested URL
     * @var ?string
     */
    private ?string $_url = null;

    /**
     * Headers sent by client
     * @var array
     */
    private array $headers;

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
     * Internal configuration
     * @var array
     */
    private array $config;

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
     * Parser for the body
     * @var PayloadParserInterface|null
     */
    private $payloadParser;

    /**
     *
     */
    public function __construct()
    {
        // Initialize fields
        $this->isValid = true;
        $this->_url = null;
        $this->cookies = null;

        $this->_get = [];
        $this->_post = [];
        $this->levels = [];
        $this->config = [];

        $this->headers = [];

        foreach (getallheaders() as $key => $value)
            $this->headers[strtoupper($key)] = $value;
    }

    /**
     * @param array $config
     * @return void
     */
    public function init(array &$config) : void
    {
        $this->config = $config;
    }

    /**
     * Set parser for the payload request
     * @param PayloadParserInterface $payloadParser
     * @return void
     */
    public function setPayloadParser(PayloadParserInterface $payloadParser) : void
    {
        $this->payloadParser = $payloadParser;
    }

    /**
     * Get value of header given the name
     * @param string $headerName
     * @return string|null
     */
    public function getHeader(string $headerName) : ?string
    {
        $header = strtoupper($headerName);
        return $this->headers[$header] ?? null;
    }

    /**
     * Get all headers
     * @return array
     */
    public function getAllHeaders() : array
    {
        return $this->headers;
    }

    /**
     * Process the request
     * @return void
     */
    private function preProcess(): void
    {
        mb_internal_encoding('UTF-8');
        mb_detect_order(array('UTF-8', 'ASCII'));

        $this->_url = trim(urldecode($_SERVER['REQUEST_URI']));

        $fullRequest = $this->getRequestedProtocolAndDomain() . $this->_url;
        if(!filter_var($fullRequest, FILTER_VALIDATE_URL)){
            $this->isValid = false;
            return;
        }

        // Replace '/?' by '?' eg: /search/?q=query by /search?q=query
        $this->_url = str_replace('/?', '?', $this->_url);

        if (strlen($this->_url) == 1)
            return;

        if (strlen($this->_url) < 1) {
            $this->isValid = false;
            return;
        }

        // Validate the pattern of the url
        /*if (strlen($this->_url) > 1 && !preg_match_all("/^(\/[0-9a-zA-Z-]+(\/)?)+(\?([A-Za-z_]{2,}=[@A-Za-z0-9\._-]+)(&[A-Za-z_]{2,}=[@A-Za-z0-9\._-]+)*)?$/", $this->_url)) {
            $this->isValid = false;
            return;
        }*/

        // Separate url from get parameters
        $urlParts = parse_url($this->_url);

        //// Treat the first part of the url
        // Split by slash
        $this->levels = explode('/', $urlParts['path']);
        // Remove the first item if empty
        if(empty($this->levels[0]))
            array_shift($this->levels);

        // Treat the parameters by get
        if (isset($urlParts['query']))
            parse_str($urlParts['query'], $this->_get);

        unset($urlParts);
    }

    /**
     * Validate the request given some rules
     * @return bool
     */
    public function validate(): bool
    {
        $this->preProcess();

        if (!$this->isValid)
            return false;

        // Validate max length url
        if (isset($this->config['max_length']) && isset($this->_url[$this->config['max_length']]))
            return $this->isValid = false;

        // Validate the number of parameters sent by url
        if (isset($this->config['max_get_params']) && count($this->_get) > $this->config['max_get_params'])
            return $this->isValid = false;

        // Validate the name and the value of each get parameter
        foreach ($this->_get as $name => $value) {
            if (isset($this->config['max_get_name_size']) && isset($name[$this->config['max_get_name_size']]))
                return $this->isValid = false;

            if (isset($this->config['max_get_value_size']) && isset($value[$this->config['max_get_value_size']]))
                return $this->isValid = false;
        }

        return true;
    }

    /**
     * Parse the payload according the request type or PayloadParser provided.
     * 
     * @return void
     */
    public function processPayload(): void
    {
        // Parse the payload according the request type
        if(is_null($this->payloadParser))
        {
            // Set multipart/form as default
            $contentType = $this->getHeader('Content-Type') ?? 'multipart/form-data;';
            $contentType = strtolower(trim(explode(';', $contentType)[0]));

            $this->payloadParser = match ($contentType) {
                'application/json' => new \Arkit\Core\HTTP\Request\JsonParser(),
                'application/x-www-form-urlencoded' => new \Arkit\Core\HTTP\Request\UrlEncodedParser(),
                default => new \Arkit\Core\HTTP\Request\MultipartFormParser()
            };

            $this->payloadParser->setHeaders(getallheaders());
        }

        $content = file_get_contents('php://input');
        $this->payloadParser->parse($content);
        $values = $this->payloadParser->getAll();

        $this->setPostValues($values);
        unset($values);
    }

    protected function setPostValues(array &$values) : void
    {
        // Validate parameters
        $i = 0;
        $max = (isset($this->config['max_post_value_size'])) ? $this->config['max_post_value_size'] : 1024000000;
        $pattern = (isset($this->config['post_param_name_format'])) ? '/^' . $this->config['post_param_name_format'] . '$/' : null;

        foreach ($values as $key => $value)
        {
            // If exceed the number of available parameters
            if (isset($this->config['max_post_params']) && $i >= $this->config['max_post_params'])
            {
                \Arkit\App::$Logs->notice("Parameter '$key' skipped. Maximum allowed.");
                break;
            }
            // Validate name length
            if (isset($this->config['max_post_name_size']) && isset($key[$this->config['max_post_name_size']]))
            {
                \Arkit\App::$Logs->notice("Parameter name '$key' have invalid size.");
                continue;
            }
            // Validate name
            if (!is_null($pattern) && !preg_match($pattern, $key))
            {
                \Arkit\App::$Logs->notice("Parameter name '$key' mismatch $pattern pattern.");
                continue;
            }

            if (!is_array($value))
            {
                if (!mb_check_encoding($key, 'UTF-8'))
                {
                    \Arkit\App::$Logs->notice("Parameter name '$key' have not UTF-8 encoding.");
                    continue;
                }
                if (mb_detect_encoding($value) == 'UTF-8')
                {
                    if (mb_strlen($value, 'UTF-8') > $max)
                        \Arkit\App::$Logs->notice("Parameter '$key' truncate to $max characters.");
                    $value = mb_substr($value, 0, $max);
                } elseif (mb_detect_encoding($value, 'ASCII'))
                {
                    if (mb_strlen($value, 'ASCII') > $max)
                        \Arkit\App::$Logs->notice("Parameter '$key' truncate to $max characters.");
                    $value = utf8_encode(mb_substr($value, 0, $max));
                } else
                    continue;

                $this->_post[$key] = $value;
            }
            else
            {
                if (isset($this->config['max_post_array_size']) && isset($value[$this->config['max_post_array_size']]))
                {
                    \Arkit\App::$Logs->notice("Parameter name '$key' exceed the {$this->config['max_post_array_size']} elements.");
                    continue;
                }
                $list = [];
                $i = -1;
                foreach ($value as $val)
                {
                    $i++;
                    if (mb_detect_encoding($val) == 'UTF-8')
                    {
                        if (mb_strlen($val, 'UTF-8') > $max)
                            \Arkit\App::$Logs->notice("Parameter '$key [$i]' truncate to $max characters.");
                        $list[] = mb_substr($val, 0, $max);
                    }
                    elseif (mb_detect_encoding($val, 'ASCII'))
                    {
                        if (mb_strlen($val, 'ASCII') > $max)
                            \Arkit\App::$Logs->notice("Parameter '$key [$i]' truncate to $max characters.");
                        $list[] = utf8_encode(mb_substr($val, 0, $max));
                    }
                    else
                        continue;
                }

                $this->_post[$key] = $list;
            }

            unset($values[$key]);
            $i++;
        }
    }

    /**
     * Check if the url is valid
     * @returns bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * Check if the url is emply (have not levels)
     * @returns bool
     */
    public function isEmptyUrl(): bool
    {
        return (count($this->levels) == 0);
    }

    /**
     * Get the level of url given an 1-based index
     * @param int $level
     * @return string|null
     */
    public function getUrlLevel(int $level): ?string
    {
        if ($level <= 0 || $level > count($this->levels)) return null;
        return $this->levels[$level - 1];
    }

    /**
     * Get an array of the url levels
     * @return array
     */
    public function getUrlLevels(): array
    {
        return $this->levels;
    }

    /**
     * Get all parameters passed by url
     * @return array
     */
    public function getAllUrlParams(): array
    {
        return $this->_get;
    }

    /**
     * Get the value of a parameter passed by url
     * @param string $option
     * @return string|null
     */
    public function getUrlParam(string $option): ?string
    {
        if (isset($this->_get[$option]))
            return $this->_get[$option];
        return null;
    }

    /**
     * Get all fields sent by post
     * @return array
     */
    public function getAllPostParams(): array
    {
        return $this->_post;
    }

    /**
     * Get a post value given the name
     * @param string $param
     * @return mixed
     */
    public function getPostParam(string $param): mixed
    {
        if (isset($this->_post[$param]))
            return $this->_post[$param];

        return null;
    }

    /**
     * Check if a post value was sent
     * @param string $paramName
     * @return bool
     */
    public function isSetPostParam(string $paramName): bool
    {
        return isset($this->_post[$paramName]);
    }

    /**
     * Get a post value given the name
     * @param string $param
     * @return mixed
     */
    public function getFileParam(string $param): mixed
    {
        if (isset($_FILES[$param]))
            return $_FILES[$param];
        return null;
    }

    /**
     * Check if a post value was sent
     * @param string $paramName
     * @return bool
     */
    public function isSetFileParam(string $paramName): bool
    {
        return isset($_FILES[$paramName]);
    }

    /**
     * @return CookieStore
     */
    public function getCookies(): CookieStore
    {
        if (is_null($this->cookies))
            $this->cookies = CookieStore::fromServerRequest();

        return $this->cookies;
    }

    /**
     * Get the requested method
     * @return string
     */
    public function getRequestMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Get the requested url
     * @return null|string
     */
    public function getRequestUrl(): ?string
    {
        return $this->_url;
    }

    /**
     * @return string
     */
    public function getRequestedDomain(): string
    {
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * @return string
     */
    public function getRequestedProtocolAndDomain(): string
    {
        return (($this->isSecure()) ? 'https://' : 'http://'). $_SERVER['SERVER_NAME'];
    }

    /**
     * Test to see if a request contains the HTTP_X_REQUESTED_WITH header.
     */
    public function isAJAX(): bool
    {
        $xRequestWithHeader = $this->getHeader('X-Requested-With');
        return (!!$xRequestWithHeader && strtolower($xRequestWithHeader) === 'xmlhttprequest');
    }

    /**
     * Attempts to detect if the current connection is secure through
     * a few different methods.
     */
    public function isSecure(): bool
    {
        if (! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
            return true;

        $xForwardedProtoHeader = $this->getHeader('X-Forwarded-Proto');
        if (!empty($xForwardedProtoHeader) && strtolower($xForwardedProtoHeader) === 'https')
            return true;

        $frontEndHttpsHeader = $this->getHeader('Front-End-Https');
        return !empty($frontEndHttpsHeader) && strtolower($frontEndHttpsHeader) !== 'off';
    }
}