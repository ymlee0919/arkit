<?php

namespace Arkit\Core\Control\Routing;

function &_node_add_literal(&$node, $literal): mixed
{
    if (!isset($node['literals'][$literal]))
        $node['literals'][$literal] = ['literals' => [], 'regexs' => [], 'rules' => []];
    return $node['literals'][$literal];
}

function &_node_add_regex(&$node, $regex): mixed
{
    if (!isset($node['regexs'][$regex]))
        $node['regexs'][$regex] = ['literals' => [], 'regexs' => [], 'rules' => []];

    return $node['regexs'][$regex];
}

function _node_add_rule(&$node, $rule): void
{
    $node['rules'][] = $rule;
}

function _node_get(&$node, $value): mixed
{
    if (isset($node['literals'][$value]))
        return $node['literals'][$value];

    foreach ($node['regexs'] as $regex => &$node)
        if (!!preg_match_all('/^' . $regex . '$/', $value))
            return $node;

    return null;
}

function &_node_get_rules(&$node)
{
    if (is_null($node['rules']))
        $node['rules'] = [];

    return $node['rules'];
}

/**
 * Class RoutingTree, used by the current Router
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
            'GET' => ['literals' => [], 'regexs' => [], 'rules' => []],
            'POST' => ['literals' => [], 'regexs' => [], 'rules' => []],
            'PUT' => ['literals' => [], 'regexs' => [], 'rules' => []],
            'DELETE' => ['literals' => [], 'regexs' => [], 'rules' => []]
        ];
    }


    /**
     * @param string $method
     * @return array|null
     */
    public function &root(string $method): ?array
    {
        $method = strtoupper($method);

        if (!isset($this->root[$method]))
            $this->root[$method] = ['literals' => [], 'regexs' => [], 'rules' => []];

        return $this->root[$method];
    }


    /**
     * @param string $method
     * @param string $id
     * @param string $urlRequest
     * @param null|array $constraints
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
     * @param string $method
     * @param string $urlRequest
     * @return array|null
     */
    public function getRoutingRules(string $method, string $urlRequest): ?array
    {
        // Divide the url by levels
        $parts = explode('?', $urlRequest);
        $parts = explode('/', $parts[0]);
        array_shift($parts);

        // Find the node
        $node = $this->root($method);

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