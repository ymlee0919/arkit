<?php

/**
 * Class for route a domain
 */
class DomainRouter
{
    /**
     * @var array
     */
    private array $config;

    /**
     * @param array $routerConfig
     */
    public function __construct(array &$routerConfig)
    {
        $this->config = $routerConfig;
    }

    /**
     * @param Request $request
     * @return string|bool
     */
    public function route(Request &$request) : string|bool
    {
        $domain = $request->getRequestedDomain();

        if(!isset($this->config[$domain]))
            return false;

        $router = &$this->config[$domain];

        if(is_array($router)) // For internationalization (eg: http://domain.com/en/landing-page)
        {
            if(!$request->isEmptyUrl())
            {
                $item = $request->getUrlLevel(1);

                if(!isset($router[$item]))
                    return false;

                $router = &$router[$item];
            }
            else
                $router = &$router['_empty'];
        }

        return $router;
    }
}