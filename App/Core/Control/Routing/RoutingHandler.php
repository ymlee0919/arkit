<?php

namespace Arkit\Core\Control\Routing;
/**
 * Class to encapsulate the callback for routing result
 */
class RoutingHandler
{
    /**
     * @var array
     */
    private array $info;

    /**
     * @param string $ruleId
     * @param string $handler
     * @param array|null $parameters
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
     * @return string
     */
    public function getRuleId(): string
    {
        return $this->info['Id'];
    }

    /**
     * @return string
     */
    public function getHandler(): string
    {
        return $this->info['Handler'];
    }

    /**
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