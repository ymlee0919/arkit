<?php
class Packages
{
    public function ShowPackages()
    {
        $output = App::$Output;
        $output->loadTemplate('packages.tpl');

        // Load the name of the packages
        $packages = [];
        $d = dir(App::fullPath('/Packages'));
        while (false !== ($package = $d->read())) {
            if($package[0] != '.' && $package != 'cmd')
                $packages[] = $package;
        }
        $d->close();

        $output->assign('Packages', $packages);
        $output->setSessionVars('INPUT_ERROR', 'ACTION_ERROR', 'ACTION_SUCCESS');

        $output->displayTemplate();
    }

    public function NewPackage()
    {
        $output = App::$Output;
        $output->loadTemplate('new.tpl');

        // Set form ID
        App::loadFormValidator();
        App::$Form->setId('NEW-PACKAGE')->generateCsrfCode();

        // Load the name of the packages
        $models = [];
        $d = dir(App::fullPath('/Model'));
        while (false !== ($model = $d->read())) {
            if($model[0] != '.')
                $models[] = $model;
        }
        $d->close();

        $output->assign('Models', $models);

        $output->displayTemplate();
    }

    public function Add()
    {
        $output = &App::$Output;

        // Validate entry
        $post = App::$Request->PostAll();

        $form = &App::$Form;

        //var_dump($post);

        $form->setId('NEW-PACKAGE');
        $form->checkValues($post);
        $form->validate('package')->isRequired()->isString()->matchWith('/^[a-zA-Z-_]+$/');
        $form->validateCsrfCode();


        if(!$form->isValid())
        {
            $form->storeErrorsInSession('INPUT_ERROR', true);
            $output->redirectTo(App::$Router->buildUrl('cmd.packages'));
        }

        // Get configuration
        $baseTpl  = (isset($post['base']) && $post['base'] == 'yes');
        $firewall = (isset($post['firewall']) && $post['firewall'] == 'yes');
        $preLoader = (isset($post['preloader']) && $post['preloader'] == 'yes');

        $package = $post['package'];

        // Build package path
        $packageDir = App::fullPath('Packages/' . $package);
        $sourceDir = App::fullPathFromPackage('/packages/files/');

        // Make package directory
        $success = mkdir($packageDir);
        if(!$success)
        {
            Session::set_flash('ACTION_ERROR', 'Unable to create the Package');
            $output->redirectTo(App::$Router->buildUrl('cmd.packages'));
        }

        // Check if require _base directory
        if($baseTpl || $firewall || $preLoader)
        {
            // Create the base folder
            $success = mkdir($packageDir . '/_base');
            if(!$success)
            {
                Session::set_flash('ACTION_ERROR', 'Unable to create the _base directory');
                $output->redirectTo(App::$Router->buildUrl('cmd.packages'));
            }

            if($baseTpl)
            {
                // Check require base template
                $success = mkdir($packageDir . '/_base/view');
                $success &= copy($sourceDir . '_base.tpl', $packageDir . '/_base/view/base.tpl');
                if(!$success)
                {
                    Session::set_flash('ACTION_ERROR', 'Unable to create the base template.');
                    $output->redirectTo(App::$Router->buildUrl('cmd.packages'));
                }
            }

            // Create the controller folder if is required
            if($firewall || $preLoader)
            {
                $success = mkdir($packageDir . '/_base/controller');
                if(!$success)
                {
                    Session::set_flash('ACTION_ERROR', 'Unable to create the controller(s)');
                    $output->redirectTo(App::$Router->buildUrl('cmd.packages'));
                }
            }

            // Write firewall if need
            if($firewall)
            {
                $success = copy($sourceDir . '_firewall.php', $packageDir . '/_base/controller/Firewall.php');
                if(!$success)
                {
                    Session::set_flash('ACTION_ERROR', 'Unable to create the firewall');
                    $output->redirectTo(App::$Router->buildUrl('cmd.packages'));
                }
            }

            if($preLoader)
            {
                // Change the firewall class name
                $className = strtoupper($package[0]) . substr($package,1) . 'PageLoader';

                // Update firewall class name and write the file
                $class = file_get_contents($sourceDir . '_preloader.php');
                $class = str_replace('PageLoader', $className, $class);
                $success = $this->write($packageDir . '/_base/controller/PageLoader.php', $class);
                if(!$success)
                {
                    Session::set_flash('ACTION_ERROR', 'Unable to create the pre-loader');
                    $output->redirectTo(App::$Router->buildUrl('cmd.packages'));
                }
            }
        }

        // Building configuration folder
        $success = mkdir($packageDir . '/_config');
        if(!$success)
        {
            Session::set_flash('ACTION_ERROR', 'Unable to create the configuration folder');
            $output->redirectTo(App::$Router->buildUrl('cmd.packages'));
        }

        // Build the router file
        $content = file_get_contents($sourceDir . '_router.yaml');
        $content = str_replace('Pk', $package, $content);
        $success = $this->write($packageDir . '/_config/router.yaml', $content);

        if(!$success)
        {
            Session::set_flash('ACTION_ERROR', 'Unable to create the router');
            $output->redirectTo(App::$Router->buildUrl('cmd.packages'));
        }

        // Build the configuration file
        $content = file_get_contents($sourceDir . '_config.yaml');

        // Treat the model
        if(isset($post['model']) && !!$post['model'])
            $content = strtr($content,[
                '#model:' => 'model:',
                '##autoload:' => '  autoload:',
                'ModelName' => $post['model']
            ]);

        // Treat the firewall
        if($firewall)
            $content = str_replace('#firewall:', 'firewall:', $content);

        // Treat the page loader
        if($preLoader)
            $content = str_replace('#onBeforeDisplay:', 'onBeforeDisplay:', $content);

        $success = $this->write($packageDir . '/_config/config.yaml', $content);
        if(!$success)
        {
            Session::set_flash('ACTION_ERROR', 'Unable to create config file');
            $output->redirectTo(App::$Router->buildUrl('cmd.packages'));
        }

        Session::set_flash('ACTION_SUCCESS', 'Package successfully created');
        $output->redirectTo(App::$Router->buildUrl('cmd.packages'));
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