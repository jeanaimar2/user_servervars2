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
namespace OCA\User_Servervars2\Backend;

use OCA\User_Servervars2\Service\Context;
use OCA\User_Servervars2\Backend\UserBackend;

class UserBackendTest extends \PHPUnit_Framework_TestCase {

	var $backend;
	var $userService;

	public function setUp() {
		
		$this->userService = $this->getMockBuilder('OCA\User_Servervars2\Service\UserService')
								->disableOriginalConstructor()
								->getMock();

		$this->backend = new UserBackend( $this->userService );

	}


	public function testCheckPassword() {
		//__GIVEN__
		$this->configUserService( array('checkTokens' => true, 'getUserIdFromToken' => 'jean.gabin@myidp.org'));

		//__WHEN__
		$returnedValue = $this->backend->checkPassword('jean.gabin@myidp.org','mlkdfmuxm');

		//_THEN__
		$this->assertEquals('jean.gabin@myidp.org', $returnedValue);
	}

	public function testCheckPasswordNoUid() {
		//__GIVEN__
		$this->configUserService( array('checkTokens' => true, 'getUserIdFromToken' => 'jean.gabin@myidp.org'));

		//__WHEN__
		$returnedValue = $this->backend->checkPassword('','mlkdfmuxm');

		//_THEN__
		$this->assertFalse($returnedValue);
	}	

	public function testCheckPasswordNotSameUid() {
		//__GIVEN__
		$this->configUserService( array('checkTokens' => true, 'getUserIdFromToken' => 'another@myidp.org'));

		//__WHEN__
		$returnedValue = $this->backend->checkPassword('jean.gabin@myidp.org','mlkdfmuxm');

		//_THEN__
		$this->assertFalse($returnedValue);
	}	


	public function testCheckPasswordCheckFailed() {
		//__GIVEN__
		$this->configUserService( array('checkTokens' => false, 'getUserIdFromToken' => 'jean.gabin@myidp.org'));

		//__WHEN__
		$returnedValue = $this->backend->checkPassword('jean.gabin@myidp.org','mlkdfmuxm');

		//_THEN__
		$this->assertFalse($returnedValue);
	}	


	function configUserService($__) {
		$this->userService->expects($this->any())->method('checkTokens')->willReturn( $__['checkTokens']);
		$this->userService->expects($this->any())->method('getUserIdFromToken')->willReturn($__['getUserIdFromToken']);
	}
}