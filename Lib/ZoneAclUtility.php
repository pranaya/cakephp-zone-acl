<?php

App::uses('Inflector', 'Utility');
App::uses('Router', 'Routing');
App::uses('ZoneAcl', 'ZoneAcl.Controller/Component/Acl');

class ZoneAclUtility {

	public static function convertUrlToAco($url, $actionPath = null) {
		$url = Router::url($url);
		$url = Router::parse($url);

		$aco = array();

		$actionPath = empty($actionPath) ? ZoneAcl::$actionPath : $actionPath;
		$actionPath = rtrim($actionPath, '/');

		$aco[] = $actionPath;

		if (isset($url['plugin'])) {
			$aco[] = Inflector::camelize($url['plugin']);
		}

		if (isset($url['controller'])) {
			$aco[] = Inflector::camelize($url['controller']);
		}

		if (isset($url['action'])) {
			$aco[] = $url['action'];
		}

		$aco = implode('/', $aco);
		return $aco;
	}

}
