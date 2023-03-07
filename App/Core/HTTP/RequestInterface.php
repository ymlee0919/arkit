<?php

namespace Arkit\Core\HTTP;

use Arkit\Core\Persistence\Client\CookieStore;
use Arkit\Core\HTTP\Request\BodyParserInterface;

/**
 * Class RequestInterface
 */
interface RequestInterface
{
    /**
     * @param array $config
     * @return void
     */
    public function init(array &$config) : void;

    /**
     * Set parser for the payload request
     * @param BodyParserInterface $bodyParser
     * @return void
     */
    public function setBodyParser(BodyParserInterface $bodyParser) : void;

    /**
     * Get value of header given the name
     * @param string $headerName
     * @return string|null
     */
    public function getHeader(string $headerName) : ?string;

    /**
     * Get all headers
     * @return array
     */
    public function getAllHeaders() : array;

    /**
     * Validate the request given some rules
     * @return bool
     */
    public function validate(): bool;

    /**
     * @return void
     */
    public function processBody(): void;

    /**
     * Check if the url is valid
     * @return bool
     */
    public function isValid(): bool;

    /**
     * Check if the url is emply (have not levels)
     * @return bool
     */
    public function isEmptyUrl(): bool;

    /**
     * Get the level of url given an 1-based index
     * @param int $level
     * @return string|null
     */
    public function getUrlLevel(int $level): ?string;

    /**
     * Get an array of the url levels
     * @return array
     */
    public function getUrlLevels(): array;

    /**
     * Get all parameters passed by url
     * @return array
     */
    public function getAllUrlParams(): array;

    /**
     * Get the value of a parameter passed by url
     * @param string $option
     * @return string|null
     */
    public function getUrlParam(string $option): ?string;

    /**
     * Get all fields sent by post
     * @return array
     */
    public function getAllPostParams(): array;

    /**
     * Get a post value given the name
     * @param string $param
     * @return mixed
     */
    public function getPostParam(string $param): mixed;

    /**
     * Check if a post value was sent
     * @param string $paramName
     * @return bool
     */
    public function isSetPostParam(string $paramName): bool;

    /**
     * Get a post value given the name
     * @param string $param
     * @return mixed
     */
    public function getFileParam(string $param): mixed;

    /**
     * Check if a post value was sent
     * @param string $paramName
     * @return bool
     */
    public function isSetFileParam(string $paramName): bool;

    /**
     * @return CookieStore
     */
    public function getCookies(): CookieStore;

    /**
     * Get the requested method
     * @return string
     */
    public function getRequestMethod(): string;

    /**
     * Get the requested url
     * @return null|string
     */
    public function getRequestUrl(): ?string;

    /**
     * @return string
     */
    public function getRequestedDomain(): string;

    /**
     * @return string
     */
    public function getRequestedProtocolAndDomain(): string;

    /**
     * Test to see if a request contains the HTTP_X_REQUESTED_WITH header.
     */
    public function isAJAX(): bool;

    /**
     * Attempts to detect if the current connection is secure through
     * a few different methods.
     */
    public function isSecure(): bool;
}