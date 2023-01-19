<?php

namespace Arkit\Core\Base;


class YamlReader
{

    public static function ReadFile(string $file): array
    {
        $input = file($file);
        return \Arkit\Core\Base\YAML\YAMLParser::YAMLLoad($input);
    }
}