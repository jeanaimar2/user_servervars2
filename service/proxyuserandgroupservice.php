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
namespace OCA\User_Servervars2\Service;
use \OC_User;

class ProxyUserAndGroupService implements UserAndGroupService {

	var $userManager;
	var $groupManager;
	var $config;
	var $backend;

	function __construct($tokenService, $userManager, $groupManager, $backend, $config) {
		$this->tokenService = $tokenService;
		$this->userManager = $userManager;
		$this->backend = $backend;
		$this->config = $config;
	}	

	/**
	 * 
	 * @return created user or false
	 */
	public function provisionUser($uid) {
		$this->userManager->delete($uid);
		if (  ! $this->userManager->userExists($uid)) {
			$randomPaswword = $this->newRandomPassword();
			//return OC_User::createUser($uid, $randomPaswword);
			return $this->userManager->createUser($uid, $randomPaswword);
		}
	}


	public function isAutoCreateUser() {
		return $this->backend->isAutoCreateUser(); 
	}

	public function isUpdateUserData() {
		return $this->backend->isUpdateUserData();
	}

	/**
	 * Cf. 	OC_User_Backend::OC_USER_BACKEND_GET_DISPLAYNAME => 'getDisplayName',
	 *	    OC_User_Backend::OC_USER_BACKEND_SET_DISPLAYNAME => 'setDisplayName',
	 */
	public function updateDisplayName($uid, $displayName) {
				// Update if not the same
		if ( $displayName !== OC_User::getDisplayName($uid) )  {
			OC_User::setDisplayName( $uid, $displayName );
		}
	}

		/**
	 * Email is set in preferences (WTF ?)
	 */
	public function updateMail($uid, $email) {
		$existingEmail = $this->config->getUserValue($uid, 'settings', 'email');
		if ( $email !== $existingEmail ) {
			$this->config->setUserValue($uid, 'settings', 'email', $email);
		}
	}

	public function updateGroup($uid, $justCreated) {
		return;
		$groups 		= $this->tokenService->getGroupsFromToken();
		$defaultGroups 	= $this->backend->getDefaultGroups();
		
		if (empty($groups) && !empty($defaultGroups)) {
            $groups = $defaultGroups;
        }

        if ($groups !== false) {

        	if ( ! $justCreated ) {
        		$oldGroups = $this->groupManager->getUserGroups( $justCreated );
        		$this->cleanGroups($groups, $oldGroups);
        	}
        	$this->updateGroups();
        }

	}	

	public function login($uid) {
		return \OC_User::login($uid, '');
	}


	public function isLoggedIn() {
		return \OC_User::isLoggedIn();
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