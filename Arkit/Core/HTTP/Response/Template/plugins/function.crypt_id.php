<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {crypt_id} function plugin
 *
 * Type:     function<br>
 * Name:     crypt_id<br>
 * Purpose:  Encrypt and id given a prefix
 *
 * @author Yuriesky Méndez Lee
 *
 * @param array                    $params   parameters
 * @param Smarty_Internal_Template $template template object
 * @return string|null
 */
function smarty_function_crypt_id($params, $template)
{
    return \Arkit\App::$Crypt->smoothEncrypt($params['prefix'] . ':' . $params['id']);
}
