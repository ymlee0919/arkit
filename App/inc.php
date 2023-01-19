<?php

function clean_file_address(string $fileName) : string
{
    return strtr(
        strtr($fileName, ['\\' => DIRECTORY_SEPARATOR, '/' => DIRECTORY_SEPARATOR]),
        DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR);
}

/**
 * @param string $date
 * @param string $inputFormat
 * @param string $outputFormat
 * @return string
 */
function format_date(string $date, string $inputFormat, string $outputFormat) : string
{
    return DateTime::createFromFormat($inputFormat, $date)->format($outputFormat);
}

/**
 * @param mixed $value
 * @param string $separator
 * @param bool $includeKeys
 * @return string
 */
function to_str(mixed $value, string $separator = ';', bool $includeKeys = true) : string
{
    if(is_array($value))
    {
        $str = '';
        reset($value);
        foreach($value as $key => $val)
            $str .= (($includeKeys) ? $key . ': ' : '') . $val . $separator;
    }
    else
    {
        return strval($value);
    }
}

/**
 * @param string $name
 * @param bool $clean_spaces
 * @return string
 */
function name2url(string $name, bool $clean_spaces = true) : string
{
    $value = $name;

    $search  = array(' ', '_', 'á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', '&', '.', 'Á', 'É', 'Í', 'Ó', 'Ú',"'");
    $replace = array('-', '-', 'a', 'e', 'i', 'o', 'u', 'n', 'u', 'and', '-', 'a', 'e', 'i', 'o', 'u', '');

    $value = str_replace($search, $replace, $value);
    $value = str_replace('ñ', 'n', $value);

    $value = strtolower($value);
    if($clean_spaces)
    {
        // Delete unnecessary white space
        $value = trim($value);
        while(str_contains($value, '  ')) $value = str_replace('  ', ' ', $value);
    }

    while(str_contains($value, '--')) $value = str_replace('--', '-', $value);

    $tmp = str_replace('-', '', $value);

    if(!ctype_alnum($tmp))
    {
        for($i = 0; $i < strlen($tmp); $i++)
            if(!ctype_alnum($tmp[$i]))
                $value = str_replace($tmp[$i], '', $value);
    }

    return $value;
}

/**
 * @param string $phone
 * @return string
 */
function clean_phone_number(string $phone) : string
{
    $number = '';
    $chars = str_split($phone);
    foreach($chars as $c)
    {
        if(filter_var($c, FILTER_VALIDATE_INT) !== false)
            $number = $number . $c;
    }

    return $number;
}

function file_type($file): string
{
    return $file;
}
?>