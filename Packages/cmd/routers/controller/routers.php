<?php
class Routers
{
    public function ManageRouter($package)
    {
        $output = App::$Output;
        $output->loadTemplate('router.tpl');

        // Set form ID
        App::loadFormValidator();
        App::$Form->setId('ROUTER-MANAGER')->generateCsrfCode();

        // Load the router information

        $output->assign('Package', $package);
        $output->setSessionVars('INPUT_ERROR', 'ACTION_ERROR', 'ACTION_SUCCESS');

        $output->displayTemplate();
    }

    public function GenerateAll()
    {
        $output = &App::$Output;

        // Validate entry
        $post = App::$Request->PostAll();

        $form = &App::$Form;

        $form->setId('ROUTER-MANAGER');
        $form->checkValues($post);
        $form->validate('package')->isRequired()->isString()->matchWith('/^[a-zA-Z]+$/');
        $form->validate('id')->isString()->matchWith('/^[a-z\.]+$/');
        $form->validate('action')->isRequired()->isString()->isOneOf(['single', 'all']);

        $form->validateCsrfCode();

        if(!$form->isValid())
        {
            $form->storeErrorsInSession('INPUT_ERROR', true);
            $output->redirectTo(App::$Router->buildUrl('cmd.packages'));
        }

        $package = $post['packages'];

        // Load the router
        $router = App::readConfig(App::fullPath("Packages/$package/_config/router.yaml"));
        // Get all ruleId
        $list = array_keys($router);

        // Generate each rule
        foreach($list as $ruleId)
        {
            $rule = $router[$ruleId];

            /// Extract the path, the class name and the function name
            $parts = [];
            // Parts[0] => The whole rule
            // Parts[1] => File path
            // Parts[2] => Class name
            // Parts[3] => Function name
            preg_match_all('/^([A-Za-z\.]+)\/([A-Za-z\.]+)::([A-Za-z\.]+)$/', $rule['callback'], $parts, PREG_SET_ORDER);

            // Build the class
            $filePath = $this->buildClass($package, $parts[1], $parts[2]);
            if(!$filePath)
            {
                Session::set_flash('ACTION_ERROR', 'Unable to create the class for the rule: ' . $ruleId);
                $output->redirectTo(App::$Router->buildUrl('cmd.router', ['package' => $package]));
            }

            /// Add the function
            $functionName = $parts[3];
            // 1.- Get parameters
            $params = [];
            $url = $rule['url'];
            $count = substr_count($url, '{');
            for($i = 1; $i <= $count; $i++)
            {
                $ptr = strpos($url, '{');
                $next = strpos($url, '}');
                $params[] = '$' . substr($url, $ptr + 1, $next - $ptr - 1);
                $url = substr($url, $next + 1);
            }

            $this->addFunction($filePath, $functionName, $params, $rule['method']);
        }

        Session::set_flash('ACTION_SUCCESS', 'Request success');
        $output->redirectTo(App::$Router->buildUrl('cmd.router', ['package' => $package]));
    }

    public function GenerateRule()
    {
        $output = &App::$Output;

        // Validate entry
        $post = App::$Request->PostAll();

        $form = &App::$Form;

        $form->setId('ROUTER-MANAGER');
        $form->checkValues($post);
        $form->validate('package')->isRequired()->isString()->matchWith('/^[a-zA-Z]+$/');
        $form->validate('id')->isString()->matchWith('/^[a-z\.]+$/');
        $form->validate('template')->isString()->matchWith('/^[A-Za-z0-9_\.-]+$/');

        $form->validateCsrfCode();

        if(!$form->isValid())
        {
            $form->storeErrorsInSession('INPUT_ERROR', true);
            $output->redirectTo(App::$Router->buildUrl('cmd.packages'));
        }

        $package = $post['package'];

        // Load the router
        $router = App::readConfig(App::fullPath("Packages/$package/_config/router.yaml"));

        if(!isset($router[$post['id']]))
        {
            Session::set_flash('ACTION_ERROR', 'The rule Id do not exists');
            $output->redirectTo(App::$Router->buildUrl('cmd.router', ['package' => $package]));
        }

        $rule = $router[$post['id']];

        /// Extract the path, the class name and the function name
        $parts = [];
        // Parts[0] => The whole rule
        // Parts[1] => File path
        // Parts[2] => Class name
        // Parts[3] => Function name
        preg_match_all('/^([A-za-z\.]+)\/([A-za-z\.]+)::([A-za-z\.]+)$/', $rule['callback'], $parts, PREG_SET_ORDER);

        // Build the class
        $filePath = $this->buildClass($package, $parts[0][1], $parts[0][2]);
        if(!$filePath)
        {
            Session::set_flash('ACTION_ERROR', 'Unable to create the class for the rule: ' . $post['id']);
            $output->redirectTo(App::$Router->buildUrl('cmd.router', ['package' => $package]));
        }

        /// Add the function
        $functionName = $parts[0][3];
        // 1.- Get parameters
        $params = [];
        $url = $rule['url'];
        $count = substr_count($url, '{');
        for($i = 1; $i <= $count; $i++)
        {
            $ptr = strpos($url, '{');
            $next = strpos($url, '}');
            $params[] = '$' . substr($url, $ptr + 1, $next - $ptr - 1);
            $url = substr($url, $next + 1);
        }

        // 2.- Get the parameters
        $options = null;
        if(isset($post['template']) && !empty($post['template']))
            $options = ['template' => $post['template']];

        $this->addFunction($filePath, $functionName, $params, $rule['method'], $options);

        Session::set_flash('ACTION_SUCCESS', 'Request success');
        $output->redirectTo(App::$Router->buildUrl('cmd.router', ['package' => $package]));
    }

    private function buildClass($package, $fileRoute, $className)
    {
        /// fileRoute = dir.dir[.dir*].file
        $parts = explode('.', $fileRoute);

        // The last part of the string is the file name
        $fileName = array_pop($parts) . '.php';

        // Build directories
        $rootDir = App::fullPath('/Packages/' . $package);
        foreach($parts as $dir)
        {
            $rootDir .= '/' . $dir;
            if(!is_dir($rootDir))
                mkdir($rootDir);
        }

        // Build full file name
        $fileName = $rootDir . '/' . $fileName;

        // If it already exists, return
        if(file_exists($fileName))
            return $fileName;

        // Create the view folder if it do not exists
        $rootDir = dirname($rootDir) . '/view';
        if(!file_exists($rootDir))
            mkdir($rootDir);

        // Create the file
        $classFile = file_get_contents(App::fullPathFromPackage('/routers/files/_class.php'));
        $classFile = str_replace('ClassName', $className, $classFile);
        $success = $this->write($fileName, $classFile);

        // If can not be created, return false
        if(!$success)
            return false;

        // Always return the file name
        return $fileName;
    }

    private function addFunction($fileName, $functionName, $params, $method, $options = null)
    {
        // Load the class
        $classCode = file_get_contents($fileName);

        // If the function already exists, return
        if(!!strpos($classCode, 'public function ' . $functionName))
            return true;

        // Build the parameters
        $parameters = (count($params) != 0) ? implode(', ', $params) : '';
        $replacements = [
            'functionName' => $functionName,
            '$params' => $parameters
        ];

        $functionCode = null;

        // Get the function code according to calling method
        if($method == 'GET')
        {
            $functionCode = file_get_contents(App::fullPathFromPackage('/routers/files/') . '_getFunction.php');
            if(!!$options)
            {
                if(isset($options['template']))
                {
                    $replacements['template.tpl'] = $options['template'];

                    // Create the template
                    $targetFile = dirname(dirname($fileName)) . '/view/' . $options['template'];
                    if(!file_exists($targetFile))
                        copy(App::fullPathFromPackage('/routers/files/_template.tpl'), $targetFile);
                }
            }
        }
        else
        {
            $functionCode = file_get_contents(App::fullPathFromPackage('/routers/files/') . '_postFunction.php');

        }

        $functionCode = strtr($functionCode, $replacements);

        // Insert the code into the class code
        $classCode = str_replace('/// End of class', $functionCode, $classCode);

        // Write override the class
        return $this->write($fileName, $classCode);
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