<?php
define('DEBUG_MODE', 'DEBUG');
define('TESTING_MODE', 'TESTING');
define('RELEASE_MODE', 'RELEASE');

//define('RUN_MODE', DEBUG_MODE);
define('RUN_MODE', TESTING_MODE);

switch(RUN_MODE)
{
	case RELEASE_MODE:
		ini_set('display_errors', 'Off');
		ini_set('output_buffering', '4096');
		ini_set('implicit_flush', 'Off');
		break;

	case TESTING_MODE:
		ini_set('display_errors', 'On');
		ini_set('output_buffering', '4096');
		ini_set('implicit_flush', 'Off');
		break;

	case DEBUG_MODE:
		ini_set('display_errors', 'On');
		ini_set('output_buffering', 'Off');
		ini_set('implicit_flush', 'On');
		break;
}


//---------------------------------------------------------------------------------------------------
//          IMPORT
//---------------------------------------------------------------------------------------------------


/**
 * @param string $lib
 * @param bool $include
 * @return bool
 * @throws Exception
 */
function import(string $lib, bool $include = false) : bool
{
    if(!str_contains($lib, '.'))
        throw new Exception('Invalid library <' . $lib .'>');

    //$_folder = dirname(dirname(dirname(__FILE__)));
    $_folder = getcwd();

	$tokens = explode('.', $lib);
	
	$_file = array_pop($tokens);
	$_folder = $_folder . '/' . implode('/', $tokens);

    if($_file == '*')
    {
        if(false === is_dir($_folder))
			return false;
        if ($hDir = opendir($_folder))
		{
            while (false !== ($entry = readdir($hDir)))
			{
                if ($entry != '.' && $entry != '..' && strtolower(substr($entry, -4)) == ".php" )
				{
					$file = $_folder.'/'.$entry;
                    if(is_file($file))
                    {
                        if(!$include) require $file;
						else include $file;
                    }
                }
            }
            closedir($hDir);
        }
    }
    else
    {
		$file = $_folder.'/'.$_file.'.php';
        if(false === is_file($file))
			return false;
        else
		{
			if(!$include) require $file;
			else include $file;
		}
    }

    return true;
}

/**
 * @param string $filePath
 * @return string
 */
function file_type(string $filePath) : string
{
    return substr($filePath, strrpos($filePath, '.') + 1);
}

/**
 * @param string $seed
 * @return string
 */
function generate_random_filename(string $seed) : string
{
    return substr(md5( $seed . date("Y-m-d:H.i.s") . session_id() ), 0, 5) . date("YmdHis");
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