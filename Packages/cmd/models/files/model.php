<?php

namespace ModelName;

/**
 * Class ModelName
 * @package ModelName
 */
class ModelName extends Model
{

    /**
     * @var ?ModelName
     */
    private static ?ModelName $instance = null;

    /**
     * @var ?FilesManager
     */
    public static ?FilesManager $FilesManager = null;

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
    public function beginTransaction()
    {
        if(!$this->conn)
            $this->conn = \Propel\Runtime\Propel::getServiceContainer()->getConnection();

        $this->conn->beginTransaction();
    }

    /**
     *
     */
    public function commit()
    {
        $this->conn->commit();
    }

    /**
     *
     */
    public function rollback()
    {
        $this->conn->rollBack();
    }

    /**
     * @returns void
     * @throws \Exception
     */
    public function load() : void
    {
		import('Libs.Propel.vendor.autoload');
		import('Model.ModelName.config.config');
        spl_autoload_register(array($this, 'loadClass'), true);

        import('Model.ModelName.FilesManager');
        self::$FilesManager = new FilesManager();

        $config = include dirname(__FILE__) . '/config/config.php';

        $serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
        $serviceContainer->checkVersion('2.0.0-dev');
        $serviceContainer->setAdapterClass('dataBase', 'mysql');

        $this->connectionManager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
        $this->connectionManager->setConfiguration($config);

        $this->connectionManager->setName('dataBase');
        $serviceContainer->setConnectionManager('dataBase', $this->connectionManager);
        $serviceContainer->setDefaultDatasource('dataBase');

    }

    /**
     * @param $className
     * @return bool
     * @throws \Exception
     */
    public function loadClass($className) : bool
    {
        if (strpos($className, 'ModelName\\') === 0)
        {
            import('Model.' . str_replace('\\', '.', $className));
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