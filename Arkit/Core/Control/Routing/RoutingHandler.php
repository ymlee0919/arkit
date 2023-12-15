<?php

namespace Arkit\Core\Control\Routing;
/**
 * Class to encapsulate the callback for routing result. Store the ruleId, handler and parameters
 */
class RoutingHandler
{
    /**
     * @var array
     */
    private array $info;

    /**
     * @param string $ruleId Id of the rule
     * @param string $handler Handler for the request
     * @param array|null $parameters (Optional) Parameters taken from the url
     */
    public function __construct(string $ruleId, string $handler, ?array $parameters = null)
    {
        $this->info = [
            'Id' => $ruleId,
            'Handler' => $handler,
            'Parameters' => $parameters
        ];
    }

    /**
     * Get the rule Id
     * 
     * @return string
     */
    public function getRuleId(): string
    {
        return $this->info['Id'];
    }

    /**
     * Get the function handler
     * 
     * @return string
     */
    public function getHandler(): string
    {
        return $this->info['Handler'];
    }

    /**
     * Get parameters taken from the url
     * @return array|null
     */
    public function getParameters(): ?array
    {
        return $this->info['Parameters'];
    }

    /**
     * @return bool Indicate if have parameters
     */
    public function haveParameters(): bool
    {
        return count($this->info['Parameters']) > 0;
    }
}