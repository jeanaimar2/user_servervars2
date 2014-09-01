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
namespace OCA\User_Servervars2\AppInfo;


class Interceptor {


	var $tokens;
	var $appConfig;
	var $uag;


	function __construct($appConfig, $tokens, $userAndGroupService) {
		$this->appConfig = $appConfig;
		$this->tokens = $tokens;
		$this->uag = $userAndGroupService;
	}
	/**
	* To avoid infinite loop it used TWO differents app parameter
	*/
	function checkApp($parm) {
		return (isset($_GET['app']) && $_GET['app'] == $parm);
	}	




	function run() {

		if( $this->checkApp('usv2') ) {

			$uid = $this->tokens->getUserId();

			if ( $uid === false ) {
				if (  $this->getAppConfig()->getValue('user_servervars2','stop_if_empty',false) ) {
					throw new \Exception('token error');
				}
				$ssoURL = $this->getAppConfig()->getValue('user_servervars2', 'sso_url', 'http://localhost/sso');
				\OCP\Response::redirect($ssoURL);
				exit();
			} 

			$isLoggedIn = $this->uag->isLoggedIn();

			if ( ! $isLoggedIn ) {
				$isLoggedIn = $this->uag->login($uid); 
			}
			if ( !$isLoggedIn || !$this->uag->isLoggedIn())  {
				\OC_Log::write('servervars',
					'Error trying to log-in the user' . $uid,
					\OC_Log::DEBUG);
			}

			\OC::$REQUESTEDAPP = '';
			\OC_Util::redirectToDefaultPage();
		}
	}
}