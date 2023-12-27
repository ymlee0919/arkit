<?php

namespace CMD\Controller\Systems;

class SystemsController extends \CMD\System\Core\Controller
{
    public function ShowSystems()
    {
        $response = \Arkit\App::$Response;

        // Load the name of the systems
        $systems = [];
        $d = dir(\Arkit\App::fullPath('/Systems'));
        while (false !== ($system = $d->read())) {
            if($system[0] != '.' && $system != 'CMD')
                $systems[] = $system;
        }
        $d->close();

        $response->assign('Systems', $systems);

        $responseTpl = 'systems.tpl';
        $outputTpl = (\Arkit\App::$Request->isAJAX()) ? $responseTpl : "extends:{$this->baseTpl}|{$responseTpl}";
        $response->displayTemplate($outputTpl);
    }

    public function NewSystem()
    {
        $response = \Arkit\App::$Response;

        // Set form ID
        \Arkit\App::loadInputValidator();
        \Arkit\App::$InputValidator->setId('NEW-SYSTEM')->generateCsrfCode();

        // Load the name of the systems
        $models = [];
        if(is_dir(\Arkit\App::fullPath('/Model')))
        {
            $d = dir(\Arkit\App::fullPath('/Model'));
            while (false !== ($model = $d->read())) {
                if($model[0] != '.')
                    $models[] = $model;
            }
            $d->close();
        }

        $response->assign('Models', $models);

        $responseTpl = 'new.tpl';
        $outputTpl = (\Arkit\App::$Request->isAJAX()) ? $responseTpl : "extends:{$this->baseTpl}|{$responseTpl}";
        $response->displayTemplate($outputTpl);
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

        $system = $post['system'];

        // Build system path
        $systemDir = \Arkit\App::fullPath('Systems/' . $system);

        // Directory for system core classes (EventsHander; AccessControl; Controller; etc...)
        $coreDir =  \Arkit\App::fullPath('Systems/' . $system . '/System');

        // Files sorce
        $sourceDir = \Arkit\App::fullPathFromSystem('/systems/files/');

        // Make system directory
        if(!is_dir($systemDir))
        {
            $success = mkdir($systemDir);
            $success = $success && mkdir($coreDir);

            if(!$success)
            {
                $response->setStatus(409);
                $response->error('error', 'Unable to create the System directory');
                $response->toJSON();
            }
        }
            
        else
        {
            $response->setStatus(409);
            $response->error('error', 'The system you want to create already exists');
            $response->toJSON();
        }

        // Build the default controller
        if(!is_dir($coreDir . '/Core'))
            $success = mkdir($coreDir . '/Core');

        if(!$success)
        {
            $response->setStatus(409);
            $response->error('error', 'Unable to create the Core directory');
            $response->toJSON();
        }

        $controller = file_get_contents($sourceDir . '_controller.php');
        $class = str_replace('SystemName', $system, $controller);

        // Treat the model
        if(isset($post['model']) && !!$post['model'])
            $class = str_replace('ModelName', $post['model'], $class);

        $success = $this->write($coreDir . '/Core/Controller.php', $class);
        if(!$success)
        {
            $response->setStatus(409);
            $response->error('error', 'Unable to create the Controller parent class');
            $response->toJSON();
        }

        if($baseTpl)
        {
            // Create the base folder
            if(!is_dir($systemDir . '/_base'))
                $success = mkdir($systemDir . '/_base');

            if(!$success)
            {
                $response->setStatus(409);
                $response->error('error', 'Unable to create the _base directory');
                $response->toJSON();
            }

            // Check require base template
            if(!is_dir($systemDir . '/_base/view'))
                $success = mkdir($systemDir . '/_base/view');
            $success &= copy($sourceDir . '_base.tpl', $systemDir . '/_base/view/base.tpl');
            if(!$success)
            {
                $response->setStatus(409);
                $response->error('error', 'Unable to create the base template');
                $response->toJSON();
            }

        }

        // Check if require Base directory
        if($accessControl)
        {
            // Create the base folder
            if(!is_dir($coreDir . '/Access'))
                $success = mkdir($coreDir . '/Access');

            if(!$success)
            {
                $response->setStatus(409);
                $response->error('error', 'Unable to create the Access directory');
                $response->toJSON();
            }

            $class = file_get_contents($sourceDir . '_access.php');
            $class = str_replace('SystemName', $system, $class);
            $success = $this->write($coreDir . '/Access/AccessControl.php', $class);
            if(!$success)
            {
                $response->setStatus(409);
                $response->error('error', 'Unable to create the AccessControl class');
                $response->toJSON();
            }
        }

        // Check if require Base directory
        if($customOutput)
        {
            // Create the base folder
            if(!is_dir($coreDir . '/Events'))
                $success = mkdir($coreDir . '/Events');

            // Create the base folder
            if(!is_dir($coreDir . '/Events/view'))
                $success = mkdir($coreDir . '/Events/view');

            if(!$success)
            {
                $response->setStatus(409);
                $response->error('error', 'Unable to create the Events directory');
                $response->toJSON();
            }

            // Update output class name and write the file
            $class = file_get_contents($sourceDir . '_events.php');
            $class = str_replace('SystemName', $system, $class);
            $success = $this->write($coreDir . '/Events/ResponseEvents.php', $class);
            if(!$success)
            {
                $response->setStatus(409);
                $response->error('error', 'Unable to create the ResponseEvents class');
                $response->toJSON();
            }

            // Copy custom error pages
            // 401 - Access denied
            @copy($sourceDir . '_401.html', $coreDir . '/Events/view/401.html');
            // 403 - Forbidden access
            @copy($sourceDir . '_403.html', $coreDir . '/Events/view/403.html');
            // 404 - Page not found
            @copy($sourceDir . '_404.html', $coreDir . '/Events/view/404.html');
        }

        // Building configuration folder
        $success = mkdir($systemDir . '/_config');
        if(!$success)
        {
            $response->setStatus(409);
            $response->error('error', 'Unable to create the configuration folder');
            $response->toJSON();
        }

        // Build the router file
        $content = file_get_contents($sourceDir . '_router.yaml');
        $content = str_replace('System', $system, $content);
        $success = $this->write($systemDir . '/_config/router.yaml', $content);

        if(!$success)
        {
            $response->setStatus(409);
            $response->error('error', 'Unable to create the router file');
            $response->toJSON();
        }

        // Build the configuration file
        $content = file_get_contents($sourceDir . '_config.yaml');

        $content = str_replace('{SystemName}', $system, $content);

        // Treat the firewall
        if($accessControl)
        {
            $success = @copy($sourceDir . '_roles.yaml', $systemDir . '/_config/roles.yaml');
            $success.= @copy($sourceDir . '_tasks.yaml', $systemDir . '/_config/tasks.yaml');
            if(!$success)
            {
                $response->setStatus(409);
                $response->error('error', 'Unable to create access configurations files');
                $response->toJSON();
            }
        }
        else
        {
            $content = strtr($content, [
                'access:' => '#access:',
                'controller:' => '#controller:'
            ]);
        }

        // Treat the page loader
        if(!$customOutput)
        {
            $content = str_replace('response:', '#response:', $content);
            $content = str_replace('onBeforeDisplay:', '#onAccessDenied:', $content);
            $content = str_replace('onPageNotFound:', '#onPageNotFound:', $content);
            $content = str_replace('onBeforeDisplay:', '#onBeforeDisplay:', $content);
            $content = str_replace('onForbiddenAccess:', '#onForbiddenAccess:', $content);
        }

        $success = $this->write($systemDir . '/_config/config.yaml', $content);
        if(!$success)
        {
            $response->setStatus(409);
            $response->error('error', 'Unable to create config file');
            $response->toJSON();
        }

        $response->setStatus(200);
        $response->success('System successfully created');
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