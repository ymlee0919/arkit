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
 * @author Yuriesky Méndez Lee
 *
 * @param array                    $params   parameters
 * @param Smarty_Internal_Template $template template object
 * @return string|null
 */
function smarty_function_url($params, $template)
{
	$list = [];
	foreach($params as $key => $value)
	{
		if($key == 'id') continue;
		if(is_array($value))
		{
			if(!class_exists('Crypt', false))
				import('App.Server.Security.Crypt');
			
			$list[$key] = Crypt::encodeUrl($value['prefix'] . ':' . $value['id']);
		}
		else
			$list[$key] = $value;
	}
	return App::$Router->buildUrl($params['id'], $list);
}
