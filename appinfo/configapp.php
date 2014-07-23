<?php
/**
 * ownCloud - UserShibbApp.php
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
namespace OCA\User_Servervars2\AppInfo;
 
use \OCP\AppFramework\App;
use \OCA\User_Servervars2\Service\TokenService;
use \OCA\User_Servervars2\Hook\ServerVarsHooks;




class ConfigApp extends App {

	public function __construct(array $urlParams=array()){
		parent::__construct('gtu', $urlParams);

		$container = $this->getContainer();

		// Controller
		$container->registerService('PageController', function ($c) {
			return  new PageController();
		});		


		$container->registerService('Context', function ($c) {
			return new RemoteContext();
		});	
		
		// Service
		$container->registerService('TokenService', function ($c) {
			return  new TokenService(
				$c->query('Context'),  
				$c->query('MetadataProvider')
			);
		});

		// Hooks
		$container->registerService('ServerVarsHooks', function ($c) {
			return  new ServerVarsHooks();
		});

		// Backend
		$container->registerService('UserBackend', function ($c) {
			return  new UserBackend(		
				$c->query('TokenService')
			);
		});

		// MetadataProvider
				// Backend
		$container->registerService('MetadataProvider', function ($c) {
			return new MetadataProvider(
				$c->query('MetadataMapper')
			);
		});	

		// Mappers
		$container->registerService('MetadataMapper', function ($c) {
			return  new MetadataMapper();
		});		

	}

	public function getUserSession() {
		return $this->getContainer()->getServer()->getUserSession();
	}
	
	public function getUser() {
		return $this->getContainer()->getServer()->getUserSession()->getUser();
	}

	public function getUserManager() {
		return $this->getContainer()->getServer()->getUserManager();
	}

	public function getUrlGenerator() {
		return $this->getContainer()->getServer()->getUrlGenerator();
	}

	/**
	 * @return /OCP/IConfig
	 */
	public function getConfig() {
		return $this->getServer()->getConfig();
	}
}
