<?php

namespace Arkit\Core\Config;

/**
 * Yaml file reader
 * 
 * @package Arkit\Core\Config
 */
class YamlReader
{
    /**
     * Read a yaml file given the absolute file path
     *
     * @param string $file Absolute file path
     * @return array Key => Value array according file
     */
    public static function ReadFile(string $file): array
    {
        return \Arkit\Core\Config\YAML\Yaml::parseFile($file);
    }
}