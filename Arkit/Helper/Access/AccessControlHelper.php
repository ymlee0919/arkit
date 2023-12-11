<?php

namespace Arkit\Helper\Access;

/**
 *
 */
class AccessControlHelper
{
    /**
     * @var AccessHash
     */
    private AccessHash $tree;

    /**
     *
     */
    public function __construct()
    {
        $this->tree = new AccessHash();
    }

    /**
     * @param array $config
     * @return void
     */
    public function init(array $config): void
    {
        $rolesSource = $config['roles_source'];
        $tasksSource = $config['tasks_source'];

        $md5Roles = md5_file($rolesSource);
        $md5Tasks = md5_file($tasksSource);
        $sign = sha1($md5Roles . '//' . $md5Tasks);

        if (\Arkit\App::$Cache->isEnabled()) {
            // Build key for cache
            $key = 'access.' . sha1($md5Roles);
            // Try to get the tree from cache
            $tree = \Arkit\App::$Cache->get($key);
            if ($tree instanceof AccessHash && $tree->getSign() == $sign)
                $this->tree = $tree;
            else {
                // Load the tree and store in cache
                $this->loadAccessTreeFromFiles($rolesSource, $tasksSource);
                $this->tree->setSign($sign);
                \Arkit\App::$Cache->set($key, $this->tree);
            }
        } else
            $this->loadAccessTreeFromFiles($rolesSource, $tasksSource);
    }

    /**
     * @param string $rolesSource
     * @param string $tasksSource
     * @return void
     * @throws \Exception
     */
    private function loadAccessTreeFromFiles(string $rolesSource, string $tasksSource): void
    {
        $roles = \Arkit\App::readConfig($rolesSource);
        $tasks = \Arkit\App::readConfig($tasksSource);

        $this->tree->build($roles, $tasks);
    }

    /**
     * @param string $task
     * @param string ...$roles
     * @return bool
     */
    public function checkTaskAccess(string $task, string ...$roles): bool
    {
        foreach ($roles as $role)
            if ($this->tree->haveAccess($role, $task))
                return true;

        return false;
    }

    /**
     * @param string $routingId
     * @param string ...$roles
     * @return bool
     */
    public function checkRoutingAccess(string $routingId, string ...$roles): bool
    {
        foreach ($roles as $role)
            if ($this->tree->canInvoke($role, $routingId))
                return true;

        return false;
    }

}