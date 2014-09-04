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

class PrependGroupNammingServiceTest extends \PHPUnit_Framework_TestCase {

	var $service;

	public function setUp() {
		$this->service = new \OCA\User_Servervars2\Service\Impl\PrependGroupNamingService(
				'@', 
				array('ou'=> 'grp', 'o' => 'org')
			);
	}


	public function testPrepend() {
		$this->assertEquals('grp@', $this->service->prepend('ou'));
	}

	public function testGetName() {
		$this->assertEquals('grp@tokyo', $this->service->getName('ou', 'tokyo'));
	}

	public function testIsValid() {
		$this->assertTrue( $this->service->isValid('grp@kyoto'));
		$this->assertTrue( $this->service->isValid('org@japan'));

		$this->assertFalse( $this->service->isValid('grp_kyoto'));
		$this->assertFalse( $this->service->isValid('ORG@japan'));

	}



}
