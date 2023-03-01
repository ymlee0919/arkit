<?php

namespace Model\ModelName;

/**
 * Class ModelName
 * @package ModelName
 */
class ModelName implements \Arkit\Core\Persistence\Database\Model
{

    /**
     * @var ?ModelName
     */
    private static ?ModelName $instance = null;

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
     * @returns ModelName
     */
    public static function getInstance() : ModelName
    {
        if(!self::$instance)
            self::$instance = new ModelName();

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
        \Loader::getInstance()->addNamespace('Model\\ModelName', __DIR__);
    }

    /**
     * Connect to database given an account
     * @param string $account Account for connection
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function connect(string $account): void
    {
        $config = include dirname(__FILE__) . '/config/config.php';

        $serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
        $serviceContainer->checkVersion('2.0.0-dev');
        $serviceContainer->setAdapterClass('dataBase', 'mysql');

        $this->connectionManager = new \Propel\Runtime\Connection\ConnectionManagerSingle('dataBase');
        $this->connectionManager->setConfiguration($config[$account]);

        $this->connectionManager->setName('dataBase');
        $serviceContainer->setConnectionManager($this->connectionManager);
        $serviceContainer->setDefaultDatasource('dataBase');
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