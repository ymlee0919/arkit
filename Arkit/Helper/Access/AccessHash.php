<?PHP 

namespace Arkit\Helper\Access;

/**
 * Internal class for access control using hash
 */
class AccessHash
{
    /**
     * Hash to store access tree
     * @var array
     */
    private array $hash;

    /**
     * Array to store the roles and tasks
     * @var array
     */
    private array $roles;


    /**
     * @var string
     */
    private string $sign;

    /**
     *
     */
    public function __construct()
    {
        $this->hash = [];
    }


    /**
     * @param array $roles
     * @param array $tasks
     * @return void
     * @throws \Exception
     */
    public function build(array $roles, array $tasks): void
    {
        $this->roles = $roles;

        // Iterate roles
        foreach ($roles as $roleName => $roleInfo)
        {
            // Create store for role tasks
            $this->hash[$roleName] = [];

            // Iterate each task
            foreach ($roleInfo['tasks'] as $task)
            {
                // Disaggregate the task
                if (!isset($tasks[$task]))
                    throw new \Exception('The task ' . $roleName . '::' . $task . ' do not exists');
                
                $routes = $tasks[$task]['routes'];

                foreach ($routes as $routeStr)
                {
                    $this->hash[$roleName] = array_merge($this->hash[$roleName], $this->disaggregate($routeStr));

                } // End of: foreach ($roleInfo['tasks'] as $task)

            } // End of: foreach ($roleInfo['tasks'] as $task)

        } // End of foreach ($roles as $roleName => $roleInfo)
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
     * @param string $role
     * @param string $task
     * @return bool
     */
    public function haveAccess(string $role, string $task): bool
    {
        if (!isset($this->roles[$role]))
            throw new \InvalidArgumentException('The rol "' . $role . '" do not exists.');

        return in_array($task, $this->roles[$role]['tasks']);
    }

    /**
     * @param string $role
     * @param string $route
     * @return bool
     */
    public function canInvoke(string $role, string $route): bool
    {
        if (!isset($this->hash[$role]))
            throw new \InvalidArgumentException('The rol "' . $role . '" do not exists.');

        return in_array($route, $this->hash[$role]);
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