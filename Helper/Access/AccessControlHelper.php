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
        $filePath = $config['path'];

        $sign = md5_file($filePath);

        if(App::$Cache->isEnable())
        {
            // Build key for cache
            $key = 'access.' . sha1($filePath);
            // Try to get the tree from cache
            $tree = App::$Cache->get($key);
            if($tree instanceof AccessTree && $tree->getSign() == $sign)
                $this->tree = $tree;
            else
            {
                // Load the tree and store in cache
                $this->loadAccessTreeFromFile($filePath);
                $this->tree->setSign($sign);
                App::$Cache->set($key, $this->tree);
            }
        }
        else
            $this->loadAccessTreeFromFile($filePath);
    }

    /**
     * @param string $filePath
     * @return void
     */
    private function loadAccessTreeFromFile(string $filePath) : void
    {
        $rules = App::readConfig($filePath);

        foreach ($rules as $role => $rulesList)
            foreach ($rulesList as $accessRule)
                $this->tree->addAccessRule($role, $accessRule);
    }

    /**
     * @param string $routingId
     * @param string ...$roles
     * @return bool
     */
    public function validateAccess(string $routingId, string ...$roles) : bool
    {
        foreach ($roles as $role)
        {
            if($this->tree->haveAccess($role, $routingId))
                return true;
        }

        return false;
    }

}