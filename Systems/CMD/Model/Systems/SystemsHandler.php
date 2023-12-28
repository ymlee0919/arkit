<?php

namespace CMD\Model\Systems;

class SystemsHandler
{
    private string $systemsDir;

    public function __construct()
    {
        $this->systemsDir = \Arkit\App::fullPath('/Systems');
    }

    public function getList(): array
    {
        // Read systems
        $systems = [];

        $d = dir($this->systemsDir);
        while (false !== ($system = $d->read())) {
            if ($system[0] != '.' && $system[0] != '..' && $system != 'CMD')
                $systems[] = $system;
        }
        $d->close();

        return $systems;
    }

    public function createSystem(string $systemName, ?string $modelName, bool $accessControl, bool $customOutput, bool $baseTemplate)
    {
        // Create file structure
        $this->createStructure($systemName);

        // Initialize configuration files
        $this->initConfigFiles($systemName, $accessControl, $customOutput);

        // Initilize system controllers files
        $this->initSystemFiles($systemName, $modelName, $accessControl, $customOutput);

        // Init view if required
        if(!!$baseTemplate)
            $this->initViewFiles($systemName);
    }

    private function createStructure(string $systemName)
    {
        $currentSystemDir = $this->systemsDir . DIRECTORY_SEPARATOR . $systemName;
        $success = true;

        // Create main directory
        if(!is_dir($currentSystemDir))
        {
            $success = @mkdir($currentSystemDir);
            if(!$success)
                throw new \CMD\System\Exception\InternalOperationException('Unable to create the system main directory', 201);
        }
        else
            throw new \CMD\System\Exception\InternalOperationException('The system you want to create alreay exists', 200);


        // Create Config directory
        $targetDir = $currentSystemDir . DIRECTORY_SEPARATOR . 'Config';
        if(!is_dir($targetDir))
            $success = @mkdir($targetDir);
        if (!$success)
            throw new \CMD\System\Exception\InternalOperationException('Unable to create the system config directory', 202);

        // Create Controller directory
        $targetDir = $currentSystemDir . DIRECTORY_SEPARATOR . 'Controller';
        if (!is_dir($targetDir))
            $success = @mkdir($targetDir);
        if (!$success)
            throw new \CMD\System\Exception\InternalOperationException('Unable to create the system controllers directory', 203);

        // Create Core directory
        $targetDir = $currentSystemDir . DIRECTORY_SEPARATOR . 'System';
        if (!is_dir($targetDir))
            $success = @mkdir($targetDir);
        if (!$success)
            throw new \CMD\System\Exception\InternalOperationException('Unable to create the system core directory', 204);

        // Create view directory
        $targetDir = $currentSystemDir . DIRECTORY_SEPARATOR . 'View';
        if (!is_dir($targetDir))
            $success = @mkdir($targetDir);
        if (!$success)
            throw new \CMD\System\Exception\InternalOperationException('Unable to create the system views directory', 205);
    }

    private function initConfigFiles(string $systemName, bool $accessControl, bool $customOutput)
    {
        $sourceDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR;
        $targetDir = $this->systemsDir . DIRECTORY_SEPARATOR . $systemName . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR;
        
        // Copy router file
        $content = file_get_contents($sourceDir . '_router.yaml');
        $content = str_replace('System', $systemName, $content);
        $success = $this->write($targetDir . 'router.yaml', $content);

        if (!$success) 
            throw new \CMD\System\Exception\InternalOperationException('Unable to create the router file', 205);

        // Copy config file
        $content = file_get_contents($sourceDir . '_config.yaml');
        $content = str_replace('{SystemName}', $systemName, $content);

        if (!$customOutput) 
        {
            $content = str_replace('response:', '#response:', $content);
            $content = str_replace('onAccessDenied:', '#onAccessDenied:', $content);
            $content = str_replace('onPageNotFound:', '#onPageNotFound:', $content);
            $content = str_replace('onBeforeDisplay:', '#onBeforeDisplay:', $content);
            $content = str_replace('onForbiddenAccess:', '#onForbiddenAccess:', $content);
        }

        $success = $this->write($targetDir . 'config.yaml', $content);
        if (!$success)
            throw new \CMD\System\Exception\InternalOperationException('Unable to create config file', 206);

        // Copy access control files
        if ($accessControl) 
        {
            $success  = @copy($sourceDir . '_roles.yaml', $targetDir . 'roles.yaml');
            $success .= @copy($sourceDir . '_tasks.yaml', $targetDir . 'tasks.yaml');
            if (!$success)
                throw new \CMD\System\Exception\InternalOperationException('Unable to create access configurations files', 207);
            
        }
    }

    private function initSystemFiles(string $systemName, ?string $modelName, bool $accessControl, bool $customOutput)
    {
        $sourceDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR;
        $targetDir = $this->systemsDir . DIRECTORY_SEPARATOR . $systemName . DIRECTORY_SEPARATOR . 'System' . DIRECTORY_SEPARATOR;
        $success = true;

        // Create controller class
        $coreDir = $targetDir . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR;
        if (!is_dir($coreDir))
            $success = @mkdir($coreDir);
        if (!$success)
            throw new \CMD\System\Exception\InternalOperationException('Unable to create the system core directory', 208);

        $class = file_get_contents($sourceDir . '_controller.php');
        $class = str_replace('SystemName', $systemName, $class);

        // Treat the model
        if (!empty($modelName)) 
            $class = str_replace('ModelName', $modelName, $class);

        $success = $this->write($coreDir . 'Controller.php', $class);
        if (!$success)
            throw new \CMD\System\Exception\InternalOperationException('Unable to create the Controller parent class', 209);

        // Add access control if required
        if ($accessControl) 
        {
            $accessDir = $targetDir . DIRECTORY_SEPARATOR . 'Access' . DIRECTORY_SEPARATOR;
            if(!is_dir($accessDir))
                $success = @mkdir($accessDir);
            if (!$success)
                throw new \CMD\System\Exception\InternalOperationException('Unable to create the Access directory', 210);

            $class = file_get_contents($sourceDir . '_access.php');
            $class = str_replace('SystemName', $systemName,  $class);
            $success = $this->write($accessDir . '/AccessControl.php', $class);
            if (!$success)
                throw new \CMD\System\Exception\InternalOperationException('Unable to create the AccessControl class', 211);
        }

        // Add custom output if required
        if (!!$customOutput) 
        {
            $eventsDir = $targetDir . DIRECTORY_SEPARATOR . 'Events';

            // Create the base folder
            if (!is_dir($eventsDir))
                $success = mkdir($eventsDir);

            // Create the base folder
            if (!is_dir($eventsDir . '/view'))
                $success .= mkdir($eventsDir . '/view');

            if (!$success)
                throw new \CMD\System\Exception\InternalOperationException('Unable to create the Events directory', 212);

            // Update output class name and write the file
            $class = file_get_contents($sourceDir . '_events.php');
            $class = str_replace('SystemName', $systemName, $class);
            $success = $this->write($eventsDir . '/ResponseEvents.php', $class);
            if (!$success)
                throw new \CMD\System\Exception\InternalOperationException('Unable to create the ResponseEvents class', 213);
            

            // Copy custom error pages
            // 401 - Access denied
            @copy($sourceDir . '_401.html', $eventsDir . '/view/401.html');
            // 403 - Forbidden access
            @copy($sourceDir . '_403.html', $eventsDir . '/view/403.html');
            // 404 - Page not found
            @copy($sourceDir . '_404.html', $eventsDir . '/view/404.html');
        }
    }

    private function initViewFiles(string $systemName)
    {
        $sourceDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR;
        $targetDir = $this->systemsDir . DIRECTORY_SEPARATOR . $systemName . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR;

        $success = copy($sourceDir . '_base.tpl', $targetDir . '/base.tpl');
        if (!$success)
        if (!$success)
            throw new \CMD\System\Exception\InternalOperationException('Unable to create the base template', 213);
    }

    private function write($target, $content)
    {
        try {
            $hFile = fopen($target, 'wb+');
            fwrite($hFile, $content);
            fflush($hFile);
            fclose($hFile);
            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }
}
