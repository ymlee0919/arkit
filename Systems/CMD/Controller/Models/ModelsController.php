<?php
namespace CMD\Controller\Models;

class ModelsController extends \CMD\System\Core\Controller
{
    private string $modelsDir;

    public function __construct()
    {
        $this->modelsDir = \Arkit\App::fullPath('/Model');
    }

    public function ShowModels()
    {
        $response = \Arkit\App::$Response;

        // Load the name of the packages
        $models = [];
        if(is_dir($this->modelsDir))
        {
            $d = dir($this->modelsDir);
            while (false !== ($model = $d->read())) {
                if($model[0] != '.')
                    $models[] = $model;
            }
            $d->close();
        }

        $response->assign('Models', $models);

        $responseTpl = 'models.tpl';
        $responseTpl = (\Arkit\App::$Request->isAJAX()) ? $responseTpl : "extends:{$this->baseTpl}|{$responseTpl}";
        $response->displayTemplate($responseTpl);
    }

    public function NewModel()
    {
        $response = \Arkit\App::$Response;

        // Set form ID
        \Arkit\App::loadInputValidator();
        \Arkit\App::$InputValidator->setId('NEW-MODEL')->generateCsrfCode();

        $responseTpl = 'new.tpl';
        $responseTpl = (\Arkit\App::$Request->isAJAX()) ? $responseTpl : "extends:{$this->baseTpl}|{$responseTpl}";
        $response->displayTemplate($responseTpl);
    }

    public function Add()
    {
        $response = &\Arkit\App::$Response;

        // Validate entry
        $post = \Arkit\App::$Request->getAllPostParams();

        $form = &\Arkit\App::$InputValidator;

        $form->setId('NEW-MODEL');
        $form->checkValues(\Arkit\App::$Request);
        $model    = $form->validate('model')->isRequired()->isString()->matchWith('/^[a-zA-Z_-]+$/')->getValue();
        $database = $form->validate('database')->isRequired()->isString()->matchWith('/^[a-z_-]+$/')->getValue();
        $type     = $form->validate('type')->isRequired()->isString()->matchWith('/^[a-zA-Z_-]+$/')->getValue();
        $host     = $form->validate('host')->isRequired()->isString()->matchWith('/^[a-z_-]+$/')->getValue();
        $port     = $form->validate('port')->isRequired()->isInteger()->isPositive()->getValue();
        $user     = $form->validate('user')->isRequired()->isString()->matchWith('/^[A-Za-z_-]+$/')->getValue();
        $password = $form->validate('pass')->isRequired()->isString()->matchWith('/^[A-Za-z_-]+$/')->getValue();
        
        $form->validateCsrfCode();

        if(!$form->isValid())
        {
            $response->setStatus(400);
            $response->inputErrors($form->getErrors());
            $response->toJSON();
        }

        // Build package path
        $modelDir    = \Arkit\App::fullPath('Model/' . $model);
        $persistence = \Arkit\App::fullPath('Model' . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . 'Persistence' );
        $business    = \Arkit\App::fullPath('Model' . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . 'Business' );
        $sourceDir   = \Arkit\App::fullPathFromSystem('/Models/files/');

        if(!is_dir($this->modelsDir))
            mkdir($this->modelsDir);

        // Make the model directory
        if(!is_dir($modelDir))
            $success = mkdir($modelDir);
        else
            $success = true;
        if(!$success)
        {
            $response->setStatus(409);
            $response->error('error', 'Unable to create the model directory');
            $response->toJSON();
        }

        // Make the persistence directory
        if(!is_dir($persistence))
            $success = mkdir($persistence);
        else
            $success = true;
        if(!$success)
        {
            $response->setStatus(409);
            $response->error('error', 'Unable to create the Persistence Model');
            $response->toJSON();
        }

        // Make the business directory
        if(!is_dir($business))
            $success = mkdir($business);
        else
            $success = true;
        if(!$success)
        {
            $response->setStatus(409);
            $response->error('error', 'Unable to create the Business Model');
            $response->toJSON();
        }

        // Create config directory
        if(!is_dir($persistence . '/config'))
            $success = mkdir($persistence . '/config');
        else
            $success = true;
        if(!$success)
        {
            $response->setStatus(409);
            $response->error('error', 'Unable to create the configuration');
            $response->toJSON();
        }

        // Copy the Model Class
        $class = file_get_contents($sourceDir .  'model.php');
        $class = str_replace('ModelName', $model, $class);
        $class = str_replace('dataBase', $database, $class);
        $class = str_replace('dbType', $type, $class);

        $success = $this->write($modelDir . '/' . $model . '.php', $class);
        if(!$success)
        {
            $response->setStatus(409);
            $response->error('error', 'Unable to create the Model Class');
            $response->toJSON();
        }

        // Copy Master class if is set
        if(isset($post['master']) && $post['master'] == 'yes')
        {
            $class = file_get_contents($sourceDir .  'master.php');
            $class = str_replace('ModelName', $model, $class);
            $success = $this->write($modelDir . '/Master.php', $class);
            if(!$success)
            {
                $response->setStatus(409);
                $response->error('error', 'Unable to create the Master Class');
                $response->toJSON();
            }
        }
        
        // Write the configuration
        $envFile = \Arkit\App::fullPath('App/Config/.env');
        $env = file_get_contents($envFile);

        $env = strtr($env, [
            '/SERVER_NAME/' => $host,
            '/DATABASE_NAME/' => $database,
            '/DATABASE_PORT/' => $port,
            '/DATABASE_USER/' => $user,
            '/USER_PASSWORD/' => $password
        ]);
        $success = $this->write($envFile, $env);
        if(!$success)
        {
            $response->setStatus(409);
            $response->error('error', 'Unable to create the Config file');
            $response->toJSON();
        }

        // Copy the schema file
        $file = file_get_contents($sourceDir . 'schema.xml');
        $file = strtr($file, [
            'ModelName' => $model,
            'DataBase' => $database
        ]);
        $success = $this->write($persistence . '/config/schema.xml', $file);
        if(!$success)
        {
            $response->setStatus(409);
            $response->error('error', 'Unable to create the Schema file');
            $response->toJSON();
        }

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
        if(!$success)
        {
            $response->setStatus(409);
            $response->error('error', 'Unable to create the Propel config file');
            $response->toJSON();
        }

        $response->setStatus(200);
        $response->success('Model successfully created');
        $response->toJSON();
    }

    private function write($target, $content)
    {
        try
        {
            $hFile = fopen($target,'wb+');
            fwrite($hFile, $content);
            fflush($hFile);
            fclose($hFile);
            return true;
        }
        catch(\Exception $ex)
        {
            return false;
        }
    }
} 