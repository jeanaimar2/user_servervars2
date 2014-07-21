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
 namespace OCA\User_Servervars2\Backend\impl;
 use OCA\User_Servervars2\Backend\ScopeValidator;
/**
 * Suffix validator 
 * validated values are "$prefix@$scope"
 *
 * @package default
 * @author 
 **/
 class SuffixScopeValidator extends ScopeValidator {

 	var $scope;

 	function __construct($scope) {
 		$this->scope = $scope;
 	}


 	public function valid($attributeValues ) {
 		foreach ($attributeValues as  $value) {
 			if ( ! endsWith($value) ) {
 				return false;
 			}
 		}
 		return true;
 	}


 	function endsWith($uid) {
 		$suffix = '@'.$this->scope;
 		return substr($uid, -strlen($suffix)) === $suffix;
 	};
 }