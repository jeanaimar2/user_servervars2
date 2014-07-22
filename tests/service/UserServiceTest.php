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
 use OCA\User_Servervars2\Service\Context;
 use OCA\User_Servervars2\Backend\MetadataProvider;


 class TokenServiceTest extends \PHPUnit_Framework_TestCase {
 	
 	var $service;
 	var $context;
 	var $metadataProvider;
 	var $scopeValidator;

 	/**
 	*
 	*/
 	protected function setUp(){
 		
 		$this->context = $this->getMock('OCA\User_Servervars2\Service\Context');
 		$this->metadataProvider = $this->getMock('OCA\User_Servervars2\Backend\MetadataProvider');
 		$this->scopeValidator = $this->getMock('OCA\User_Servervars2\Backend\scopeValidator');
	 	$this->service = new TokenService($this->context, $this->metadataProvider);
 	}


 	public function testCheckTokensEmpty() {
	 	//__WHEN__
	 	$uid = ''; 
	 	$provider = 'myidp.org';
 		$this->setUpContext($uid, null, null);

	 	//__THEN__
	 	$this->assertFalse( $this->service->checkTokens() );
 	}


 	/**
 	 * If uid doesn't match provider
 	 *
 	 **/
 	public function testCheckTokensOk() {
 		//__GIVEN__
 		$this->scopeValidator->expects($this->any())
	 			->method('valid')
	 			->willReturn( true );
	 	//__WHEN__
	 	$uid = 'jean.gabin@myidp.org'; 
	 	$provider = 'myidp.org';
 		$this->setUpContext($uid, $provider, $this->scopeValidator);

	 	//__THEN__
	 	$this->assertTrue( $this->service->checkTokens() );
 	}
 	/**
 	 * If uid doesn't match provider
 	 *
 	 **/
 	public function testCheckTokensError() {
 		//__GIVEN__
 		$this->scopeValidator->expects($this->any())
	 			->method('valid')
	 			->willReturn( false );
	 	//__WHEN__
	 	$uid = 'jean.gabin@foo.myidp.org'; 
	 	$provider = 'myidp.org';
 		$this->setUpContext($uid, $provider, $this->scopeValidator);

	 	//__THEN__
	 	$this->assertFalse( $this->service->checkTokens() );
 	}


 	public function testCheckTokensNoProvider() {
	 	//__WHEN__
	 	$uid = 'jean.gabin@myidp.org'; 
	 	$provider = '';
 		$this->setUpContext($uid, $provider, $this->scopeValidator);

	 	//__THEN__
	 	$this->assertFalse( $this->service->checkTokens() );
 	}



 	/**
 	 * undocumented function
 	 *
 	 * @return void
 	 * @author 
 	 **/
 	function setUpContext($uid, $provider, $validator) {
 				// : isUserMatchingProviderCallBack
 		$this->metadataProvider->expects($this->any())
	 			->method('getScopeValidator')
	 			->will( $this->returnValue($validator));

 		$this->metadataProvider->expects($this->any())
	 			->method('getUserIdAttributeName')
	 			->will( $this->returnValue('eppn'));	 			
	 	// 
	 	$this->context->expects($this->any())
	 			->method('getUserId')
	 			->willReturn( $uid );

	 	$this->context->expects($this->any())
	 			->method('getProviderId')
	 			->willReturn( $provider );

 	}

 }