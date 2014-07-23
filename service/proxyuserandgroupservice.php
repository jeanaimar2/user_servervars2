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



	public function userExists($uid) {
		return $this->userManager->userExists($uid);
	}

	public function isAutoCreateUser() {
		 $this->backend->isAutoCreateUser() 
	}

	public function isUpdateUserData() {
		$this->backend->isUpdateUserData()
	}

	protected function udpateDisplayName($uid, displayName) {
				// Update if not the same
		if ( $displayName !== $this->backend->getDisplayName($uid) )  {
			$this->backend->setDisplayName( $displayName );
		}
	}

}