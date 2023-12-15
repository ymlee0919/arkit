<?php

namespace Arkit\Core\Control\Routing;

/**
 * @ignore add literal to node
 *
 * @param array $node
 * @param string $literal
 * @return mixed
 */
function &_node_add_literal(&$node, $literal): mixed
{
    if (!isset($node['literals'][$literal]))
        $node['literals'][$literal] = ['literals' => [], 'regexs' => [], 'rules' => []];
    return $node['literals'][$literal];
}

/**
 * @ignore add regex to node
 *
 * @param array $node
 * @param string $regex
 * @return mixed
 */
function &_node_add_regex(&$node, $regex): mixed
{
    if (!isset($node['regexs'][$regex]))
        $node['regexs'][$regex] = ['literals' => [], 'regexs' => [], 'rules' => []];

    return $node['regexs'][$regex];
}

/**
 * @ignore add rule to node
 *
 * @param array $node
 * @param string $rule
 * @return void
 */
function _node_add_rule(&$node, $rule): void
{
    $node['rules'][] = $rule;
}

/**
 * @ignore sort rules
 *
 * @param string $rule1
 * @param string $rule2
 * @return int
 */
function sortRules($rule1, $rule2): int
{
    $asterPos1 = strpos($rule1, '*');
    $asterPos2 = strpos($rule2, '*');
    
    if(false === $asterPos1 && false === $asterPos2)
        return 0;
    
    if(false === $asterPos1 && false !== $asterPos2)
        return -1;
    
    if(false !== $asterPos1 && false === $asterPos2)
        return 1;

    return 0;
}

/**
 * @ignore get a node given a key value
 *
 * @param array $node
 * @param string $value
 * @return mixed
 */
function _node_get(&$node, $value): mixed
{
    if (isset($node['literals'][$value]))
        return $node['literals'][$value];

    foreach ($node['regexs'] as $regex => &$node)
        if (!!preg_match_all('/^' . $regex . '$/', $value))
            return $node;

    return null;
}

/**
 * @ignore get node rules
 *
 * @param array $node
 * @return array
 */
function &_node_get_rules(&$node)
{
    if (is_null($node['rules']))
        $node['rules'] = [];

    return $node['rules'];
}

/**
 * Class to handle routes, implemented as a tree.
 */
final class RoutingTree
{

    /**
     * @var array
     */
    private array $root;

    /**
     * Constructor of the class RoutingTree
     */
    public function __construct()
    {
        $this->root = [
            'GET' => ['literals' => [], 'regexs' => [], 'rules' => []],
            'POST' => ['literals' => [], 'regexs' => [], 'rules' => []],
            'PUT' => ['literals' => [], 'regexs' => [], 'rules' => []],
            'DELETE' => ['literals' => [], 'regexs' => [], 'rules' => []]
        ];
    }


    /**
     * Get a root node given a request method
     * 
     * @param string $method Request method
     * @return array|null
     */
    public function &root(string $method): ?array
    {
        $method = strtoupper($method);

        if (!isset($this->root[$method]))
            $this->root[$method] = [];

        return $this->root[$method];
    }


    /**
     * Add a routing rule
     * 
     * @param string $method Http method
     * @param string $id Rule id
     * @param string $urlRequest Url
     * @param null|array $constraints (Optional) Constraints of url parameters
     */
    public function addRoutingRule(string $method, string $id, string $urlRequest, array $constraints = null): void
    {
        // Divide the url by levels
        $parts = explode('?', $urlRequest);
        $parts = explode('/', $parts[0]);
        array_shift($parts);

        // Surf into the tree and insert the rule
        $node = &$this->root($method);
        if (!isset($parts[0]) || !isset($parts[0][1])) {
            _node_add_rule($node, $id);
            unset($parts);
            return;
        }

        reset($parts);
        foreach ($parts as $item) {
            if ('{' == $item[0]) {
                $tag = substr($item, 1, -1);
                if (!is_null($constraints) && isset($constraints[$tag]))
                    $node = &_node_add_regex($node, $constraints[$tag]);
                else
                    $node = &_node_add_regex($node, '([0-9a-zA-Z-]+)');
            } else
                $node = &_node_add_literal($node, $item);
        }

        _node_add_rule($node, $id);
        unset($parts);
    }


    /**
     * Get list of rules given a request
     * 
     * @param string $method Http request method
     * @param string $urlRequest Url requested
     * @return array|null List of routing rules
     */
    public function getRoutingRules(string $method, string $urlRequest): ?array
    {
        // Divide the url by levels
        $parts = explode('?', $urlRequest);
        $parts = explode('/', $parts[0]);
        array_shift($parts);

        // Find the node
        $node = $this->root($method);
        if(empty($node))
            return null;

        if (!isset($parts[0]) || !isset($parts[0][1])) {
            unset($parts);
            return $node['rules'];
        }

        reset($parts);
        foreach ($parts as $item) {
            $node = _node_get($node, $item);
            if (is_null($node)) return null;
        }
        unset($parts);

        return $node['rules'];
    }
}