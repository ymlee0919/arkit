<?php

/**
 * Class TinyTemplate
 */
class TinyTemplate
{
    /**
     * Compile a small template, generally used by emails
     * @param string $sourceTpl
     * @param array $vars
     * @return string
     */
    public static function compile(string $sourceTpl, array $vars) : string
    {
        $hash = [];
        foreach($vars as $key => $value)
            $hash['{' . $key . '}'] = $value;

        return strtr(file_get_contents($sourceTpl), $hash);
    }

} 