<?php

namespace CMD\Controller\Systems;

class SystemsController extends \CMD\System\Core\Controller
{
    public function ShowSystems()
    {
        $response = \Arkit\App::$Response;

        // Read systems
        $systemsHandler = new \CMD\Model\Systems\SystemsHandler();
        $systems = $systemsHandler->getList();
        $response->assign('Systems', $systems);

        $responseTpl = './systems/main.tpl';
        $response->displayTemplate($responseTpl);
    }

    public function NewSystem()
    {
        $response = \Arkit\App::$Response;

        // Set form ID
        \Arkit\App::loadInputValidator();
        \Arkit\App::$InputValidator->setId('NEW-SYSTEM')->generateCsrfCode();

        // Read models
        $modelsHandler = new \CMD\Model\Models\ModelsHandler();
        $models = $modelsHandler->getList();
        $response->assign('Models', $models);

        $responseTpl = './systems/add.tpl';
        $response->displayTemplate($responseTpl);
    }

    public function Add()
    {
        $response = &\Arkit\App::$Response;

        // Validate entry
        $post = \Arkit\App::$Request->getAllPostParams();

        $form = &\Arkit\App::$InputValidator;

        $form->setId('NEW-SYSTEM');
        $form->checkValues(\Arkit\App::$Request);
        $form->validate('system')->isRequired()->isString()->matchWith('/^[a-zA-Z-_]+$/');
        $form->validateCsrfCode();

        if(!$form->isValid())
        {
            $response->setStatus(400);
            $response->inputErrors($form->getErrors());
            $response->toJSON();
        }

        // Get configuration
        $baseTpl  = (isset($post['base']) && $post['base'] == 'yes');
        $accessControl = (isset($post['access']) && $post['access'] == 'yes');
        $customOutput = (isset($post['output']) && $post['output'] == 'yes');
        $modelName = (!empty($post['model'])) ? $post['model'] : null;

        $system = $post['system'];

        $systemsHandler = new \CMD\Model\Systems\SystemsHandler();
        try
        {
            $systemsHandler->createSystem($system, $modelName, $accessControl, $customOutput, $baseTpl);
        }
        catch(\Exception $ex)
        {
            $response->setStatus(409);
            $response->error('error', $ex->getMessage());
            $response->toJSON();
        }

        $response->setStatus(200);
        $response->success('System successfully created');
        $response->toJSON();

    }
} 