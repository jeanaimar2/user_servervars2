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
namespace OCA\User_servervars2\Controller;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCA\User_servervars2\Lib\ConfigHelper;
use OCP\AppFramework\Http;


class SettingsControllerTest extends \PHPUnit_Framework_TestCase {

	var $controller;
	var $request;
	var $appConfig;

	public function setUp() {
		$this->request = $this->getMockBuilder('\OCP\IRequest')
			->getMock();

		$this->controller = new SettingsController($this->request, null);
	}


	public function testProceedConfigClass() {
		$array = array();
		$this->controller->proceedConfig($array, 'something_class', 'OCA\User_servervars2\Controller\SettingsControllerTest');
		try {
			$this->controller->proceedConfig($array, 'something_class', '\SettingsControllerTestFoo');
			$this->fail("Must throw exception");
		} catch(\Exception $e) {
			$this->assertEquals("Class not found \SettingsControllerTestFoo", $e->getMessage());
		}
	}


	public function testProceedConfigFile() {
		$array = array();
		$this->assertTrue(file_exists( __DIR__.DIRECTORY_SEPARATOR.'SettingsControllerTest.php'));
		$this->controller->proceedConfig($array, 'something_conf', join(DIRECTORY_SEPARATOR,array('controller', 'settingscontroller.php')));
		try {
			$this->controller->proceedConfig($array, 'something_conf', __DIR__.DIRECTORY_SEPARATOR.'SettingsControllerTestFOO.php');
			$this->fail("Must throw exception");
		} catch(\Exception $e) {
			$this->assertEquals("File not found", substr($e->getMessage(),0,strlen("File not found")));
		}
	}

	// public function testProceedConfigURLOk() {
	// 	$array = array();
	// 	$this->controller->proceedConfig($array, 'something_url', "http://www.owncloud.org/?arg=1");
	// }


	// public function testProceedRELATIVEConfigURLOk() {
	// 	$array = array();
	// 	$this->controller->proceedConfig($array, 'something_url', "/index.php/?arg=1");
	// }

	// public function testProceedConfigURLKo() {
	// 	$array = array();
	// 	try {
	// 		$this->controller->proceedConfig($array, 'something_url', "htp://www.owncloud.org/?arg=1");
	// 		$this->fail("Must throw exception");
	// 	} catch(\Exception $e) {
	// 		$this->assertEquals("File not found", substr($e->getMessage(),0,strlen("File not found")));
	// 	}
	// }


}