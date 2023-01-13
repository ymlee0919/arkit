<?php

require 'AccessTree.php';

/**
 *
 */
class AccessControlHelper
{
    /**
     * @var AccessTree
     */
    private AccessTree $tree;

    /**
     *
     */
    public function __construct()
    {
        $this->tree = new AccessTree();
    }

    /**
     * @param array $config
     * @return void
     */
    public function init(array $config) : void
    {
        $rolesSource = $config['roles_source'];
        $tasksSource = $config['tasks_source'];

        $md5Roles = md5_file($rolesSource);
        $md5Tasks = md5_file($tasksSource);
        $sign = sha1($md5Roles . '//' . $md5Tasks);

        if(App::$Cache->isEnable())
        {
            // Build key for cache
            $key = 'access.' . sha1($md5Roles);
            // Try to get the tree from cache
            $tree = App::$Cache->get($key);
            if($tree instanceof AccessTree && $tree->getSign() == $sign)
                $this->tree = $tree;
            else
            {
                // Load the tree and store in cache
                $this->loadAccessTreeFromFiles($rolesSource, $tasksSource);
                $this->tree->setSign($sign);
                App::$Cache->set($key, $this->tree);
            }
        }
        else
            $this->loadAccessTreeFromFiles($rolesSource, $tasksSource);
    }

    /**
     * @param string $rolesSource
     * @param string $tasksSource
     * @return void
     * @throws Exception
     */
    private function loadAccessTreeFromFiles(string $rolesSource, string $tasksSource) : void
    {
        $roles = App::readConfig($rolesSource);
        $tasks = App::readConfig($tasksSource);

        $this->tree->build($roles, $tasks);
    }

    /**
     * @param string $task
     * @param string ...$roles
     * @return bool
     */
    public function checkTaskAccess(string $task, string ...$roles) : bool
    {
        foreach ($roles as $role)
            if($this->tree->haveAccess($role, $task))
                return true;

        return false;
    }

    /**
     * @param string $routingId
     * @param string ...$roles
     * @return bool
     */
    public function checkRoutingAccess(string $routingId, string ...$roles) : bool
    {
        foreach ($roles as $role)
            if($this->tree->canInvoke($role, $routingId))
                return true;

        return false;
    }

}