<?php

/**
 * Class to encapsulate the callback for routing result
 */
class RoutingCallback
{
    /**
     * @var array
     */
    private array $info;

    /**
     * @param string $ruleId
     * @param string $callback
     * @param array|null $parameters
     * @param string|null $task
     */
    public function __construct(string $ruleId, string $callback, ?array $parameters = null, ?string $task = null)
    {
        $this->info = [
            'Id' => $ruleId,
            'Callback' => $callback,
            'Parameters' => $parameters
        ];

        if(!is_null($task))
            $this->info['Task'] = $task;
    }

    /**
     * @return string
     */
    public function getRuleId() : string
    {
        return $this->info['Id'];
    }

    /**
     * @return string
     */
    public function getCallback() : string
    {
        return $this->info['Callback'];
    }

    /**
     * @return ?string
     */
    public function getTask() : ?string
    {
        return $this->info['Task'] ?? null;
    }

    /**
     * @return array|null
     */
    public function getParameters() : ?array
    {
        return $this->info['Parameters'];
    }

    /**
     * @return bool Indicate if have parameters
     */
    public function haveParameters() : bool
    {
        return count($this->info['Parameters']) > 0;
    }
}