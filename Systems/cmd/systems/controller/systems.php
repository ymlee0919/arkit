<?php
class Systems
{
    public function ShowSystems()
    {
        $output = App::$Response;
        $output->loadTemplate('systems.tpl');

        // Load the name of the systems
        $systems = [];
        $d = dir(App::fullPath('/Systems'));
        while (false !== ($system = $d->read())) {
            if($system[0] != '.' && $system != 'cmd')
                $systems[] = $system;
        }
        $d->close();

        $output->assign('Systems', $systems);
        $output->setSessionVars('INPUT_ERROR', 'ACTION_ERROR', 'ACTION_SUCCESS');

        $output->displayTemplate();
    }

    public function NewSystem()
    {
        $output = App::$Response;
        $output->loadTemplate('new.tpl');

        // Set form ID
        App::loadFormValidator();
        App::$Form->setId('NEW-SYSTEM')->generateCsrfCode();

        // Load the name of the systems
        $models = [];
        if(is_dir(App::fullPath('/Model')))
        {
            $d = dir(App::fullPath('/Model'));
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
        $output = &App::$Response;

        // Validate entry
        $post = App::$Request->getAllPostParams();

        $form = &App::$Form;

        //var_dump($post);

        $form->setId('NEW-SYSTEM');
        $form->checkValues(App::$Request);
        $form->validate('system')->isRequired()->isString()->matchWith('/^[a-zA-Z-_]+$/');
        $form->validateCsrfCode();

        if(!$form->isValid())
        {
            $form->storeErrorsInSession('INPUT_ERROR', true);
            $output->redirectTo('cmd.systems');
        }

        // Get configuration
        $baseTpl  = (isset($post['base']) && $post['base'] == 'yes');
        $firewall = (isset($post['firewall']) && $post['firewall'] == 'yes');
        $preLoader = (isset($post['preloader']) && $post['preloader'] == 'yes');

        $system = $post['system'];

        // Build system path
        $systemDir = App::fullPath('Systems/' . $system);
        $sourceDir = App::fullPathFromSystem('/systems/files/');

        // Make system directory
        if(!is_dir($systemDir))
            $success = mkdir($systemDir);
        else
            $success = true;

        if(!$success)
        {
            App::$Session->setFlash('ACTION_ERROR', 'Unable to create the System');
            $output->redirectTo('cmd.systems');
        }

        // Check if require _base directory
        if($baseTpl || $firewall || $preLoader)
        {
            // Create the base folder
            if(!is_dir($systemDir . '/_base'))
                $success = mkdir($systemDir . '/_base');

            if(!$success)
            {
                App::$Session->setFlash('ACTION_ERROR', 'Unable to create the _base directory');
                $output->redirectTo('cmd.systems');
            }

            if($baseTpl)
            {
                // Check require base template
                if(!is_dir($systemDir . '/_base/view'))
                    $success = mkdir($systemDir . '/_base/view');
                $success &= copy($sourceDir . '_base.tpl', $systemDir . '/_base/view/base.tpl');
                if(!$success)
                {
                    App::$Session->setFlash('ACTION_ERROR', 'Unable to create the base template.');
                    $output->redirectTo('cmd.systems');
                }
            }

            // Create the controller folder if is required
            if($firewall || $preLoader)
            {
                if(!is_dir($systemDir . '/_base/controller'))
                    $success = mkdir($systemDir . '/_base/controller');
                if(!$success)
                {
                    App::$Session->setFlash('ACTION_ERROR', 'Unable to create the controller(s)');
                    $output->redirectTo('cmd.systems');
                }
            }

            // Write firewall if need
            if($firewall)
            {
                $class = file_get_contents($sourceDir . '_firewall.php');
                $class = str_replace('_System_', $system, $class);
                $success = $this->write($systemDir . '/_base/controller/' . $system. 'AccessControl.php', $class);
                if(!$success)
                {
                    App::$Session->setFlash('ACTION_ERROR', 'Unable to create the firewall');
                    $output->redirectTo('cmd.systems');
                }
            }

            $loaderClassName = 'PageLoader';
            if($preLoader)
            {
                // Change the preloader class name
                $loaderClassName = strtoupper($system[0]) . substr($system,1) . 'PageLoader';

                // Update preloader class name and write the file
                $class = file_get_contents($sourceDir . '_preloader.php');
                $class = str_replace('PageLoader', $loaderClassName, $class);
                $success = $this->write($systemDir . '/_base/controller/' .$loaderClassName . '.php', $class);
                if(!$success)
                {
                    App::$Session->setFlash('ACTION_ERROR', 'Unable to create the pre-loader');
                    $output->redirectTo('cmd.systems');
                }
            }
        }

        // Building configuration folder
        $success = mkdir($systemDir . '/_config');
        if(!$success)
        {
            App::$Session->setFlash('ACTION_ERROR', 'Unable to create the configuration folder');
            $output->redirectTo('cmd.systems');
        }

        // Build the router file
        $content = file_get_contents($sourceDir . '_router.yaml');
        $content = str_replace('Pk', $system, $content);
        $success = $this->write($systemDir . '/_config/router.yaml', $content);

        if(!$success)
        {
            App::$Session->setFlash('ACTION_ERROR', 'Unable to create the router');
            $output->redirectTo('cmd.systems');
        }

        // Build the configuration file
        $content = file_get_contents($sourceDir . '_config.yaml');

        // Treat the model
        if(isset($post['model']) && !!$post['model'])
            $content = strtr($content,[
                'ModelName' => $post['model']
            ]);

        // Treat the firewall
        if($firewall)
        {
            $content = str_replace('{System}', $system, $content);

            $success = @copy($sourceDir . '_access.yaml', $systemDir . '/_config/access.yaml');
            if(!$success)
            {
                App::$Session->setFlash('ACTION_ERROR', 'Unable to create access configuration file');
                $output->redirectTo('cmd.systems');
            }
        }
        else
        {
            $content = strtr($content, [
                'access:' => '#access:',
                '  controller:' => '##controller:'
            ]);
        }

        // Treat the page loader
        if($preLoader)
            $content = str_replace('{PageLoader}', $loaderClassName, $content);
        else
            $content = str_replace('onBeforeDisplay:', '#onBeforeDisplay:', $content);

        $success = $this->write($systemDir . '/_config/config.yaml', $content);
        if(!$success)
        {
            App::$Session->setFlash('ACTION_ERROR', 'Unable to create config file');
            $output->redirectTo('cmd.systems');
        }

        App::$Session->setFlash('ACTION_SUCCESS', 'System successfully created');
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