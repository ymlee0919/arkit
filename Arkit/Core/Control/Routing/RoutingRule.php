<?php

namespace Arkit\Core\Control\Routing;
/**
 * Class to encapsulate a rule of routing
 */
class RoutingRule
{
    /**
     * Id of the rule
     * @var string
     */
    private string $ID;

    /**
     * Information of the rule
     * @var array
     */
    private array $info;


    /**
     * Build a Routing rule given an Id and an array with the information
     * @param string $ruleId
     * @param array $info
     * @return static
     */
    public static function fromArray(string $ruleId, array $info): self
    {
        return new RoutingRule(
            $ruleId,
            $info['url'],
            $info['method'],
            $info['callback'],
            (isset($info['task'])) ? $info['task'] : null,
            (isset($info['constraints'])) ? $info['constraints'] : null,
            (isset($info['allow'])) ? $info['allow'] : null
        );
    }

    /**
     * @param string $ruleId Rule ID
     * @param string $url General url format
     * @param string $method Method for request: 'GET', 'POST', etc.
     * @param string $callback Callback to handle the request [Directory.directory..file/Class::function]
     * @param ?string $task Task: For control access
     * @param array|null $constraints (Optional) Constraints for url parameters
     * @param array|null $allowedParameters (Optional) Optionals parameters for '*' ending url
     */
    public function __construct(string $ruleId, string $url, string $method, string $callback, ?string $task = null, ?array $constraints = null, ?array $allowedParameters = null)
    {
        $this->ID = $ruleId;

        $this->info = [
            'URL' => $url,
            'Method' => $method,
            'Callback' => $callback
        ];

        if (!is_null($task))
            $this->info['Task'] = $task;

        if (is_array($constraints))
            $this->info['Constraints'] = $constraints;

        if (is_array($allowedParameters))
            $this->info['Allowed'] = $allowedParameters;
    }

    /**
     * Get the rule ID
     * @return string
     */
    public function getId(): string
    {
        return $this->ID;
    }

    /**
     * Get the url format
     * @return string
     */
    public function getUrl(): string
    {
        return $this->info['URL'];
    }

    /**
     * Get the requested method
     *
     * @return string
     */
    public function getRequestMethod(): string
    {
        return $this->info['Method'];
    }

    /**
     * Get the callback to handle the request
     * @return string
     */
    public function getCallback(): string
    {
        return $this->info['Callback'];
    }

    /**
     * Get the task
     * @return string|null
     */
    public function getTask(): ?string
    {
        return $this->info['Task'] ?? null;
    }

    /**
     * Get the constraints for url parameters.
     * If exists, return and associative array when the key is the name of the parameter
     * and the value is the regular expression to validate the parameter
     * @return array|null
     */
    public function getConstraints(): ?array
    {
        return $this->info['Constraints'] ?? null;
    }

    /**
     * Return the constraint for the given url parameter, it is a regular expression
     *
     * @param string $paramName Name of the parameter
     * @return string|null
     */
    public function getConstraint(string $paramName): ?string
    {
        return (isset($this->info['Constraints']) && isset($this->info['Constraints'][$paramName]))
            ? $this->info['Constraints'][$paramName]
            : null;
    }

    /**
     * Return the list of get allowed parameters when the url end with '*'
     *
     * @return array|null
     */
    public function getAllowedParameters(): ?array
    {
        return $this->info['Allowed'] ?? null;
    }
}