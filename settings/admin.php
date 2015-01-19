<?php
/**
 * ownCloud - 
 *
 * @author Marc DeXeT
 * @copyright 2014 DSI CNRS https://www.dsi.cnrs.fr
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */


/**
* This PHP scripts is used by AppInfo\App to register an admin panel.
* It sets a bunch of var and calls a template.
* 
*/
use OCA\User_servervars2\Lib\ConfigHelper;

$appName = 'user_servervars2';
$helper = new ConfigHelper();
$tmpl = new OCP\Template($appName, 'settings-admin');

$array = array(
	'sso_url', 
	'slo_url',
	'auto_create_user',
	'update_user_data',
	'update_groups',
	'stop_if_empty',
	'tokens_class',
	'tokens_conf',
	//'tokens_conf_data',
	'group_naming_conf',
	'group_naming_class',
	'button_name'
);

foreach ($array as $key) {
	$parm = OCP\Config::getAppValue($appName, $key);

	$tmpl->assign($key, $parm);
	
	if ( $helper->endsWith($key, 'conf') && $parm) {
		$tmpl->assign($key.'_data', $helper->getJSon($parm));
	}


}

return $tmpl->fetchPage();
