<?php

namespace Arkit\Helper\Access;
/**
 *
 */
class AccessTree
{

    /**
     * This is a tree with 2 main branches
     *  - TASKS for allowed tasks
     *  - ROUTES for allowed routes
     *
     * @var array
     */
    private array $tree;


    /**
     * @var string
     */
    private string $sign;

    /**
     *
     */
    public function __construct()
    {
        $this->tree = [];
    }


    /**
     * @param array $roles
     * @param array $tasks
     * @return void
     * @throws \Exception
     */
    public function build(array $roles, array $tasks): void
    {
        foreach ($roles as $roleName => $roleInfo) {
            if (isset($this->tree[$roleName]))
                throw new \Exception('The role ' . $roleName . ' is two defined');

            if (!isset($roleInfo['tasks']))
                throw new \Exception('The role ' . $roleName . ' have not tasks associated');

            // Build the rol branch
            $this->tree[$roleName] = [
                '<TASKS>' => [],
                '<ROUTES>' => []
            ];

            // Prepare each ptr
            $tasksPtr = &$this->tree[$roleName]['<TASKS>'];
            $routesPtr = &$this->tree[$roleName]['<ROUTES>'];

            // Iterate each task
            foreach ($roleInfo['tasks'] as $task) {
                // Disaggregate the task
                $taskList = $this->disaggregate($task);

                foreach ($taskList as $taskId) {
                    // Add the rule
                    $this->addPath($tasksPtr, $taskId);

                    if (!isset($tasks[$taskId]))
                        throw new \Exception('The task ' . $roleName . '::' . $taskId . ' do not exists');

                    if (!isset($tasks[$taskId]['routes']))
                        throw new \Exception('The task ' . $roleName . '::' . $taskId . ' have not routes');

                    foreach ($tasks[$taskId]['routes'] as $route)
                        $this->addPath($routesPtr, $route);

                } // End of: foreach ($taskList as $taskId)

            } // End of: foreach ($roleInfo['tasks'] as $task)

        } // End of foreach ($roles as $roleName => $roleInfo)

        echo '<pre>';
        var_dump($this->tree);
    }

    /**
     * @param string $item
     * @return array|string[]
     */
    private function disaggregate(string $item): array
    {
        $result = [];

        // Find the position of the '[' character
        $index = strpos($item, '[');

        if (false === $index)
            return [$item];

        // Get the prefix before the '.[' characters
        $prefix = substr($item, 0, $index - 1);

        // Get the rest of the item and exclude the ']'character
        $rest = substr($item, $index + 1, -1);

        // Split the rest by the ';' character
        $parts = explode(';', $rest);

        // Add each item to the result
        foreach ($parts as $part)
            $result[] = $prefix . '.' . $part;

        return $result;
    }

    /**
     * Recursive method for add rule by part
     *
     * @param array $nodePtr
     * @param string $path
     * @return void
     */
    private function addPath(array &$nodePtr, string $path): void
    {
        if ('[' === $path[0]) {
            $parts = explode(';', substr($path, 1, -1));
            foreach ($parts as $part)
                $nodePtr[] = $part;
        } else {
            $index = strpos($path, '.');
            if (false === $index)
                $nodePtr[] = $path;
            else {
                $step = substr($path, 0, $index);
                $rest = substr($path, $index + 1);
                if (!isset($nodePtr[$step]))
                    $nodePtr[$step] = [];
                $this->addPath($nodePtr[$step], $rest);
            }
        }
    }

    /**
     * @param string $role
     * @param string $task
     * @return bool
     */
    public function haveAccess(string $role, string $task): bool
    {
        if (!isset($this->tree[$role]))
            throw new \InvalidArgumentException('The rol "' . $role . '" do not exists.');

        if ('*' === $this->tree[$role]['<TASKS>'])
            return true;

        $ptr = &$this->tree[$role]['<TASKS>'];

        return $this->existsPath($ptr, $task);

    }

    /**
     * @param string $role
     * @param string $route
     * @return bool
     */
    public function canInvoke(string $role, string $route): bool
    {
        if (!isset($this->tree[$role]))
            throw new \InvalidArgumentException('The rol "' . $role . '" do not exists.');

        if ('*' === $this->tree[$role]['<ROUTES>'])
            return true;

        $ptr = &$this->tree[$role]['<ROUTES>'];

        return $this->existsPath($ptr, $route);
    }

    /**
     * @param array $rootNode
     * @param string $path
     * @return bool
     */
    private function existsPath(array &$rootNode, string $path): bool
    {
        $steps = explode('.', $path);
        $c = count($steps);
        for ($i = 0; $i < $c; $i++) {
            $step = $steps[$i];

            if (in_array($step, $rootNode) && $i + 1 == $c)
                return true;
            elseif (isset($ptr[$step]))
                $ptr = &$ptr[$step];
            else
                return false;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getSign(): string
    {
        return $this->sign;
    }

    /**
     * @param string $sign
     */
    public function setSign(string $sign): void
    {
        $this->sign = $sign;
    }

}