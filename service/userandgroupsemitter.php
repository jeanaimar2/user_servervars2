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
namespace OCA\User_ServerVars2\Service;


class UserAndGroupsEmitter extends \OC\Hooks\BasicEmitter {

	public static $emitter;


	public static function getInstance() {
		if ( is_null(self::$emitter) ) {
			self::$emitter = new UserAndGroupsEmitter();
		}
		return self::$emitter;
	}


	/**
	* 'OCA/UserChange/', 'postChangeDisplayName', $callback($uid, $newDisplayName, $oldDisplayName)
	*/
	public function emitPostChangeDisplayName($uid, $newDisplayName, $oldDisplayName) {
		$this->emit('OCA/UserChange/', 'postChangeDisplayName', array($uid, $newDisplayName, $oldDisplayName));
	}

	/**
	* 'OCA/UserChange/', 'postChangeMail', $callback($uid, $newmail, $oldMail)
	*/
	public function emitPostChangeMail($uid, $newMail, $oldMail) {
		$this->emit('OCA/UserChange/', 'postChangeMail', array($uid, $newMail, $oldMail));
	}
	
}