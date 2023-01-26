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
     * @returns void
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
     * {@inheritDoc}
     */
    public function route(string $url, string $method): ?RoutingHandler
    {
        $rules = $this->tree->getRoutingRules($method, $url);
        if (is_null($rules)) return null;

        // Find the matched rule id
        $match_id = null;
        $parameters = [];

        foreach ($rules as $id) {
            $rule = &$this->hash[$id];

            $route_url = &$rule['url'];

            // Check url with allowed parameters, ONLY FOR URL WITHOUT OPTIONS
            if (!str_contains($route_url, '?') && str_contains($url, '?')) {
                if (!isset($rule['allow'])) continue;

                $valid = true;
                foreach (\Arkit\App::$Request->getAllUrlParams() as $option)
                    $valid = (in_array($option, $rule['allow']) && $valid);
                if (!$valid) continue;
                else {
                    $match_id = $id;
                    break;
                }
            }

            // Check literal rule
            if (!str_contains($route_url, '{') && !str_contains($route_url, '*')) {
                if (0 == strcmp($url, $route_url)) {
                    $match_id = $id;
                    break;
                }
            } else {
                $end = (str_ends_with($route_url, '*')) ? '/' : '$/';

                if (str_contains($route_url, '{')) {
                    // Get the url parameters
                    $params = [];
                    preg_match_all('/{[^}]*}/', $route_url, $params);

                    // Build the pattern of the url
                    $pattern_url = $route_url;

                    // Get the position of the ? sign
                    $ptr = strpos($pattern_url, '?');
                    if ($ptr === false) $ptr = strlen($pattern_url) + 1;
                    else $pattern_url = str_replace('?', '\?', $pattern_url);

                    // Replace each parameter into the pattern url
                    foreach ($params[0] as $param) {
                        // Check if the parameter is into the constraint
                        $p = substr($param, 1, -1);
                        if (isset($rule['constraint']) && isset($rule['constraint'][$p]))
                            $pattern_url = str_replace($param, $rule['constraint'][$p], $pattern_url);

                        // Replace the parameter by the pattern, taking the position into the url
                        // A part of the root is different to a parameter by get
                        if (strpos($pattern_url, $param) < $ptr)
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
                    if (!$success) continue;

                    // Collect each parameter
                    $c = count($params[0]);
                    for ($i = 0; $i < $c; ++$i)
                        $parameters[substr($params[0][$i], 1, -1)] = $url_params[$i + 1][0];

                    $match_id = $id;
                    break;
                }
            }
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