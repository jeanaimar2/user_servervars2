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
 use OCA\User_Servervars2\Service\IContext;

 class UserServiceTest extends \PHPUnit_Framework_TestCase {
 	
 	var $service;
 	var $context;
 	var $endWithCallBack;

 	/**
 	*
 	*/
 	protected function setUp(){
 		
 		$this->context = $this->getMock('OCA\User_Servervars2\Service\IContext');
 		$this->assertTrue($this->context instanceof IContext);
 		$this->service = new UserService($this->context);

 		// - callback
 		$this->endWithCallBack = function($uid,$provider) {
 					if ( empty($provider)  ) return true;
	 				$provider = '@'.$provider;
	 				return substr($uid, -strlen($provider)) === $provider;
	 			};
 	}

 	public function testCheckTokens() {
	 	//__WHEN__
	 	$uid = 'jean.gabin@myidp.org'; 
	 	$provider = 'myidp.org';
 		$this->setUpContext($uid, $provider, $this->endWithCallBack);

	 	//__THEN__
	 	$this->assertTrue( $this->service->checkTokens() );
 	}

 	/**
 	 * If uid doesn't match provider
 	 *
 	 **/
 	public function testCheckTokensError() {
	 	//__WHEN__
	 	$uid = 'jean.gabin@foo.myidp.org'; 
	 	$provider = 'myidp.org';
 		$this->setUpContext($uid, $provider, $this->endWithCallBack);

	 	//__THEN__
	 	$this->assertFalse( $this->service->checkTokens() );
 	}


 	public function testCheckTokensNoProvider() {
	 	//__WHEN__
	 	$uid = 'jean.gabin@myidp.org'; 
	 	$provider = '';
 		$this->setUpContext($uid, $provider, $this->endWithCallBack);

	 	//__THEN__
	 	$this->assertTrue( $this->service->checkTokens() );
 	}



 	/**
 	 * undocumented function
 	 *
 	 * @return void
 	 * @author 
 	 **/
 	function setUpContext($uid, $provider, $callback) {
 				// : isUserMatchingProviderCallBack
 		$this->context->expects($this->any())
	 			->method('isUserMatchingProviderCallBack')
	 			->will( $this->returnValue($callback));
	 	// 
	 	$this->context->expects($this->any())
	 			->method('getUserId')
	 			->willReturn( $uid );

	 	$this->context->expects($this->any())
	 			->method('getProvider')
	 			->willReturn( $provider );

 	}

 	/**
 	*
 	**/
 	public function testEndWith() {
	 	$this->assertTrue(call_user_func($this->endWithCallBack, 'jean.gabin@myidp.org', 'myidp.org'));
 	}
 }