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

namespace OCA\User_Servervars2\Hook;

use \OC\User\User;

class ServerVarsHooks {


	var $tokenService;
	var $userManager;
	var $backend;

	function __construct($tokenService, $userManager,$backend) {
		$this->tokenService = $tokenService;
		$this->userManager = $userManager;
		$this->backend = $backend;
	}

	function onPostLogin(User $user) {
		$uid = $user->getUID();

		$just_created = false;
		if ( $uid === $this->tokenService->checkTokens() ) {
			if (  $this->backend->autocreate && 
				! $this->userManager->userExist($uid)) {
				$just_created = $this->createUser($user);
			}

			if ( $just_created || $this->backend->updateUserData ) {
				$displayName = $this->tokenService->getDisplayName();
				// Update if not the same
				if ( $displayName !== $user->getDisplayName() )  {
					$user->setDisplayName( $displayName );
				}
			}
		}

	}


	/**
	 * 
	 *
	 */
	protected function createUser(User $user) {
		$uid = $user->getUID();
		$randomPaswword = $this->newRandomPassword();
		/*
		* userManager check uid validity and throws Exception if $uid is 
		* already used
		*/
		$this->userManager->createUser($uid, $randomPaswword);

	}


	/**
	 *
	 * @return String a random password
	 */
	function newRandomPassword() {
		$valid_chars
		= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789&#!@%*?';
		$length = 20;
		$ind_max_valid_chars = strlen($valid_chars) - 1;

		$random_string = '';
		for ($i = 0; $i < $length; $i++) {
			$random_pick = mt_rand(0, $ind_max_valid_chars);
			$random_char = $valid_chars[$random_pick];
			$random_string .= $random_char;
		}

		return $random_string;
	}
}