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
	var $userAndGroupService;
/*	var $userManager;
	var $groupManager;
	var $config;
	var $backend;*/

	function __construct($tokenService, $userManager, $backend, $config) {
		$this->tokenService = $tokenService;
		$this->userManager = $userManager;
		$this->backend = $backend;
		$this->config = $config;
	}

	function onPostLogin($uid, $password) {

		$justCreatedUser = null;
		$uag = $this->userAndGroupService;

		if ( $uid === $this->tokenService->checkTokens() ) {
			if ( $uag->isAutoCreateUser() &&   
				! $uag->userExists($uid)) {
				$justCreatedUser = $this->createUser($uid);
			}
//			if ( $justCreatedUser || $this->backend->isUpdateUserData() ) {

			if ( $justCreatedUser || $uag->isUpdateUserData() ) {
				$uag->udpateDisplayName( $uid, $this->tokenService->getDisplayName() );
				$uag->updateMail( $uid ,  $this->tokenService->getEmail());
				$uag->updateGroup( $uid, $this->tokenService->getGroupsFromToken() );
			}
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