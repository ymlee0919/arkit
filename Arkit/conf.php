<?php

/*
 * Define the application run mode as Debug.
 */
define('DEBUG_MODE', 'DEBUG');

/*
 * Define the application run mode as Debug.
 */
define('TESTING_MODE', 'TESTING');

/*
 * Define the application run mode as Debug.
 */
define('RELEASE_MODE', 'RELEASE');

//// Set a secure environment:
//// According: https://cheatsheetseries.owasp.org/cheatsheets/PHP_Configuration_Cheat_Sheet.html
//-------------------------------------------
ini_set('expose_php', 'Off');
ini_set('log_errors', 'On');
ini_set('ignore_repeated_errors', 'Off');
ini_set('allow_url_fopen', 'Off');

// PHP general settings
ini_set('allow_url_fopen', 'Off');
ini_set('allow_url_include', 'Off');
ini_set('variables_order', 'GPCS');
ini_set('request_order', 'PG');
ini_set('allow_webdav_methods', 'Off');

// PHP executable handling
ini_set('enable_dl', 'Off');
ini_set('disable_functions', 'system, exec, shell_exec, passthru, phpinfo, show_source, highlight_file, popen, proc_open, fopen_with_path, dbmopen, dbase_open, putenv, filepro, filepro_rowcount, filepro_retrieve, posix_mkfifo');

// PHP session handling
ini_set('session.sid_length', 64);
ini_set('session.sid_bits_per_character', 5);
//ini_set('session.hash_function', 1);
//ini_set('session.hash_bits_per_character', 6);

// Some more security paranoid checks
ini_set('report_memleaks', 'On');
ini_set('track_errors', 'Off');
ini_set('html_errors', 'Off');
ini_set('register_globals', 'Off');

// Limit PHP Access To File System
// According: https://learn2torials.com/a/php-best-practices
ini_set('cgi.force_redirect', 'On');
ini_set('open_basedir', dirname(__FILE__, 2));