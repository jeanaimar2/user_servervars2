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

class ProxyUserAndGroupService implements UserAndGroupService {

	var $userManager;
	var $groupManager;
	var $config;
	var $backend;

	function __construct($userManager, $groupManager, $backend, $config) {
		$this->tokenService = $tokenService;
		$this->userManager = $userManager;
		$this->backend = $backend;
		$this->config = $config;
	}	



	public function userExists($uid) {
		return $this->userManager->userExists($uid);
	}

	public function isAutoCreateUser() {
		 $this->backend->isAutoCreateUser() 
	}

	public function isUpdateUserData() {
		$this->backend->isUpdateUserData()
	}

	/**
	 * Cf. 	OC_User_Backend::OC_USER_BACKEND_GET_DISPLAYNAME => 'getDisplayName',
	 *	    OC_User_Backend::OC_USER_BACKEND_SET_DISPLAYNAME => 'setDisplayName',
	 */
	protected function udpateDisplayName($uid, displayName) {
				// Update if not the same
		if ( $displayName !== $this->backend->getDisplayName($uid) )  {
			$this->backend->setDisplayName( $displayName );
		}
	}

		/**
	 * Email is set in preferences (WTF ?)
	 */
	protected function updateMail($uid, $email) {
		$existingEmail = $this->config->getUserValue($uid, 'settings', 'email');
		if ( $email !== $existingEmail ) {
			$this->config->setUserValue($uid, 'settings', 'email', $email);
		}
	}

	protected function updateGroup($uid, $justCreated) {
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

}