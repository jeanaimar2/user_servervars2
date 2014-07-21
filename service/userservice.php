<?php
/**
 * ownCloud - usershibbservice.php
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

 use OCA\User_Servervars2\Backend\MetadataProvider;
 use OCA\User_Servervars2\Service\Context;

 class UserService {

 	var $context;
 	var $metadataProvider;

 	public function __construct(Context $context, MetadataProvider $metadataProvider) {
 		$this->context = $context;
		$this->metadataProvider = $metadataProvider;
 	}


 	public function checkTokens() {
 		
 		$uid = $this->context->getUserId();
 		if ( empty($uid)) {
 			return false;
 		}

 		$providerId 	= $this->context->getProviderId();
 		if ( empty($providerId)) {
 			return false;
 		}
 		$attributeName  = $this->metadataProvider->getUserIdAttributeName($providerId);
 		$scopeValidator = $this->metadataProvider->getScopeValidator($providerId, $attributeName);
 		if ( $scopeValidator ) {
 			return $scopeValidator->valid(array($uid));
 		}
 		return true;
 	}

 	public function getUserIdFromToken() {
 		return $this->context->getUserId();
 	}

 }