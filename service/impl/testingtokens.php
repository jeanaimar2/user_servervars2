<?php
/**
 * ownCloud - Context
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
namespace OCA\User_Servervars2\Service\Impl;

use OCA\User_Servervars2\Service\Tokens;

class TestingTokens implements Tokens {

	/**
	 * undocumented class variable
	 *
	 * @var appConfig
	 **/
	var $appConfig;

	function __construct($appConfig) {
		$this->appConfig = $appConfig;
	}

	private function getParam($key, $default) {
		return $this->appConfig->getValue('user_servervars2', $key, $default);
	}

 	/**
 	 * Return the identity provider ( as 'https://idp.example.org/idp/shibboleth')
 	 * @return provider name or false if none
 	 */
 	public function getProviderId(){
 		return $this->getParam('ttokens_provider_id', 'http://myidp.foo');
 	}
 	/**
 	 * undocumented function
 	 *
 	 * @return user id or false is none
 	 * @author 
 	 **/
 	public function getUserId() {
 		return $this->getParam('ttokens_user_id', 'foo');
 	}

 	public function getDisplayName(){
 		return $this->getParam('ttokens_display_name', 'bar');
 	}

 	public function getEmail() {
 		return $this->getParam('ttokens_email', 'bar@foo.org');
 	}

 	public function getGroups() {
 		return explode( '|', $this->getParam('ttokens_groups', 'foogrp|bargrp') );
 	}

 }