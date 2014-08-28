<?php
/**
 * ownCloud - app.php
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
namespace OCA\User_Servervars2\AppInfo;

$app = new ConfigApp();
$c = $app->getContainer();
$appName = 'user_servervars2';
if ( ! \OCP\App::isEnabled( $appName) ) {
	return;
}



//To put a template into admin menu
\OCP\App::registerAdmin('user_servervars2', 'settings');

$app->getUserManager()->registerBackend( $c->query('UserBackend'));
$c->query('ServerVarsHooks')->register( $app->getUserSession());
$authStatus = $c->isLoggedIn();

// - trigger authentication - 
// http://localhost/core/index.php?XDEBUG_SESSION_START=sublime.xdebug&app=usv2&debug=1
if(isset($_GET['app']) && $_GET['app'] == 'usv2') {

	$tokens = $app->getTokens();
        \OC_Log::write('servervars', 'TOKENS'.$tokens,\OC_Log::ERROR);
	$uag = $c->query('UserAndGroupService');
	$uid = $tokens->getUserId();

	if ( $uid === false ) {
		$ssoURL = $app->getAppConfig()->getValue('user_servervars2', 'sso_url', 'http://localhost/sso');
		 \OCP\Response::redirect($ssoURL);
        exit();
	} 

	$isLoggedIn = $c->isLoggedIn();

	if ( ! $isLoggedIn ) {
		$isLoggedIn = $uag->login($uid); 
	}
	if ( !$isLoggedIn || !$c->isLoggedIn())  {
		            \OC_Log::write('servervars',
                            'Error trying to log-in the user' . $uid,
                            \OC_Log::DEBUG);
	}

 	\OC::$REQUESTEDAPP = '';
	\OC_Util::redirectToDefaultPage();
}
