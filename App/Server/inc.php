<?php
define('DEBUG_MODE', 'DEBUG');
define('TESTING_MODE', 'TESTING');
define('RELEASE_MODE', 'RELEASE');

define('RUN_MODE', DEBUG_MODE);
//define('RUN_MODE', TESTING_MODE);
//define('RUN_MODE', RELEASE_MODE);

switch(RUN_MODE)
{
	case RELEASE_MODE:
        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
		ini_set('display_errors', '0');
		ini_set('log_errors', true);
		ini_set('output_buffering', '4096');
		ini_set('implicit_flush', 'Off');
		break;

	case TESTING_MODE:
        error_reporting(-1);
        ini_set('log_errors', true);
		ini_set('display_errors', '1');
		ini_set('output_buffering', '4096');
		ini_set('implicit_flush', 'Off');
		break;

	case DEBUG_MODE:
        error_reporting(-1);
        ini_set('display_errors', '1');
        ini_set('log_errors', false);
		ini_set('output_buffering', 'Off');
		ini_set('implicit_flush', 'On');
		break;
}


//---------------------------------------------------------------------------------------------------
//          IMPORT
//---------------------------------------------------------------------------------------------------

/**
 * @param ?string $className
 * @param string $lib
 * @param bool $include
 * @return bool
 */
function import(?string $className, string $lib, bool $include = false) : bool
{
    if(!is_null($className))
        if(class_exists($className))
            return true;

    // Go to root folder
    $folder = dirname(__FILE__, 3);

    // Explode by dot
	$tokens = explode('.', $lib);
    // The last token is the file
	$file = array_pop($tokens);
    // Build the folder
	$folder = $folder . '/' . implode('/', $tokens);
    // Check the folder exists
    if(!is_dir($folder)) return false;
    // Build full file path
    $file = $folder.'/'.$file.'.php';
    if(false === is_file($file)) return false;

    if(!$include) require $file;
    else include $file;

    return true;
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
?>