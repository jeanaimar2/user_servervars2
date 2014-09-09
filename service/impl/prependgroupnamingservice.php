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
namespace OCA\User_Servervars2\Service\Impl;
use OCA\User_Servervars2\Service\GroupNamingService;

class PrependGroupNamingService implements GroupNamingService {

	var $mapping;
	var $prefixes = array();

	public function __construct($array) {
		if ( is_null($array)) {
			$mapping = 'auto';
			$separator = '@';
		} else {
			$mapping 	= $array['mapping'];
			$separator 	= $array['separator'];
		}
		$this->mapping = $mapping;
		$this->separator = $separator;
	}
	
	/**
	* @param String kind of attribute
	* @param String value
	* @return boolean validity of group name according to $kind
	*/
	function isValid($groupName){ 
		if ( empty($this->prefixes)) {
			$this->buildPrefixes();
		}
		foreach ($this->prefixes as $prefix) {

			if ( strpos($groupName, $prefix) === 0 ) {
				return true;
			}

		}
		return false;
	}



	function buildPrefixes() {
		if ( is_array($this->mapping)) {
			foreach ($this->mapping as $key => $value) {
				$this->prefixes[] = $this->prepend($key);
			}
		} else {
			$this->prefixes[] = $this->prepend(null);
		}
	}

	/**
	* @param String kind of attribute
	* @param String value
	* @return built name according to rules
	**/
	function getName($kind, $value) {
		return $this->prepend($kind).strtolower($value);
	}

	function prepend($kind) {
		$v = null;
		if ( isset($this->mapping[$kind])) {
			$v = $this->mapping[$kind];
		} else if(!is_array($this->mapping) ){
			$v =  $this->mapping;
		} else {
			throw new \Exception("Unmanaged kind: $kind");
		}
		return strtolower($v).$this->separator;
	}


	function isManaged($kind) {
		if ( ! is_array($this->mapping)) return true;
		return array_key_exists($kind, $this->mapping);
	}
}