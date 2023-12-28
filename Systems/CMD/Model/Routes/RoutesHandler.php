<?php
namespace CMD\Model\Routes;

class RoutesHandler
{

    private string $systemName;

    public function __construct(string $systemName)
    {
        $this->systemName = $systemName;
    }

    public function buildHandler(string $routeId, ?string $templateName = null) : void
    {
        $router = \Arkit\App::readConfig(\Arkit\App::fullPath('Systems' . DIRECTORY_SEPARATOR . $this->systemName . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'router.yaml'));

        if (!isset($router[$routeId]))
            throw new \CMD\System\Exception\InternalOperationException('The rule Id do not exists', 301);
        
        $rule = $router[$routeId];

        /// Extract the path, the class name and the function name
        $parts = explode('::', $rule['handler']);

        // Build the class
        $className = $parts[0];

        //$mainNamespace = substr($className, 0, strrpos($className, '\\'));

        $filePath = ClassHandler::buildClass($className);

        if (!$filePath)
            throw new \CMD\System\Exception\InternalOperationException('Unable to create the class ' . $className, 301);


        /// Add the function
        $functionName = $parts[1];
        // 1.- Get parameters
        $params = [];
        $url = $rule['url'];
        $count = substr_count($url, '{');
        for ($i = 1; $i <= $count; $i++) {
            $ptr = strpos($url, '{');
            $next = strpos($url, '}');
            $params[] = '$' . substr($url, $ptr + 1, $next - $ptr - 1);
            $url = substr($url, $next + 1);
        }

        // 2.- Get the parameters
        $options = null;
        if (isset($post['template']) && !empty($post['template']))
        $options = ['template' => $templateName];

        ClassHandler::addFunction($filePath, $functionName, $params, $rule['method'], $options);
    }

}