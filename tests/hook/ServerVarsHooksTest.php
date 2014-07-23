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
use OCA\User_Servervars2\Hook\ServerVarsHooks;


class ServerVarsHooksTest extends \PHPUnit_Framework_TestCase {

	var $hooks;
	var $tokenService; 
	var $userManager;
	var $groupManager;
	var $backend;
	var $config;

	var $user;

	public function setUp() {
		$this->tokenService = $this->getMockBuilder('OCA\User_Servervars2\Service\TokenService')
								->disableOriginalConstructor()
								->getMock();

		$this->userManager 	= $this->getMockBuilder('\OC\User\Manager')
								->disableOriginalConstructor()
								->getMock();

		$this->groupManager 	= $this->getMockBuilder('\OC\Group\Manager')
								->disableOriginalConstructor()
								->getMock();								

		$this->backend 		= $this->getMockBuilder('\OCA\User_Servervars2\Backend\UserBackend')
								->disableOriginalConstructor()
								->getMock();

		$this->config 		= $this->getMock('\OCP\IConfig');

		$this->hooks 		= new ServerVarsHooks(	$this->tokenService,
													$this->userManager,
													$this->groupManager,
													$this->backend,
													$this->config);

		$this->user = $this->getMockBuilder('\OC\User\User')
								->disableOriginalConstructor()
								->getMock();

	}


	/**
	 * Test in mock mode.
	 */
	public function testOnPostLoginCreateUser() {
		//__GIVEN__
		// Backend create but doesn't update
		$this->backend->expects( $this->any() )->method('isAutoCreateUser')->willReturn(true);
		$this->backend->expects( $this->any() )->method('isUpdateUserData')->willReturn(false);

		$this->tokenService->expects( $this->once() )->method('checkTokens')->willReturn( 'uid@myidp.org' );
		$this->userManager->expects( $this->once() )->method('userExists')->with('uid@myidp.org')->willReturn( false );
		$this->userManager->expects( $this->once() )->method('createUser')->willReturn( $this->user );

		// update : 
		$this->tokenService->expects( $this->once() )->method('getDisplayName')->willReturn( 'Jean GABIN' );

		// --> displayName
		$this->backend->expects( $this->once() )->method('getDisplayName')->with('uid@myidp.org')->willReturn( null );
		$this->backend->expects( $this->once() )->method('setDisplayName')->with('uid@myidp.org');

		// --> eMail
		$this->config->expects( $this->once() )->method('getUserValue')->with( 'uid@myidp.org', 'settings', 'email')->willReturn(false);
		$this->config->expects( $this->once() )->method('setUserValue')->with( 'uid@myidp.org', 'settings', 'email');


		//__THEN__
		$this->hooks->onPostLogin('uid@myidp.org', 'password');

	}


}