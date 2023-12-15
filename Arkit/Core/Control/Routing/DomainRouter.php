<?php

namespace Arkit\Core\Control\Routing;

/**
 * Router class for domain
 */
class DomainRouter
{
    /**
     * Configuration
     * 
     * @var array
     */
    private array $config;

    /**
     * Constructor of the class.
     * 
     * Router configuration should be an array where keys are the domain or subdmains and the associated values are the url routers that handle the rest of the request.
     * domain.com: /Doamin/_config/router.yaml
     * 
     * For internatinalization, the first level of the url is used.
     * mydomain.com:
     *   es : /MyDomain/_config/router.es.yaml
     *   en : /MyDomain/_config/router.en.yaml
     * 
     * So, when the request is http://mydomain.com/en/rest/of/url it takes first 'mydomain.com' and then 'en'.
     * 
     * @param array $routerConfig Router configuration
     */
    public function __construct(array &$routerConfig)
    {
        $this->config = $routerConfig;
    }

    /**
     * Return a url router file defintion given a request.
     * 
     * @param \Arkit\Core\HTTP\RequestInterface $request Request from client
     * 
     * @return string|bool If can handle the request, return a router file definition, false otherwise.
     */
    public function route(\Arkit\Core\HTTP\RequestInterface &$request): string|bool
    {
        $domain = $request->getRequestedDomain();

        if (!isset($this->config[$domain]))
            return false;

        $router = &$this->config[$domain];

        if (is_array($router)) // For internationalization (eg: http://domain.com/en/landing-page)
        {
            if (!$request->isEmptyUrl()) {
                $item = $request->getUrlLevel(1);

                if (!isset($router[$item]))
                    return false;

                $router = &$router[$item];
            } else
                $router = &$router['_empty'];
        }

        return $router;
    }
}