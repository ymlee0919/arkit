<?php

namespace Landscaping;

/**
 * Class Landscaping
 * @package Landscaping
 */
class Landscaping implements \Model
{

    /**
     * @var ?Landscaping
     */
    private static ?Landscaping $instance = null;

    /**
     * @var ?\Propel\Runtime\Connection\ConnectionInterface
     */
    private ?\Propel\Runtime\Connection\ConnectionInterface $conn;

    /**
     * @var ?\Propel\Runtime\Connection\ConnectionManagerSingle
     */
    private ?\Propel\Runtime\Connection\ConnectionManagerSingle $connectionManager;

    /**
     *
     */
    private function __construct()
    {
        $this->conn = null;
        $this->connectionManager = null;
    }

    /**
     * @returns Landscaping
     */
    public static function getInstance() : Landscaping
    {
        if(!self::$instance)
            self::$instance = new Landscaping();

        return self::$instance;
    }

    /**
     *
     */
    public function beginTransaction() : void
    {
        if(!$this->conn)
            $this->conn = \Propel\Runtime\Propel::getServiceContainer()->getConnection();

        $this->conn->beginTransaction();
    }

    /**
     *
     */
    public function commit() : void
    {
        $this->conn->commit();
    }

    /**
     *
     */
    public function rollback() : void
    {
        $this->conn->rollBack();
    }

    /**
     * @returns void
     * @throws \Exception
     */
    public function load() : void
    {
		import(null, 'Libs.Propel.vendor.autoload');
        spl_autoload_register(array($this, 'loadClass'), true);
    }

    public function connect(string $account): void
    {
        $config = include dirname(__FILE__) . '/config/config.php';

        $serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
        $serviceContainer->checkVersion('2.0.0-dev');
        $serviceContainer->setAdapterClass('landscaping', 'mysql');

        $this->connectionManager = new \Propel\Runtime\Connection\ConnectionManagerSingle('landscaping');
        $this->connectionManager->setConfiguration($config[$account]);

        $serviceContainer->setConnectionManager($this->connectionManager);
        $serviceContainer->setDefaultDatasource('landscaping');
    }

    /**
     * @param $className
     * @return bool
     * @throws \Exception
     */
    public function loadClass($className) : bool
    {
        if (str_starts_with($className, 'Landscaping\\'))
        {
            import($className,'Model.' . str_replace('\\', '.', $className));
            return true;
        }
        return false;
    }

    /**
     * Close all connections
     * @return void
     */
    public function release() : void
    {
        $this->connectionManager->closeConnections();
    }

}