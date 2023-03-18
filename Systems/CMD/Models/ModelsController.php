<?php
namespace CMD\Models;

class ModelsController extends \CMD\Core\Controller
{
    private string $modelsDir;

    public function __construct()
    {
        $this->modelsDir = \Arkit\App::fullPath('/Model');
    }

    public function ShowModels()
    {
        $output = \Arkit\App::$Response;

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

        $output->assign('Models', $models);

        $output->displayTemplate('models.tpl');
    }

    public function NewModel()
    {
        $output = \Arkit\App::$Response;
        
        // Set a sample cookie
        $options = [
            'expires'  => time() + 3600,
            'path'     => '/',
            'domain'   => 'cmd.landscaping.com',
            'secure'   => false,
            'httponly' => true,
            'samesite' => 'Lax'
        ];

        setcookie('MyCookie', md5('This is my secret key'), $options);

        // Set form ID
        \Arkit\App::loadInputValidator();
        \Arkit\App::$InputValidator->setId('NEW-MODEL')->generateCsrfCode();

        $output->displayTemplate('new.tpl');
    }

    public function Add()
    {
        $output = &\Arkit\App::$Response;

        // Validate entry
        $post = \Arkit\App::$Request->getAllPostParams();

        $form = &\Arkit\App::$InputValidator;

        $form->setId('NEW-MODEL');
        $form->checkValues(\Arkit\App::$Request);
        $form->validate('model')->isRequired()->isString()->matchWith('/^[a-zA-Z_-]+$/');
        $form->validate('database')->isRequired()->isString()->matchWith('/^[a-z_-]+$/');
        $form->validate('host')->isRequired()->isString()->matchWith('/^[a-z_-]+$/');
        $form->validate('user')->isRequired()->isString()->matchWith('/^[A-Za-z_-]+$/');
        $form->validateCsrfCode();

        if(!$form->isValid())
        {
            $output->inputErrors($form->getErrors());
            $output->redirectTo('cmd.models');
        }
        

        // Get configuration
        $model = $post['model'];
        $database = $post['database'];
        $host = $post['host'];
        $user = $post['user'];
        $password = $post['pass'];

        // Build package path
        $modelDir = \Arkit\App::fullPath('Model/' . $model);
        $persistence = \Arkit\App::fullPath('Model' . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . 'Persistence' );
        $business = \Arkit\App::fullPath('Model' . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . 'Business' );
        $sourceDir = \Arkit\App::fullPathFromSystem('/Models/files/');

        if(!is_dir($this->modelsDir))
            mkdir($this->modelsDir);

        // Make the model directory
        if(!is_dir($modelDir))
            $success = mkdir($modelDir);
        else
            $success = true;
        if(!$success)
        {
            $output->error('ACTION_ERROR', 'Unable to create the Model');
            $output->redirectTo(\Arkit\App::$Router->buildUrl('cmd.models'));
        }

        // Make the persistence directory
        if(!is_dir($persistence))
            $success = mkdir($persistence);
        else
            $success = true;
        if(!$success)
        {
            $output->error('ACTION_ERROR', 'Unable to create the Persistence Model');
            $output->redirectTo(\Arkit\App::$Router->buildUrl('cmd.models'));
        }

        // Make the business directory
        if(!is_dir($business))
            $success = mkdir($business);
        else
            $success = true;
        if(!$success)
        {
            $output->error('ACTION_ERROR', 'Unable to create the Business Model');
            $output->redirectTo(\Arkit\App::$Router->buildUrl('cmd.models'));
        }

        // Create config directory
        if(!is_dir($persistence . '/config'))
            $success = mkdir($persistence . '/config');
        else
            $success = true;
        if(!$success)
        {
            $output->error('ACTION_ERROR', 'Unable to create the configuration', true);
            $output->redirectTo('cmd.models');
        }

        // Copy the Model Class
        $class = file_get_contents($sourceDir . ((isset($post['crypt']) && $post['crypt'] == 'yes') ? 'modelWCrypt.php' : 'model.php'));
        $class = str_replace('ModelName', $model, $class);
        $class = str_replace('dataBase', $database, $class);
        $success = $this->write($modelDir . '/' . $model . '.php', $class);
        if(!$success)
        {
            $output->error('ACTION_ERROR', 'Unable to create the Model Class', true);
            $output->redirectTo('cmd.models');
        }

        // Copy the config file
        $class = file_get_contents($sourceDir . 'config.php');
        $class = strtr($class, [
            'dataBase' => $database,
            'hostName' => $host,
            'userName' => $user,
            'userPassword' => $password
        ]);
        $success = $this->write($persistence . '/config/config.php', $class);
        if(!$success)
        {
            $output->error('ACTION_ERROR', 'Unable to create the Config file', true);
            $output->redirectTo('cmd.models');
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
            $output->error('ACTION_ERROR', 'Unable to create the Schema file', true);
            $output->redirectTo('cmd.models');
        }

        // Copy propel config file
        $config = file_get_contents($sourceDir . 'propel.yaml');
        $config = strtr($config, [
            'dataBase' => $database,
            'hostName' => $host,
            'userName' => $user,
            'userPassword' => $password
        ]);
        $success = $this->write($persistence . '/config/propel.yaml', $config);
        if(!$success)
        {
            $output->error('ACTION_ERROR', 'Unable to create the Propel config file');
            $output->redirectTo('cmd.models');
        }

        $output->success('Model successfully created');
        $output->redirectTo('cmd.models');
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