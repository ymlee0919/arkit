<?php
namespace CMD\Controller\Routes;

class RoutesController extends \CMD\System\Core\Controller
{
    public function ManageRouter($system)
    {
        $response = \Arkit\App::$Response;

        // Set form ID
        \Arkit\App::loadInputValidator();
        \Arkit\App::$InputValidator->setId('ROUTER-MANAGER')->generateCsrfCode();

        // Load the router information

        $response->assign('System', $system);
        
        $responseTpl = 'router.tpl';
        $responseTpl = (\Arkit\App::$Request->isAJAX()) ? $responseTpl : "extends:{$this->baseTpl}|{$responseTpl}";
        $response->displayTemplate($responseTpl);
    }

    public function GenerateAll()
    {
        $output = &\Arkit\App::$Response;

        // Validate entry
        $post = \Arkit\App::$Request->getAllPostParams();

        $form = &\Arkit\App::$InputValidator;

        $form->setId('ROUTER-MANAGER');
        $form->checkValues(\Arkit\App::$Request);
        $form->validate('system')->isRequired()->isString()->matchWith('/^[a-zA-Z]+$/');
        //$form->validate('id')->isString()->matchWith('/^[a-z\.]+$/');
        $form->validate('action')->isRequired()->isString()->isOneOf(['single', 'all']);

        $form->validateCsrfCode();

        if(!$form->isValid())
        {
            $output->inputErrors($form->getErrors());
            $output->redirectTo('cmd.systems');
        }

        $form->releaseCsrfCookie();

        $system = $post['system'];

        // Load the router
        $router = \Arkit\App::readConfig(\Arkit\App::fullPath("Systems/$system/_config/router.yaml"));
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
            $filePath = $this->buildClass($system, $parts[1], $parts[2]);
            if(!$filePath)
            {
                $output->error('ACTION_ERROR', 'Unable to create the class for the rule: ' . $ruleId);
                $output->redirectTo('cmd.router', ['system' => $system]);
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

        $output->success( 'Request success');

        $output->redirectTo('cmd.router', ['system' => $system]);
    }

    public function GenerateRule()
    {
        $response = &\Arkit\App::$Response;

        // Validate entry
        $post = \Arkit\App::$Request->getAllPostParams();

        $form = &\Arkit\App::$InputValidator;

        $form->setId('ROUTER-MANAGER');
        $form->checkValues(\Arkit\App::$Request);
        $validSystem = $form->validate('system')->isRequired()->isString()->matchWith('/^[a-zA-Z]+$/')->isValid();
        $form->validate('id')->isRequired('The rule id is required')->isString()->matchWith('/^[a-z\.-]+$/');
        $form->validate('template')->isString()->matchWith('/^[A-Za-z0-9_\.-]+$/');

        $form->validateCsrfCode();

        if(!$form->isValid())
        {
            $response->setStatus(400);
            $response->inputErrors($form->getErrors());
            $response->toJSON();
        }

        $system = $post['system'];

        // Load the router
        $router = \Arkit\App::readConfig(\Arkit\App::fullPath("Systems/$system/_config/router.yaml"));

        if(!isset($router[$post['id']]))
        {
            $response->setStatus(409);
            $response->error('error', 'The rule Id do not exists');
            $response->toJSON();
        }

        $rule = $router[$post['id']];

        /// Extract the path, the class name and the function name
        $parts = explode('::', $rule['handler']);

        // Build the class
        $className = $parts[0];
        $mainNamespace = substr($className,0, strrpos($className, '\\'));

        $filePath = $this->buildClass($className);
        if(!$filePath)
        {
            $response->setStatus(409);
            $response->error('error', "Unable to create the class {$className}");
            $response->toJSON();
        }

        //// Build folders if sent
        $rootDir = dirname($filePath);
        // Extract the name of the module
        $moduleName = basename($rootDir);
        // I18n
        if(isset($post['i18n']) && $post['i18n'] === 'yes')
        {
            $i18nFolder = $rootDir . '/view/i18n';
            if(!is_dir($i18nFolder))
            {
                if(!mkdir($i18nFolder))
                {
                    $response->setStatus(409);
                    $response->error('error', "Unable to create the i18n internationalization directory");
                    $response->toJSON();
                }
            }
                
        }
        // Helper
        if(isset($post['helper']) && $post['helper'] === 'yes')
        {
            $helperFolder = $rootDir . '/Helper';
            if(!is_dir($helperFolder))
            {
                if(!mkdir($helperFolder))
                {
                    $response->setStatus(409);
                    $response->error('error', "Unable to create the directory for helpers");
                    $response->toJSON();
                }
            }
		
	        $namespace = $mainNamespace . '\\Helper';

            if(isset($post['pdf']) && $post['pdf'] === 'yes')
            {
                $className = $moduleName . 'PdfHelper';

                $fileName = $rootDir . '/Helper/'. $className . '.php';
                if(!is_file($fileName))
                {
                    $classFile = file_get_contents(\Arkit\App::fullPathFromSystem('/Routes/files/_pdfHelper.php'));
                    $classFile = str_replace('pdfHelper', $className, $classFile);
                    $classFile = str_replace('TheNameSpace', $namespace, $classFile);
                    $success = $this->write($fileName, $classFile);
                    if(!$success)
                    {
                        $response->setStatus(409);
                        $response->error('error', "Unable to create the class the PDF Helper class {$className}");
                        $response->toJSON();
                    }
                }
            }

            if(isset($post['email']) && $post['email'] === 'yes')
            {
		        $className = $moduleName . 'EmailHelper';

                $fileName = $rootDir . '/Helper/'. $className . '.php';
                if(!is_file($fileName))
                {
                    $classFile = file_get_contents(\Arkit\App::fullPathFromSystem('/Routes/files/_emailHelper.php'));
                    $classFile = str_replace('emailHelper', $className, $classFile);
		            $classFile = str_replace('TheNameSpace', $namespace, $classFile);
                    $success = $this->write($fileName, $classFile);
                    if(!$success)
                    {
                        $response->setStatus(409);
                        $response->error('error', "Unable to create the class the Email Helper class {$className}");
                        $response->toJSON();
                    }
                }
            }
        }
        // Email
        if(isset($post['email']) && $post['email'] === 'yes')
        {
            $emailFolder = $rootDir . '/view/email';
            if(!is_dir($emailFolder))
                mkdir($emailFolder);
        }
        // PDF
        if(isset($post['pdf']) && $post['pdf'] === 'yes')
        {
            $pdfFolder = $rootDir . '/view/pdf';
            if(!is_dir($pdfFolder))
                mkdir($pdfFolder);
        }

        /// Add the function
        $functionName = $parts[1];
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

        $response->setStatus(200);
        $response->success('Route handler successfully created');
        $response->toJSON();
    }

    private function buildClass($className)
    {
        $parts = explode('\\', $className);
        // Get the class name
        $class = array_pop($parts);
        // Build the namespace
        $namespace = implode('\\', $parts);
        // Build directories
        $rootDir = \Arkit\App::fullPath('Systems');
        foreach($parts as $dir)
        {
            $rootDir .= DIRECTORY_SEPARATOR . $dir;
            if(!is_dir($rootDir))
                mkdir($rootDir);
        }

        // The last part of the string is the file name
        $fileName = $class . '.php';

        // Build full file name
        $fileName = $rootDir . DIRECTORY_SEPARATOR . $fileName;
        $fileName = clean_file_address($fileName);

        // If it already exists, return
        if(file_exists($fileName))
            return $fileName;

        // Create the view folder if it do not exists
        $viewDir = $rootDir . '/view';
        if(!file_exists($viewDir))
            mkdir($viewDir);

        // Create the file
        $classFile = file_get_contents(\Arkit\App::fullPathFromSystem('/Routes/files/_class.php'));
        $classFile = str_replace('ClassName', $class, $classFile);
        $classFile = str_replace('TheNameSpace', $namespace, $classFile);
        $classFile = str_replace('SystemName', $parts[0], $classFile);
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

        // Get the function code according to calling method
        if($method == 'GET')
        {
            $functionCode = file_get_contents(\Arkit\App::fullPathFromSystem('/Routes/files/') . '_getFunction.php');
            if(!!$options)
            {
                if(isset($options['template']))
                {
                    $replacements['template.tpl'] = $options['template'];

                    // Create the template
                    $targetFile = dirname($fileName) . '/view/' . $options['template'];
                    if(!file_exists($targetFile))
                        copy(\Arkit\App::fullPathFromSystem('/Routes/files/_template.tpl'), $targetFile);
                }
            }
        }
        else
        {
            $functionCode = file_get_contents(\Arkit\App::fullPathFromSystem('/Routes/files/') . '_postFunction.php');

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