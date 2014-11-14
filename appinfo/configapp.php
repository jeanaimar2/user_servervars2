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




/**
 *
 */
class ConfigApp extends App {

	public function __construct(array $urlParams=array()){
		parent::__construct('user_servervars2', $urlParams);

		$container = $this->getContainer();

		// Controller
/*		$container->registerService('PageController', function ($c) {
			return  new PageController();
		});	*/	

		$container->registerService('TokensFactory', function($c) {
			return new \OCA\User_Servervars2\Service\TokensFactory( 
				$c->query('ServerContainer')->getAppConfig()
				);
		});

		$container->registerService('Tokens', function ($c) {
			return $c->query('TokensFactory')->getTokens();
		});	
		
		// Service
		$container->registerService('TokenService', function ($c) {
			return  new \OCA\User_Servervars2\Service\TokenService(
				$c->query('Tokens')
			);
		});


		$container->registerService('GroupManager', function($c) {
			return \OC_Group::getManager();
			// return new \OC\Group\Manager(
			// 		$c->query('ServerContainer')->getUserManager()
			// 	);
		});

		// Service
		$container->registerService('UserAndGroupService', function ($c) {
			return  new \OCA\User_Servervars2\Service\ProxyUserAndGroupService(
				$c->query('ServerContainer')->getUserManager(),
				$c->query('GroupManager'),
				$c->query('GroupNamingServiceFactory')->getGroupNamingService(),
				$c->query('UserBackend'),
				$c->query('ServerContainer')->getConfig()
			);
		});

		$container->registerService('GroupNamingServiceFactory', function ($c) {
			return new \OCA\User_Servervars2\Service\GroupNamingServiceFactory(
				$c->query('ServerContainer')->getAppConfig()
			);
		});

		// Interceptor
		$container->registerService('Interceptor', function ($c) {
			return  new \OCA\User_Servervars2\AppInfo\Interceptor(
				$c->query('ServerContainer')->getAppConfig(),  
				$c->query('Tokens'),
				$c->query('UserAndGroupService'),
				$this->getUrlGenerator()
			);
		});

		// Hooks
		$container->registerService('ServerVarsHooks', function ($c) {
			return  new \OCA\User_Servervars2\Hook\ServerVarsHooks(
				$c->query('TokenService'),
				$c->query('UserAndGroupService'),
				$c->query('ServerContainer')->getAppConfig()
			);
		});

		// Backend
		$container->registerService('UserBackend', function ($c) {
			return  new \OCA\User_Servervars2\Backend\UserBackend(		
				$c->query('TokenService'),
				$c->query('ServerContainer')->getAppConfig()
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

		// Mappers
		$container->registerService('SettingsController', function ($c) {
			return new \OCA\User_Servervars2\Controller\SettingsController(
				$c->query('Request'),
				$c->query('ServerContainer')->getAppConfig()
			);
		});	


		$container->registerService('DeferredController', function ($c) {
			return new \OCA\User_Servervars2\Controller\DeferredController(
				$c->query('Request'),
				$c->query('TokenService'),
				$c->query('UserAndGroupService'),
				$c->getServer()->getUserSession()->getUser(),
				$c->query('ServerContainer')->getAppConfig()
			);
		});


	}


	public function getTokens() {
		return $this->getContainer()->query('Tokens');
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

	public function getGroupManager() {
		return $this->getContainer()->query('GroupManager');
	}

	public function getUrlGenerator() {
		return $this->getContainer()->getServer()->getUrlGenerator();
	}

	/**
	 * @return /OCP/IConfig
	 */
	public function getConfig() {
		return $this->getContainer()->getServer()->getConfig();
	}

	public function getAppConfig() {
		return $this->getContainer()->getServer()->getAppConfig();
	}
}
