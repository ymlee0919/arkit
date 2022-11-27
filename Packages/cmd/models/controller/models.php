<?php
class Models
{
    public function ShowModels()
    {
        $output = App::$Output;
        $output->loadTemplate('models.tpl');

        // Load the name of the packages
        $models = [];
        $d = dir(App::fullPath('/Model'));
        while (false !== ($model = $d->read())) {
            if($model[0] != '.')
                $models[] = $model;
        }
        $d->close();

        $output->assign('Models', $models);
        $output->setSessionVars('INPUT_ERROR', 'ACTION_ERROR', 'ACTION_SUCCESS');

        $output->displayTemplate();
    }

    public function NewModel()
    {
        $output = App::$Output;
        $output->loadTemplate('new.tpl');

        // Set form ID
        App::loadFormValidator();
        App::$Form->setId('NEW-MODEL')->generateCsrfCode();

        $output->displayTemplate();
    }

    public function Add()
    {
        $output = &App::$Output;

        // Validate entry
        $post = App::$Request->PostAll();

        $form = &App::$Form;

        $form->setId('NEW-MODEL');
        $form->checkValues($post);
        $form->validate('model')->isRequired()->isString()->matchWith('/^[a-zA-Z_-]+$/');
        $form->validate('database')->isRequired()->isString()->matchWith('/^[a-z_-]+$/');
        $form->validate('host')->isRequired()->isString()->matchWith('/^[a-z_-]+$/');
        $form->validate('user')->isRequired()->isString()->matchWith('/^[A-Za-z_-]+$/');
        $form->validateCsrfCode();

        if(!$form->isValid())
        {
            $form->storeErrorsInSession('INPUT_ERROR', true);
            $output->redirectTo(App::$Router->buildUrl('cmd.models'));
        }

        // Get configuration
        $model = $post['model'];
        $database = $post['database'];
        $host = $post['host'];
        $user = $post['user'];
        $password = $post['pass'];

        // Build package path
        $modelDir = App::fullPath('Model/' . $model);
        $sourceDir = App::fullPathFromPackage('/models/files/');

        // Make the model directory
        $success = mkdir($modelDir);
        if(!$success)
        {
            Session::set_flash('ACTION_ERROR', 'Unable to create the Model');
            $output->redirectTo(App::$Router->buildUrl('cmd.models'));
        }

        // Create config directory
        $success = mkdir($modelDir . '/config');
        if(!$success)
        {
            Session::set_flash('ACTION_ERROR', 'Unable to create the configuration');
            $output->redirectTo(App::$Router->buildUrl('cmd.models'));
        }

        // Copy the Model Class
        $class = file_get_contents($sourceDir . 'model.php');
        $class = str_replace('ModelName', $model, $class);
        $class = str_replace('dataBase', $database, $class);
        $success = $this->write($modelDir . '/' . $model . '.php', $class);
        if(!$success)
        {
            Session::set_flash('ACTION_ERROR', 'Unable to create the Model Class');
            $output->redirectTo(App::$Router->buildUrl('cmd.models'));
        }

        // Copy the FileManager Class
        $class = file_get_contents($sourceDir . 'filesManager.php');
        $class = str_replace('ModelName', $model, $class);
        $success = $this->write($modelDir . '/FilesManager.php', $class);
        if(!$success)
        {
            Session::set_flash('ACTION_ERROR', 'Unable to create the FilesManager Class');
            $output->redirectTo(App::$Router->buildUrl('cmd.models'));
        }

        // Copy the config file
        $class = file_get_contents($sourceDir . 'config.php');
        $class = strtr($class, [
            'dataBase' => $database,
            'hostName' => $host,
            'userName' => $user,
            'userPassword' => $password
        ]);
        $success = $this->write($modelDir . '/config/config.php', $class);
        if(!$success)
        {
            Session::set_flash('ACTION_ERROR', 'Unable to create the Config file');
            $output->redirectTo(App::$Router->buildUrl('cmd.models'));
        }

        // Copy the propel config file
        $config = file_get_contents($sourceDir . 'propel.yaml');
        $config = strtr($config, [
            'dataBase' => $database,
            'hostName' => $host,
            'userName' => $user,
            'userPassword' => $password
        ]);
        $success = $this->write($modelDir . '/config/propel.yaml', $config);
        if(!$success)
        {
            Session::set_flash('ACTION_ERROR', 'Unable to create the Propel config file');
            $output->redirectTo(App::$Router->buildUrl('cmd.models'));
        }

        Session::set_flash('ACTION_SUCCESS', 'Model successfully created');
        $output->redirectTo(App::$Router->buildUrl('cmd.models'));
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