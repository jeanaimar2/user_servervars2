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
namespace OCA\User_Servervars2\Lib;

class ConfigHelper {
	
	function newInstanceOf($className, $args=null, $default=null) {
		if ( ! is_null($args) && ! is_array($args) ) {
			throw new \Exception("args ".print_r($args,1)." is not a array as expected");
		}
		try { 

			$r = new \ReflectionClass($className);
			$object = $r->newInstanceArgs( (array) $args );
 			// $object = $r->newInstanceArgs( );
			return $object;
		} catch(\ReflectionException $e) {
			$msg = "Class not found exception $className";
			\OCP\Util::writeLog('User_Servervars2',$msg, \OCP\Util::ERROR);
			if ( $default ) {
				return $this->newInstanceOf($default, $args);
			} 
			return $null;
		}
	}

	/**
	* @param String json file location
	* @return CustomConfig
	*/
	function newCustomConfig($arg) {
		
		$config = new CustomConfig();
		$json = $this->getJSon($arg);
		$err = $config->loadFromJSON($json);
		if( $err ) {
			throw new \Exception("configuration $json is invalid");
		}
		return $config;
	}

	function endsWith($v, $needle) {
		return ( 
			strlen($v) > strlen($needle) && strcmp(substr($v, strlen($v) - strlen($needle)), $needle) === 0
		);
	}


	function getPath($arg) {
		return join(DIRECTORY_SEPARATOR,array( __DIR__, '.','..',$arg));
	}

	function getJSon($arg) {
		$json = null;
		$path = $this->getPath($arg);
		$realPath = realpath($path);
		if ( ! $realPath ) {
			\OCP\Util::writeLog('User_Servervars2', "file ".$path." doesn't exist", \OCP\Util::ERROR);;
		} else {
			$json = file_get_contents( $path );
		}
		return $json;
	}
}