<?php
namespace Arkit\Core\HTTP\Request;

/**
 *
 */
abstract class BodyParserInterface
{

    /**
     * @var array
     */
    protected array $values;

    /**
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
     * @param array $headers
     * @return void
     */
    public function setHeaders(array $headers) : void
    {
        $this->headers = $headers;
    }

    /**
     * @param string $paramName
     * @return mixed
     */
    public function get(string $paramName) : mixed
    {
        return $this->values[$paramName] ?? null;
    }

    /**
     * @return array
     */
    public function getAll() : array
    {
        return $this->values;
    }

    /**
     * @param string $paramsName
     * @return bool
     */
    public function exists(string $paramsName) : bool
    {
        return isset($this->values[$paramsName]);
    }

    /**
     * @param string $bodyContent Request body content
     * @return void
     */
    public abstract function parse(string $bodyContent) : void;

}