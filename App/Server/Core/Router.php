<?php

function &_node_add_literal(&$node, $literal) : mixed
{
    if(!isset($node['literals'][$literal]))
        $node['literals'][$literal] = [ 'literals' => [], 'regexs' => [], 'rules' => [] ];
    return $node['literals'][$literal];
}

function &_node_add_regex(&$node, $regex) : mixed
{
    if(!isset($node['regexs'][$regex]))
        $node['regexs'][$regex] = [ 'literals' => [], 'regexs' => [], 'rules' => [] ];

    return $node['regexs'][$regex];
}

function _node_add_rule(&$node, $rule): void
{
    $node['rules'][] = $rule;
}

function _node_get(&$node, $value) : mixed
{
    if(isset($node['literals'][$value]))
        return $node['literals'][$value];

    foreach($node['regexs'] as $regex => &$node)
        if(!!preg_match_all('/^'   . $regex . '$/', $value))
            return $node;

    return null;
}

function &_node_get_rules(&$node)
{
    if(is_null($node['rules']))
        $node['rules'] = null;

    return $node['rules'];
}


/**
 * Class RoutingTree
 */
final class RoutingTree
{

    /**
     * @var array
     */
    private array $root;

    /**
     * Constructor of the class RouterTree
     */
    public function __construct()
    {
        $this->root = [
            'GET' => [ 'literals' => [], 'regexs' => [], 'rules' => [] ],
            'POST' => [ 'literals' => [], 'regexs' => [], 'rules' => [] ],
            'PUT' => [ 'literals' => [], 'regexs' => [], 'rules' => [] ],
            'DELETE' => [ 'literals' => [], 'regexs' => [], 'rules' => [] ]
        ];
    }


    /**
     * @param string $method
     * @return array|null
     */
    public function &root(string $method): ?array
    {
        $method = strtoupper($method);

        if(isset($this->root[$method]))
            $this->root[$method] = [];

        return $this->root[$method];
    }


    /**
     * @param string $method
     * @param string $id
     * @param string $urlRequest
     * @param null|array $constrains
     */
    public function addRoutingRule(string $method, string $id, string $urlRequest, array $constrains = null) : void
    {
        // Divide the url by levels
        $parts = explode('?', $urlRequest);
        $parts = explode('/', $parts[0]);
        array_shift($parts);

        // Surf into the tree and insert the rule
        $node = &$this->root($method);
        if(!isset($parts[0]) || !isset($parts[0][1]))
        {
            _node_add_rule($node, $id);
            unset($parts);
            return;
        }

        reset($parts);
        foreach($parts as $item)
        {
            if('{' == $item[0])
            {
                $tag = substr($item, 1, -1);
                if(!is_null($constrains) && isset($constrains[$tag]))
                    $node = &_node_add_regex($node, $constrains[$tag]);
                else
                    $node = &_node_add_regex($node, '([0-9a-zA-Z-]+)');
            }
            else
                $node = &_node_add_literal($node, $item);
        }

        _node_add_rule($node, $id);
        unset($parts);
    }


    /**
     * @param string $method
     * @param string $urlRequest
     * @return array|null
     */
    public function getRoutingRules(string $method, string $urlRequest) : ?array
    {
        // Divide the url by levels
        $parts = explode('?', $urlRequest);
        $parts = explode('/', $parts[0]);
        array_shift($parts);

        // Find the node
        $node = $this->root($method);

        if(!isset($parts[0]) || !isset($parts[0][1]))
        {
            unset($parts);
            return $node['rules'];
        }

        reset($parts);
        foreach($parts as $item)
        {
            $node = _node_get($node, $item);
            if(is_null($node)) return null;
        }
        unset($parts);

        return $node['rules'];
    }
}


/**
 * Class Router
 */
final class Router {

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
     * @returns void
     */
    public function setSign(string $str) : void
    {
        $this->sign = $str;
    }

    /**
     * @return string
     */
    public function getSign() : string
    {
        return $this->sign;
    }

    /**
     * @param array $rules
     * @returns void
     */
    public function setRules(array &$rules) : void
    {
        $this->hash = $rules;
        foreach($this->hash as $id => $rule)
            $this->tree->addRoutingRule($rule['method'], $id, $rule['url'], (isset($rule['constrains'])) ? $rule['constrains'] : null );
    }

    /**
     * Return the set of rules that match with the given url and request method
     * @param string $url
     * @param string $method
     * @return ?array
     */
    public function route(string $url, string $method) : ?array
    {
        $rules = $this->tree->getRoutingRules($method, $url);
        if(is_null($rules)) return null;

        // Find the matched rule id
        $match_id = null;
        $parameters = [];

        foreach($rules as $id)
        {
            $rule = &$this->hash[$id];

            $route_url = &$rule['url'];

            // Check url with allowed parameters, ONLY FOR URL WITHOUT OPTIONS
            if(!str_contains($route_url, '?') && str_contains($url, '?'))
            {
                if(!isset($rule['allow'])) continue;

                $valid = true;
                foreach (App::$Request->namesGet() as $option)
                    $valid = (in_array($option,$rule['allow']) && $valid);
                if(!$valid) continue;
                else {
                    $match_id = $id;
                    break;
                }
            }

            // Check literal rule
            if(!str_contains($route_url, '{') && !str_contains($route_url, '*'))
            {
                if(0 == strcmp($url, $route_url))
                {
                    $match_id = $id;
                    break;
                }
            } else {
                $end = (str_ends_with($route_url, '*')) ? '/' : '$/';

                if(str_contains($route_url, '{'))
                {
                    // Get the url parameters
                    $params = [];
                    preg_match_all('/{[^}]*}/', $route_url, $params);

                    // Build the pattern of the url
                    $pattern_url = $route_url;

                    // Get the position of the ? sign
                    $ptr = strpos($pattern_url, '?');
                    if($ptr === false) $ptr = strlen($pattern_url) + 1;
                    else $pattern_url = str_replace('?', '\?', $pattern_url);

                    // Replace each parameter into the pattern url
                    foreach($params[0] as $param)
                    {
                        // Check if the parameter is into the constrains
                        $p = substr($param, 1, -1);
                        if(isset($rule['constrains']) && isset($rule['constrains'][$p]))
                            $pattern_url = str_replace($param, $rule['constrains'][$p], $pattern_url);

                        // Replace the parameter by the pattern, taking the position into the url
                        // A part of the root is different to a parameter by get
                        if(strpos($pattern_url, $param) < $ptr)
                            $pattern_url = str_replace($param, '([0-9a-zA-Z-]+)', $pattern_url);
                        else
                            $pattern_url = str_replace($param, '([@A-Za-z0-9\._-]+)', $pattern_url);
                    }

                    // Scape the / character
                    $pattern_url = str_replace('/', "\/", $pattern_url);

                    // Get each parameter of the given client request
                    $url_params = [];
                    // Try to match the url
                    $success = preg_match_all('/^' . $pattern_url . $end, $url, $url_params);
                    // If not match, get the next url
                    if(!$success) continue;

                    // Collect each parameter
                    $c = count($params[0]);
                    for($i = 0; $i < $c; ++$i)
                        $parameters[substr($params[0][$i],1,-1)] = $url_params[$i + 1][0];

                    $match_id = $id;
                    break;
                }
            }
        }

        if(is_null($match_id)) return null;

        return ['id' => $match_id, 'callback' => $this->hash[$match_id]['callback'], 'parameters' => $parameters];
    }

    /**
     * @param string $id
     * @param ?array $params
     * @return string
     */
    public function buildUrl(string $id, ?array $params = null) : string
    {
        if($params == null)
            return $this->hash[$id]['url'];

        $parameters = [];
        foreach ($params as $key => $value)
            $parameters['{' . $key . '}'] = $value;

        $url = $this->hash[$id]['url'];
        $url = strtr($url, $parameters);

        if($url[strlen($url) - 1] == '*')
            $url = substr($url, 0, -1);

        unset($parameters);

        return $url;
    }
}