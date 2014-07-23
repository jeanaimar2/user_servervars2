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

use \OCP\User;


class ServerVarsHooks {

	var $tokenService;
	var $userManager;
	var $groupManager;
	var $config;
	var $backend;

	function __construct($tokenService, $userManager, $backend, $config) {
		$this->tokenService = $tokenService;
		$this->userManager = $userManager;
		$this->backend = $backend;
		$this->config = $config;
	}

	function onPostLogin($uid, $password) {

		$justCreatedUser = null;
		if ( $uid === $this->tokenService->checkTokens() ) {
			if (  $this->backend->isAutoCreateUser() && 
				! $this->userManager->userExists($uid)) {
				$justCreatedUser = $this->createUser($uid);
			}

			if ( $justCreatedUser || $this->backend->isUpdateUserData() ) {
				$this->udpateDisplayName( $uid );
				$this->updateMail( $uid );
				$this->updateGroup( $uid );
			}
		}

	}

	/**
	 * Cf. 	OC_User_Backend::OC_USER_BACKEND_GET_DISPLAYNAME => 'getDisplayName',
	 *	    OC_User_Backend::OC_USER_BACKEND_SET_DISPLAYNAME => 'setDisplayName',
	 */
	protected function udpateDisplayName($uid) {
		$displayName = $this->tokenService->getDisplayName();
				// Update if not the same
		if ( $displayName !== $this->backend->getDisplayName($uid) )  {
			$this->backend->setDisplayName( $displayName );
		}
	}

	/**
	 * Email is set in preferences (WTF ?)
	 */
	protected function updateMail($uid) {
		$email = $this->tokenService->getEmail();
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


	protected function cleanGroups($groups, $oldGroups) {
		$protected_groups = $this->backend->getProtectedGroups();
		foreach($oldGroups as $group) {
				if(!in_array($group, $protected_groups) && !in_array($group, $groups)) {
					$groupObject = $this->groupManager->get($group);
				OC_Group::removeFromGroup($uid, $group);
			}
	}


	function update_groups($uid, $groups,
		$protected_groups='', $just_created=false) {

		$groups = preg_split('/[, ]+/', $groups, -1, PREG_SPLIT_NO_EMPTY);
		$protected_groups = preg_split('/[, ]+/', $protected_groups, -1,
			PREG_SPLIT_NO_EMPTY);

		if(!$just_created) {
			$old_groups = OC_Group::getUserGroups($uid);
			foreach($old_groups as $group) {
				if(!in_array($group, $protected_groups)
					&& !in_array($group, $groups)) {
					OC_Log::write('servervars',
						'Remove "'
						. $uid
						. '" from group "'
						. $group
						.'"',
						OC_Log::DEBUG);
				OC_Group::removeFromGroup($uid, $group);
			}
		}
	}

	foreach($groups as $group) {
		if (preg_match('/[^a-zA-Z0-9 _.@-]/', $group)) {
			OC_Log::write('servervars',
				'Invalid group "'
				. $group
				. '": allowed chars [a-zA-Z0-9_.@-]',
				OC_Log::DEBUG);
		}
		elseif (!OC_Group::inGroup($uid, $group)) {
			if (!OC_Group::groupExists($group)) {
				OC_Log::write('servervars',
					'Adding new group: ' . $group,
					OC_Log::DEBUG);
				OC_Group::createGroup($group);
			}
			OC_Log::write('servervars',
				'Adding "'
				. $uid
				. '" to group "'
				. $group
				.'"',
				OC_Log::DEBUG);
			OC_Group::addToGroup($uid, $group);
		}
	}
}

	/**
	 * 
	 * @return created user or false
	 */
	protected function createUser($uid) {
		$randomPaswword = $this->newRandomPassword();
		/*
		* userManager check uid validity and throws Exception if $uid is 
		* already used
		*/
		return $this->userManager->createUser($uid, $randomPaswword);

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