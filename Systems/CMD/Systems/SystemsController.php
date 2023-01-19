<?php

namespace CMD\Systems;

class SystemsController
{
    public function ShowSystems()
    {
        $output = \Arkit\App::$Response;
        $output->loadTemplate('systems.tpl');

        // Load the name of the systems
        $systems = [];
        $d = dir(\Arkit\App::fullPath('/Systems'));
        while (false !== ($system = $d->read())) {
            if($system[0] != '.' && $system != 'CMD')
                $systems[] = $system;
        }
        $d->close();

        $output->assign('Systems', $systems);
        $output->setSessionVars('INPUT_ERROR', 'ACTION_ERROR', 'ACTION_SUCCESS');

        $output->displayTemplate();
    }

    public function NewSystem()
    {
        $output = \Arkit\App::$Response;
        $output->loadTemplate('new.tpl');

        // Set form ID
        \Arkit\App::loadFormValidator();
        \Arkit\App::$Form->setId('NEW-SYSTEM')->generateCsrfCode();

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

        $output->assign('Models', $models);

        $output->displayTemplate();
    }

    public function Add()
    {
        $output = &\Arkit\App::$Response;

        // Validate entry
        $post = \Arkit\App::$Request->getAllPostParams();

        $form = &\Arkit\App::$Form;

        //var_dump($post);

        $form->setId('NEW-SYSTEM');
        $form->checkValues(\Arkit\App::$Request);
        $form->validate('system')->isRequired()->isString()->matchWith('/^[a-zA-Z-_]+$/');
        $form->validateCsrfCode();

        if(!$form->isValid())
        {
            $form->storeErrorsInSession('INPUT_ERROR', true);
            $output->redirectTo('cmd.systems');
        }

        // Get configuration
        $baseTpl  = (isset($post['base']) && $post['base'] == 'yes');
        $accessControl = (isset($post['access']) && $post['access'] == 'yes');
        $customOutput = (isset($post['output']) && $post['output'] == 'yes');

        $system = $post['system'];

        // Build system path
        $systemDir = \Arkit\App::fullPath('Systems/' . $system);
        $sourceDir = \Arkit\App::fullPathFromSystem('/systems/files/');

        // Make system directory
        if(!is_dir($systemDir))
            $success = mkdir($systemDir);
        else
            $success = true;

        if(!$success)
        {
            \Arkit\App::$Session->setFlash('ACTION_ERROR', 'Unable to create the System');
            $output->redirectTo('cmd.systems');
        }

        if($baseTpl)
        {
            // Create the base folder
            if(!is_dir($systemDir . '/_base'))
                $success = mkdir($systemDir . '/_base');

            if(!$success)
            {
                \Arkit\App::$Session->setFlash('ACTION_ERROR', 'Unable to create the _base directory');
                $output->redirectTo('cmd.systems');
            }

            // Check require base template
            if(!is_dir($systemDir . '/_base/view'))
                $success = mkdir($systemDir . '/_base/view');
            $success &= copy($sourceDir . '_base.tpl', $systemDir . '/_base/view/base.tpl');
            if(!$success)
            {
                \Arkit\App::$Session->setFlash('ACTION_ERROR', 'Unable to create the base template.');
                $output->redirectTo('cmd.systems');
            }

        }

        // Check if require Base directory
        if($accessControl)
        {
            // Create the base folder
            if(!is_dir($systemDir . '/Access'))
                $success = mkdir($systemDir . '/Access');

            if(!$success)
            {
                \Arkit\App::$Session->setFlash('ACTION_ERROR', 'Unable to create the Access directory');
                $output->redirectTo('cmd.systems');
            }

            $class = file_get_contents($sourceDir . '_access.php');
            $class = str_replace('SystemName', $system, $class);
            $success = $this->write($systemDir . '/Access/AccessControl.php', $class);
            if(!$success)
            {
                \Arkit\App::$Session->setFlash('ACTION_ERROR', 'Unable to create the access control class');
                $output->redirectTo('cmd.systems');
            }
        }

        // Check if require Base directory
        if($customOutput)
        {
            // Create the base folder
            if(!is_dir($systemDir . '/Events'))
                $success = mkdir($systemDir . '/Events');

            // Create the base folder
            if(!is_dir($systemDir . '/Events/view'))
                $success = mkdir($systemDir . '/Events/view');

            if(!$success)
            {
                \Arkit\App::$Session->setFlash('ACTION_ERROR', 'Unable to create the Events directory');
                $output->redirectTo('cmd.systems');
            }

            // Update output class name and write the file
            $class = file_get_contents($sourceDir . '_response.php');
            $class = str_replace('System', $system, $class);
            $success = $this->write($systemDir . '/Events/ResponseEvents.php', $class);
            if(!$success)
            {
                \Arkit\App::$Session->setFlash('ACTION_ERROR', 'Unable to create the response class');
                $output->redirectTo('cmd.systems');
            }

            // Copy custom error pages
            // 401 - Access denied
            @copy($sourceDir . '_401.html', $systemDir . '/Events/view/401.html');
            // 403 - Forbidden access
            @copy($sourceDir . '_403.html', $systemDir . '/Events/view/403.html');
            // 404 - Page not found
            @copy($sourceDir . '_404.html', $systemDir . '/Events/view/404.html');
        }

        // Building configuration folder
        $success = mkdir($systemDir . '/_config');
        if(!$success)
        {
            \Arkit\App::$Session->setFlash('ACTION_ERROR', 'Unable to create the configuration folder');
            $output->redirectTo('cmd.systems');
        }

        // Build the router file
        $content = file_get_contents($sourceDir . '_router.yaml');
        $content = str_replace('System', $system, $content);
        $success = $this->write($systemDir . '/_config/router.yaml', $content);

        if(!$success)
        {
            \Arkit\App::$Session->setFlash('ACTION_ERROR', 'Unable to create the router');
            $output->redirectTo('cmd.systems');
        }

        // Build the configuration file
        $content = file_get_contents($sourceDir . '_config.yaml');

        // Treat the model
        if(isset($post['model']) && !!$post['model'])
            $content = strtr($content,[
                'ModelName' => $post['model']
            ]);
        else
            $content = strtr($content, [
                'model:' => '#model:',
                'name:' => '#name:'
            ]);


        $content = str_replace('{System}', $system, $content);

        // Treat the firewall
        if($accessControl)
        {
            $success = @copy($sourceDir . '_roles.yaml', $systemDir . '/_config/roles.yaml');
            $success.= @copy($sourceDir . '_tasks.yaml', $systemDir . '/_config/tasks.yaml');
            if(!$success)
            {
                \Arkit\App::$Session->setFlash('ACTION_ERROR', 'Unable to create access configuration file');
                $output->redirectTo('cmd.systems');
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
            \Arkit\App::$Session->setFlash('ACTION_ERROR', 'Unable to create config file');
            $output->redirectTo('cmd.systems');
        }

        \Arkit\App::$Session->setFlash('ACTION_SUCCESS', 'System successfully created');
        $output->redirectTo('cmd.systems');
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