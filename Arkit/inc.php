<?php

/**
 * Clean a file address of double directory separators.
 *
 * @param string $fileName File name
 * @return string
 * @example clean_file_address('/src//path/to\file.php') returns /src/path/to/file.php
 */
function clean_file_address(string $fileName): string
{
    return strtr(
        strtr($fileName, ['\\' => DIRECTORY_SEPARATOR, '/' => DIRECTORY_SEPARATOR]),
        [DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR => DIRECTORY_SEPARATOR]
    );
}

/**
 * Given a date in string format and the format input, return the date in the given output format
 * @param string $date Date 
 * @param string $inputFormat Input format date
 * @param string $outputFormat Output format date
 * @return string
 * @example format_date('19-09-2019', 'd-m-Y', 'Y.m.d') returns 2019.09.19
 */
function format_date(string $date, string $inputFormat, string $outputFormat): string
{
    return DateTime::createFromFormat($inputFormat, $date)->format($outputFormat);
}

/**
 * Convert a name to a url part. 
 * 
 * @param string $name Name to be converted
 * @param bool $clean_spaces Clean double white spaces
 * @return string
 * 
 * @example name2url('Name of the   product', true) returns name-of-the-product
 */
function name2url(string $name, bool $clean_spaces = true): string
{
    $value = $name;

    $search  = array(' ', '_', 'á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', '&', '.', 'Á', 'É', 'Í', 'Ó', 'Ú', "'");
    $replace = array('-', '-', 'a', 'e', 'i', 'o', 'u', 'n', 'u', 'and', '-', 'a', 'e', 'i', 'o', 'u', '');

    $value = str_replace($search, $replace, $value);
    $value = str_replace('ñ', 'n', $value);

    $value = strtolower($value);
    if ($clean_spaces) 
    {
        // Delete unnecessary white space
        $value = trim($value);
        while (str_contains($value, '  ')) $value = str_replace('  ', ' ', $value);
    }

    while (str_contains($value, '--')) $value = str_replace('--', '-', $value);

    $tmp = str_replace('-', '', $value);

    if (!ctype_alnum($tmp)) 
    {
        for ($i = 0; $i < strlen($tmp); $i++)
            if (!ctype_alnum($tmp[$i]))
                $value = str_replace($tmp[$i], '', $value);
    }

    return $value;
}
