<?php

namespace Model\Landscaping;

/**
 * Class Landscaping
 * @package Landscaping
 */
class Landscaping implements \Arkit\Core\Persistence\Database\Model
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
     * @var \Arkit\Core\Security\Crypt\CryptInterface
     */
    public readonly \Arkit\Core\Security\Crypt\CryptInterface $crypt;

    /**
     *
     */
    private function __construct()
    {
        $this->conn = null;
        $this->connectionManager = null;

        $this->crypt = new \Arkit\Core\Security\Crypt();
        $this->crypt->init();
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
        \Loader::getInstance()->addNamespace('Model\\Landscaping', __DIR__);
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
        $serviceContainer->setAdapterClass('landscaping', 'mysql');

        $this->connectionManager = new \Propel\Runtime\Connection\ConnectionManagerSingle('landscaping');
        $this->connectionManager->setConfiguration($config[$account]);

        $this->connectionManager->setName('landscaping');
        $serviceContainer->setConnectionManager($this->connectionManager);
        $serviceContainer->setDefaultDatasource('landscaping');
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