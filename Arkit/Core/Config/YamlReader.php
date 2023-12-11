<?php

namespace Arkit\Core\Config;

class YamlReader
{

    public static function ReadFile(string $file): array
    {
        return \Arkit\Core\Config\YAML\Yaml::parseFile($file);
    }
}