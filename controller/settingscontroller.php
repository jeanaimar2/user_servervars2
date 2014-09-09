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
class SettingsController extends Controller {

	var $appConfig;
	var $directories;

	public function __construct($request, $appConfig,$directories=null) {
		parent::__construct('user_servervars2', $request);
		$this->appConfig = $appConfig;
		if ( is_null($directories)) {
			$directories=array(realpath(join(DIRECTORY_SEPARATOR,array(__DIR__, '.','..' ))));
		}
		$this->directories = $directories;

	}



	/**
	* @Ajax
	*/
	public function setConfig() {
		\OC_Util::checkAdminUser();
		$params = $this->request->post;
		$key = isset($params['key']) ? $params['key'] : null;
		$value = isset($params['value']) ? $params['value'] : null;

		$array = array();


		if ( is_null($key)) {
			return new JSONResponse( array("msg" => "Key is null"), Http::STATUS_BAD_REQUEST);
		}
		try {
			$this->proceedConfig($array, $key, $value);
		} catch(\Exception $e) {
			return new JSONResponse( array("msg" => $e->getMessage()), Http::STATUS_BAD_REQUEST);	
		}
		
		$this->appConfig->setValue('user_servervars2', $key, $value);

		return new JSONResponse( $array );
	}



	function proceedConfig(&$array, $key, $value) {
		$helper = new ConfigHelper();

		$endsWith = function($v,$end) use($helper) {
			return $helper->endsWith($v, $end);
		};

		if ( $endsWith($key, 'class') ) {
			if ( ! class_exists($value) ) {
				throw new \Exception("Class not found $value");
			}
		} else if ( $endsWith($key, 'conf') ) {
			
			$file = $helper->getPath($value);
			//-----------------------------------------------------
			// Must end with json extension
			//-----------------------------------------------------
			if ( ! $endsWith($file, '.json'))  {
				throw new \Exception("File '$file' is not a '.json' file");
			}		
			//-----------------------------------------------------
			// Must exist
			//-----------------------------------------------------
			$realPath = realpath($file)	;
			if ( ! $realPath ) {
				throw new \Exception("File not found $file");
			}
			//-----------------------------------------------------
			// Must be in one of this directories
			//-----------------------------------------------------

			if ( $this->directories ) {
				$throwException = true;
				foreach ($this->directories as $dir) {
					if ( strpos($realPath, $dir) == 0 ) {
						$throwException = false;
						break;
					}
				}
				if ( $throwException ) {
					throw new \Exception("File '$realPath' is not in allowed directories");
				}
			}
			//-----------------------------------------------------
			// Must be valid
			//-----------------------------------------------------

			$this->testJSONValidity(@file_get_contents($file));

			$array['conf'] = $helper->getJSon($value);

		} elseif ( $endsWith($key, 'url')) {

			// $components = parse_url($value);
			// if ( array_key_exists('scheme', $components) && ! in_array($components['scheme'], array('http','https'))) {
			// 	throw new \Exception("Invalid URL $value. Scheme is u");
			// } 

			// if ( ! array_key_exists('scheme', $components) && ! array_key_exists('path', $components)){
			// 	throw new \Exception("Invalid URL $value");
			// }
			// else if ( ! filter_var($value, FILTER_VALIDATE_URL) ) {
				
			// }

		
		}
	}



	public function testJSONValidity($str) {
		json_decode($str);
		$err = json_last_error();
		if ( $err ) {
			throw new \Exception("JSON error $err. Content: $str");
		}
	}
	/**
	* @Ajax
	*/
	public function classExists() {
		\OC_Util::checkAdminUser();
		$params = $this->request->get;
		return new JSONResponse( class_exist($params['class']) ? array('ok') : array('ko'));
	}

	/**
	* @Ajax
	*/
	public function conf() {
		\OC_Util::checkAdminUser();
		$params = $this->request->post;
		$helper = new ConfigHelper();
		return new JSONResponse( $helper->getJSon($params['file']));	
	}
}