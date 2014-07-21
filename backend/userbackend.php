<?php
/**
 * ownCloud - usershibbbackend.php
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
 use OCA\User_Servervars2\Service\IContext;

class UserBackend extends OC_User_Backend {


	var $userService;


	public function __construct($userService) {
		$this->userService = $userService;
	}
	/**
	* @see \OC\User\manager::checkPassword 
	*/
	public function implementsActions($actions) {
		return (bool)(
			(
			  OC_USER_BACKEND_CHECK_PASSWORD
			| OC_USER_BACKEND_GET_DISPLAYNAME
			)
			& $actions);
	}

	public function checkPassword($uid, $password) {
		$uidFromToken =  $this->userService->checkTokens();
		if ( $uid === $uidFromToken ) return $uid;
		return false;
	}

	public function getDisplayName($uid) {
		return $this->userService->
	}
}
