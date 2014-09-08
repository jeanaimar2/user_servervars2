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

class CustomConfig {

	var $data;


	public function __construct($data = null) {
		$this->data = $data;
	}


	public function loadFromJSON($str) {
		$this->data = json_decode($str, TRUE);
		return json_last_error();
	}

	public function toJSON() {
		return json_encode($this->data);
	}

	function setValue($key,$value) {
		if (!isset($this->data)) {
			$this->data = array();
		}
		$this->_setValue($this->data, $key, $value);
		return true;                        
	}

	function _setValue(&$array_ptr, $key, $value) {

		$keys = explode('.', $key);

		$last_key = array_pop($keys);

		while ($arr_key = array_shift($keys)) {
			if (!array_key_exists($arr_key, $array_ptr)) {
				$array_ptr[$arr_key] = array();
			}
			$array_ptr = &$array_ptr[$arr_key];
		}

		$array_ptr[$last_key] = $value;
	}


	function _getValue(&$array_ptr, $key) {

		$keys = explode('.', $key);

		$last_key = array_pop($keys);

		while ($arr_key = array_shift($keys)) {
			if (!array_key_exists($arr_key, $array_ptr)) {
				return null;
			} 
			$array_ptr = &$array_ptr[$arr_key];
		}

		return $array_ptr[$last_key];
	}


	function getValue($key, $default=null) {
		$v = null;
		if ( isset($this->data)) {

			$v =  $this->_getValue($this->data, $key);
			if ( is_null($v)) {
				$v =  $default;
			}
		}
		return $v;

	}

}
