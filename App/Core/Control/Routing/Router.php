<?php

namespace Arkit\Core\Control\Routing;

/**
 * Class Router
 */
final class Router implements RouterInterface
{

    /**
     * @var RoutingTree
     */
    private RoutingTree $tree;

    /**
     * @var ?array
     */
    private ?array $hash = null;


    /**
     * @var string
     */
    private string $sign = '';

    /**
     *
     */
    public function __construct()
    {
        $this->tree = new RoutingTree();
    }

    /**
     * @param string $str
     * @return void
     */
    public function setSign(string $str): void
    {
        $this->sign = $str;
    }

    /**
     * @return string
     */
    public function getSign(): string
    {
        return $this->sign;
    }

    /**
     * {@inheritDoc}
     */
    public function setRules(array &$rules): void
    {
        $this->hash = $rules;
        foreach ($this->hash as $id => $rule)
            $this->tree->addRoutingRule($rule['method'], $id, $rule['url'], (isset($rule['constraints'])) ? $rule['constraints'] : null);
    }

    /**
     * {@inheritDoc}
     */
    public function getRule(string $ruleId): ?RoutingRule
    {
        if (isset($this->hash[$ruleId]))
            return RoutingRule::fromArray($ruleId, $this->hash[$ruleId]);

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function setRule(string $ruleId, array &$rule): void
    {
        $this->tree->addRoutingRule($rule['method'], $ruleId, $rule['url'], (isset($rule['constraints'])) ? $rule['constraints'] : null);
    }

    /**
     * Get score of the routing url given the number of parameters
     *
     * @param string $routingUrl
     * @return int
     */
    private function getScore(string $routingUrl) : int
    {
        return substr_count($routingUrl,'{') * 2 + substr_count($routingUrl,'?') * 3;
    }

    /**
     * Extract parameters from the requestUrl given the routing url
     *
     * @param string $routingUrl
     * @param string $requestedUrl
     * @return array|bool
     */
    private function extractParameters(string $routingUrl, string $requestedUrl) : array|bool
    {
        $result = [];
    
        // If not parameters, return empty array
        if(false === strpos($routingUrl,'{'))
            return $result;
    
        $routingParts = parse_url($routingUrl);
        $requestParts = parse_url($requestedUrl);
    
        //// Extract parameters from path ------------------------
        $routingPath = $routingParts['path'];
        $requestPath = $requestParts['path'];
    
        // If the path have parameters
        if(false !== strpos($routingPath,'{'))
        {
            // Split the url by levels
            $routingLevels = explode('/', $routingPath);
            $requestLevels = explode('/', $requestPath);
            $levels = count($routingLevels);
            
            // Iterate each level
            for($i = 0; $i < $levels; $i++)
            {
                // If current routing level have parameter, extract it
                if(false !== strpos($routingLevels[$i],'{'))
                {
                    $outputParamName = substr($routingLevels[$i], 1, -1);
                    $result[$outputParamName] = $requestLevels[$i];
                }
            }
    
            unset($routingLevels);
            unset($requestLevels);
            unset($levels);
        }
    
        unset($routingPath);
        unset($requestPath);
    
        // If no query in routing, return the current parameters
        if(!isset($routingParts['query']))
            return $result;
        
        // At this point the routing path have query
        // If request have not parameters, then return false
        if(!isset($requestParts['query']))
            return false;
    
        //// Extract parameters from query ------------------------
        $routingQuery = $routingParts['query'];
        $requestQuery = $requestParts['query'];
    
        // If routing query have parameters
        if(false !== strpos($routingQuery,'{'))
        {
            $routingQueryParams = [];
            parse_str($routingQuery, $routingQueryParams);
            $requestQueryParams = [];
            parse_str($requestQuery, $requestQueryParams);
    
            foreach($routingQueryParams as $queryParamName => $queryParamValue)
            {
                // If this parameter is not sent into the request, the request is not valid
                if(!isset($requestQueryParams[$queryParamName]))
                    return false;
    
                // If the value of have a url parameter
                if(false !== strpos($queryParamValue,'{'))
                {
                    $outputParamName = substr($queryParamValue, 1, -1);
                    $result[$outputParamName] = $requestQueryParams[$queryParamName];
                }
            }
        }
    
        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function route(string $url, string $method): ?RoutingHandler
    {
        $rules = $this->tree->getRoutingRules($method, $url);
        if (is_null($rules)) return null;

        // Find the matched rule id
        $match_id = null;
        $parameters = [];
        $maxScore = -1;

        foreach ($rules as $id)
        {
            $rule = &$this->hash[$id];
            $route_url = &$rule['url'];

            // Get the score of url
            $score = $this->getScore($route_url);
            
            // If the escore is not bigger, get the other rule
            if($score < $maxScore)
                continue;
            
            // Extract the parameters if score > 0
            if($score > 0)
            {
                $requestParams = $this->extractParameters($route_url, $url);
                if(false === $requestParams)
                    continue;
                
                $parameters = $requestParams;
            }

            $match_id = $id;
            $maxScore = $score;
        }

        if (is_null($match_id)) return null;

        return new RoutingHandler($match_id, $this->hash[$match_id]['handler'], $parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function buildUrl(string $ruleId, ?array $params = null): string
    {
        if (!isset($this->hash[$ruleId]))
            throw new \InvalidArgumentException('The routing id "' . $ruleId . '" do not exists into the current router');

        if ($params == null)
            return $this->hash[$ruleId]['url'];

        $parameters = [];
        foreach ($params as $key => $value)
            $parameters['{' . $key . '}'] = $value;

        $url = $this->hash[$ruleId]['url'];
        $url = strtr($url, $parameters);

        if ($url[strlen($url) - 1] == '*')
            $url = substr($url, 0, -1);

        unset($parameters);

        return $url;
    }
}