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

class GroupNamingServiceFactory {

	function __construct($appConfig) {
		$this->className = $appConfig->getValue('user_servervars2', 'group_naming_factory', 'OCA\User_Servervars2\Service\Impl\PrependGroupNamingService');
	}


	function getGroupNamingService() {
		try { 
 			$r = new \ReflectionClass($className);
 			$object = $r->newInstance( $this->appConfig );
 			return $object;
 		} catch(\ReflectionException $e) {
 			\OCP\Util::writeLog('User_Servervars2',"Class not found exception $className, use \OCA\User_Servervars2\Service\Impl\MuteGroupNamingService instead", \OCP\Util::ERROR);
 			$r = new \ReflectionClass('\OCA\User_Servervars2\Service\Impl\MuteTokens');
 			$object = $r->newInstance( $this->appConfig );
 			return $object;
 		}
	}
}