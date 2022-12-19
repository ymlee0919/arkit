<?php

require 'YAMLNode.php';
require 'YAMLParser.php';

class ConfigReader {

    public static function ReadFile(string $file) : array
    {
        $input = file($file);
        return YAMLParser::YAMLLoad($input);
    }
}