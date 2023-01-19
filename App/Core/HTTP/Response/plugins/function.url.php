<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {url} function plugin
 *
 * Type:     function<br>
 * Name:     url<br>
 * Purpose:  Build the url given the id and parameters
 *
 * @author Yuriesky M�ndez Lee
 *
 * @param array                    $params   parameters
 * @param Smarty_Internal_Template $template template object
 * @return string|null
 */
function smarty_function_url($params, $template)
{
	return \Arkit\App::$Router->buildUrl($params['id'], $params);
}
