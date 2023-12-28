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

        $responseTpl = './routes/main.tpl';
        $response->displayTemplate($responseTpl);
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

        try
        {
            $routerHandler = new \CMD\Model\Routes\RoutesHandler($system);
            $routerHandler->buildHandler($post['id'], (!empty($post['template']) ? $post['template'] : null));
        }
        catch(\Exception $ex)
        {
            $response->setStatus(409);
            $response->error('error', $ex->getMessage());
            $response->toJSON();
        }

        $response->setStatus(200);
        $response->success('Route handler successfully created');
        $response->toJSON();

        exit;
        
        /*
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
        */
    }

    
} 