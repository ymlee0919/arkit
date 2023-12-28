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
     * @return ModelName
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
     * @return void
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
        $serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
        $serviceContainer->checkVersion(2);
        $serviceContainer->setAdapterClass('dataBase', 'dbType');

        $manager = new \Propel\Runtime\Connection\ConnectionManagerSingle('dataBase');

        $server = \Arkit\App::$Env['DB_SERVER'];
        $port = \Arkit\App::$Env['DB_PORT'];
        $database = \Arkit\App::$Env['DB_NAME'];
        $user = \Arkit\App::$Env['DB_USER'];
        $password = \Arkit\App::$Env['DB_PASS'];
        $sslMode = \Arkit\App::$Env['DB_SSL_MODE'];

        $config = [
            'classname' => 'Propel\\Runtime\\Connection\\DebugPDO',
            'dsn' => 'dbType:host=' . $server . ';port=' . $port . ';dbname=' . $database . ';sslmode=' . $sslMode,
            'user' => $user,
            'password' => $password,
			'attributes' =>  [ 'ATTR_EMULATE_PREPARES' => true ]
        ];
    
        $manager->setConfiguration($config);

        $serviceContainer->setConnectionManager($manager);
        $serviceContainer->setDefaultDatasource('dataBase');

        // Set Logger
        $serviceContainer->setLoggerConfiguration('defaultLogger', array (
            'type' => 'stream',
            'path' => \Arkit\App::fullPath('resources/propel.log'),
            'level' => 400,
            'bubble' => true
        ));

        $this->initTables($serviceContainer);

        $this->conn = $serviceContainer->getConnection();

    }

    private function initTables($serviceContainer)
    {
        $serviceContainer->initDatabaseMaps(array (
            'global' => 
            array (
              // 
            ),
          ));
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