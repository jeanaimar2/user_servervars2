<?php

namespace OCA\User_Servervars2\AppInfo;

$app = new ConfigApp();
$app->registerRoutes($this, array(
 	'routes' => array(
 			array('name' => 'settings#class_exists'	, 'url' =>	'/api/settings/class/{class}'	,   'verb' => 'GET'),
 			array('name' => 'settings#conf'			, 'url' =>	'/api/settings/conf/'		,   	'verb' => 'POST'),
 			array('name' => 'settings#set_config'	, 'url' =>	'/api/settings/'				,   'verb' => 'POST'),
 			array('name' => 'deferred#provisionning', 'url' =>	'/api/deferred/provisionning'	,  	'verb' => 'GET'),
 		)
 	)
 );