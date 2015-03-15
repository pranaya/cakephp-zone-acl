<?php

App::uses('HtmlHelper', 'View/Helper');
App::uses('ZoneAclUtility', 'ZoneAcl.Lib');
App::uses('AuthComponent', 'Controller/Component');

class ZoneAclHtmlHelper extends HtmlHelper {

	public $aclComponent;

	public function __construct(\View $View, $settings = array()) {
		parent::__construct($View, $settings);

		$this->aclComponent = new AclComponent(new ComponentCollection());
	}

	public function link($title, $url = null, $options = array(), $confirmMessage = false) {

		if ($this->isAllowed($url)) {
			return parent::link($title, $url, $options, $confirmMessage);
		}

		return '';
	}
	
	public function isAllowed($url) {
		$aco = ZoneAclUtility::convertUrlToAco($url);
		$allow = $this->aclComponent->check(AuthComponent::user(), $aco);
		return (bool) $allow;
	}

}
