<?php

namespace CMD\Model\Routes;

class ClassHandler
{
    public static function buildClass($className) : bool|string
    {
        $parts = explode('\\', $className);
        // Get the class name
        $class = array_pop($parts);
        // Build the namespace
        $namespace = implode('\\', $parts);
        // Build directories
        $rootDir = \Arkit\App::fullPath('Systems');
        foreach ($parts as $dir) {
            $rootDir .= DIRECTORY_SEPARATOR . $dir;
            if (!is_dir($rootDir))
                mkdir($rootDir);
        }

        // The last part of the string is the file name
        $fileName = $class . '.php';

        // Build full file name
        $fileName = $rootDir . DIRECTORY_SEPARATOR . $fileName;
        $fileName = clean_file_address($fileName);

        // If it already exists, return
        if (file_exists($fileName))
            return $fileName;

        // Create the file
        $classFile = file_get_contents(dirname(__FILE__) . '/files/_class.php');
        $classFile = strtr($classFile, [
            'ClassName' => $class,
            'TheNameSpace' => $namespace,
            'SystemName' => $parts[0]
        ]);

        $success = self::write($fileName, $classFile);

        // If can not be created, return false
        if (!$success)
            return false;

        // Always return the file name
        return $fileName;
    }

    public static function addFunction($fileName, $functionName, $params, $method, $options = null)
    {
        // Load the class
        $classCode = file_get_contents($fileName);

        // If the function already exists, return
        if (!!strpos($classCode, 'public function ' . $functionName))
            return true;

        // Build the parameters
        $parameters = (count($params) != 0) ? implode(', ', $params) : '';
        $replacements = [
            'functionName' => $functionName,
            '$params' => $parameters
        ];

        // Get the function code according to calling method
        if ($method == 'GET') {
            $functionCode = file_get_contents(dirname(__FILE__) . '/files/_getFunction.php');
            if (!!$options) {
                if (isset($options['template'])) 
                {
                    $replacements['template.tpl'] = $options['template'];

                    // Create the template
                    //$targetFile = dirname($fileName) . '/view/' . $options['template'];
                    //if (!file_exists($targetFile))
                        //copy(dirname(__FILE__) . '/files/_template.tpl', $targetFile);
                }
            }
        } else {
            $functionCode = file_get_contents(dirname(__FILE__) . '/files/_postFunction.php');
        }

        $functionCode = strtr($functionCode, $replacements);

        // Insert the code into the class code
        $classCode = str_replace('/// End of class', $functionCode, $classCode);

        // Write override the class
        return self::write($fileName, $classCode);
    }

    private static function write($target, $content)
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
