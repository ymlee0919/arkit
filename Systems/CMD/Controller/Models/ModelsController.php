<?php
namespace CMD\Controller\Models;

class ModelsController extends \CMD\System\Core\Controller
{
    public function ShowModels()
    {
        $response = \Arkit\App::$Response;

        // Read models
        $modelsHandler = new \CMD\Model\Models\ModelsHandler();
        $models = $modelsHandler->getList();
        $response->assign('Models', $models);

        $responseTpl = './models/main.tpl';
        $response->displayTemplate($responseTpl);
    }

    public function NewModel()
    {
        $response = \Arkit\App::$Response;

        // Set form ID
        \Arkit\App::loadInputValidator();
        \Arkit\App::$InputValidator->setId('NEW-MODEL')->generateCsrfCode();

        $responseTpl = './models/add.tpl';
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

        $modelsHandler = new \CMD\Model\Models\ModelsHandler();
        try
        {
            $modelsHandler->createModel($model, [
                'database'  => $database,
                'type'      => $type,
                'host'      => $host,
                'port'      => $port,
                'user'      => $user,
                'password'  => $password,
                'master'    => (isset($post['master']) && $post['master'] == 'yes')
            ]);
        }
        catch(\Exception $ex)
        {
            $response->setStatus(409);
            $response->error('error', $ex->getMessage());
            $response->toJSON();
        }

        $response->setStatus(200);
        $response->success('Model successfully created');
        $response->toJSON();

        
    }
} 