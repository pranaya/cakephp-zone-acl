<?php

App::uses('AclInterface', 'Controller/Component/Acl');

class ZoneAcl extends Object implements AclInterface {

	protected $settings;
	protected $aclComponent;

	public function initialize(\Component $component) {

		$filenameIni = Configure::read('ZoneAcl.config_file');

		$iniContent = parse_ini_file($filenameIni, true);

		$modifiers = array(
			'*' => '.*',
		);

		$caseSensitive = isset($iniContent['settings']['case-sensitive']) ? (bool) $iniContent['settings']['case-sensitive'] : true;

		foreach ($iniContent as $zone => &$value) {

			if (!is_array($value)) {
				continue;
			}

			foreach ($value as $k => &$value1) {
				if ($k == 'url') {
					foreach ($value1 as &$url) {
						$url = str_replace(array_keys($modifiers), array_values($modifiers), $url);
						$url = '#^' . $url . '$#';

						if ($caseSensitive) {
							$url .= 'i';
						}
					}
				}
			}
		}

		$this->settings = $iniContent;
		$this->aclComponent = $component;
	}

	public function allow($aro, $aco, $action = "*") {
		
	}

	public function check($aro, $aco, $action = "*") {

		$allowed = false;

		$aco = str_replace('controllers/', '', $aco);

		$zones = $this->aclComponent->Aro->getAllowedZones($aro);

		foreach ($zones as $zone) {
			if (!isset($this->settings['zone:' . $zone]['url'])) {
				continue;
			}

			$extra = null;
			foreach ($this->settings['zone:' . $zone]['url'] as $key => $urlPattern) {
				if (preg_match($urlPattern, $aco, $extra)) {
					$allowed = true;
					continue;
				}
			}
		}

		return $allowed;
	}

	public function deny($aro, $aco, $action = "*") {
		
	}

	public function inherit($aro, $aco, $action = "*") {
		
	}

}
