<?php
namespace CMD\Model\Models;

class ModelsHandler
{
    private string $modelsDir;

    public function __construct()
    {
        $this->modelsDir = \Arkit\App::fullPath('/Model');
    }

    public function getList() : array
    {
        $models = [];
        
        if (is_dir($this->modelsDir)) {
            $d = dir($this->modelsDir);
            while (false !== ($model = $d->read())) {
                if ($model[0] != '.')
                    $models[] = $model;
            }
            $d->close();
        }

        return $models;
    }

    public function createModel(string $modelName, array $connectionsParameters)
    {
        // Create directory structure
        $this->createDirectoryStructure($modelName);

        // Write content
        $this->writeFiles(
            $modelName, 
            $connectionsParameters['database'], 
            $connectionsParameters['type'],
            $connectionsParameters['host'],
            $connectionsParameters['port'],
            $connectionsParameters['user'],
            $connectionsParameters['password'],
            $connectionsParameters['master']
        );
    }

    private function createDirectoryStructure(string $modelName)
    {
        $modelDir    = $this->modelsDir . DIRECTORY_SEPARATOR  . $modelName;
        $persistence = $this->modelsDir . DIRECTORY_SEPARATOR . $modelName . DIRECTORY_SEPARATOR . 'Persistence';
        $business    = $this->modelsDir . DIRECTORY_SEPARATOR . $modelName . DIRECTORY_SEPARATOR . 'Business';

        if (!is_dir($this->modelsDir))
            mkdir($this->modelsDir);

        // Make the model directory
        if (!is_dir($modelDir))
            $success = mkdir($modelDir);
        else
            $success = true;
        if (!$success)
            throw new \CMD\System\Exception\InternalOperationException('Unable to create the model directory', 101);

        // Make the persistence directory
        if (!is_dir($persistence))
            $success = mkdir($persistence);
        else
            $success = true;
        if (!$success)
            throw new \CMD\System\Exception\InternalOperationException('Unable to create the Persistence Model', 102);
        
        // Make the business directory
        if (!is_dir($business))
            $success = mkdir($business);
        else
            $success = true;
        if (!$success)
            throw new \CMD\System\Exception\InternalOperationException('Unable to create the Business Model', 103);

        // Create config directory
        if (!is_dir($persistence . '/config'))
        $success = mkdir($persistence . '/config');
        else
            $success = true;
        if (!$success)
            throw new \CMD\System\Exception\InternalOperationException('Unable to create the configuration', 104);
        
    }

    private function writeFiles(
        string $model,
        string $database,
        string $type,
        string $host,
        string $port,
        string $user,
        string $password, 
        bool $createMaster = false)
    {
        $modelDir    = $this->modelsDir . DIRECTORY_SEPARATOR  . $model;
        $persistence = $this->modelsDir . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . 'Persistence';

        $sourceDir   = \Arkit\App::fullPathFromSystem('/Model/Models/files/');
        
        // Copy the Model Class
        $class = file_get_contents($sourceDir .  'model.php');
        $class = strtr($class, [
            'ModelName' => $model,
            'dataBase' => $database,
            'dbType' => $type
        ]);

        $success = $this->write($modelDir . '/' . $model . '.php', $class);
        if(!$success)
            throw new \CMD\System\Exception\InternalOperationException('Unable to create the Model Class', 105);


        // Copy Master class if is set
        if (!!$createMaster) 
        {
            $class = file_get_contents($sourceDir .  'master.php');
            $class = str_replace('ModelName', $model, $class);
            $success = $this->write($modelDir . '/Master.php', $class);
            if(!$success)
                throw new \CMD\System\Exception\InternalOperationException('Unable to create the Master Class', 106);
        }

        // Write the configuration
        $envFile = \Arkit\App::fullPath('Arkit/Config/.env');
        $env = file_get_contents($envFile);

        $env = strtr($env, [
            '/SERVER_NAME/' => $host,
            '/DATABASE_NAME/' => $database,
            '/DATABASE_PORT/' => $port,
            '/DATABASE_USER/' => $user,
            '/USER_PASSWORD/' => $password
        ]);

        $success = $this->write($envFile, $env);
        if (!$success)
            throw new \CMD\System\Exception\InternalOperationException('Unable to create the Config file', 107);

        // Copy the schema file
        $file = file_get_contents($sourceDir . 'schema.xml');
        $file = strtr($file, [
            'ModelName' => $model,
            'DataBase' => $database
        ]);

        $success = $this->write($persistence . '/config/schema.xml', $file);
        if (!$success)
            throw new \CMD\System\Exception\InternalOperationException('Unable to create the Schema file', 108);

        // Copy propel config file
        $config = file_get_contents($sourceDir . 'propel.yaml');
        $config = strtr($config, [
            'dbType' => $type,
            'dataBase' => $database,
            'hostName' => $host,
            'userName' => $user,
            'userPassword' => $password
        ]);

        $success = $this->write($persistence . '/config/propel.yaml', $config);
        if (!$success)
            throw new \CMD\System\Exception\InternalOperationException('Unable to create the Propel config file', 109);    
    }

    private function write($target, $content)
    {
        try {
            $hFile = fopen($target, 'wb+');
            fwrite($hFile, $content);
            fflush($hFile);
            fclose($hFile);
            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }
}
