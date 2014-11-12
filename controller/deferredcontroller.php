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
/**
*
*/
class DeferredController extends Controller {

	public function __construct($request, $tokenService, $userAndGroupService, $appConfig, $redirector=null ) {
		parent::__construct('user_servervars2', $request);
		$this->tokenService = $tokenService;
		$this->userAndGroupService = $userAndGroupService;
		$this->redirector = $redirector;
		if ( $this->redirector === null ) {
			$this->redirector = new \OCA\User_Servervars2\AppInfo\DefaultRedirector();
		}
		$this->appConfig = $appConfig;
	}


	/**
	 * @NoAdminRequired
	 */
	public function provisionning() {
		if ( $uid === $this->tokenService->checkTokens() ){
			$uag = $this->userAndGroupService;
			$justCreatedUser = $uag->provisionUser($uid, $this->tokenService->getTokens() );
			return new TemplateResponse(
				$this->appName,
				"provisionning",
				array()
			);
		} else {
			throw new \Exception("Security problem");
		}

	}
}