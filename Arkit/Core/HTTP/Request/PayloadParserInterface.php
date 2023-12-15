<?php
namespace Arkit\Core\HTTP\Request;

/**
 * Interface to define a class for parse the http request payload
 */
abstract class PayloadParserInterface
{

    /**
     * Array of values
     * 
     * @var array
     */
    protected array $values;

    /**
     * Array of headers
     * 
     * @var ?array
     */
    protected ?array $headers;


    /**
     * Constructor of the class
     */
    public function __construct()
    {
        $this->values = [];
        $this->headers = [];
    }

    /**
     * Set request headers
     * 
     * @param array $headers
     * @return void
     */
    public function setHeaders(array $headers) : void
    {
        $this->headers = $headers;
    }

    /**
     * Get a value given an index
     * 
     * @param string $paramName Index
     * 
     * @return mixed
     */
    public function get(string $paramName) : mixed
    {
        return $this->values[$paramName] ?? null;
    }

    /**
     * Get all values of the request
     * 
     * @return array
     */
    public function getAll() : array
    {
        return $this->values;
    }

    /**
     * Validate if an index exists
     * 
     * @param string $paramsName Index
     * @return bool
     */
    public function exists(string $paramsName) : bool
    {
        return isset($this->values[$paramsName]);
    }

    /**
     * Method to parse con content of the request payload
     * 
     * @param string $bodyContent Request payload content
     * @return void
     */
    public abstract function parse(string $bodyContent) : void;

}