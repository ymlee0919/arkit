<?php

namespace Arkit\Core\Config;


class YamlReader
{

    public static function ReadFile(string $file): array
    {
        $input = file($file);
        return \Arkit\Core\Config\YAML\YAMLParser::YAMLLoad($input);
    }
}