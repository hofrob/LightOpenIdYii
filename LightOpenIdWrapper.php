<?php

require_once 'LightOpenID'.DIRECTORY_SEPARATOR.'LightOpenID.php';

class LightOpenIDWrapper extends CApplicationComponent {

	private $__lightopenid;

	public function init() {
		parent::init();
		$this->__lightopenid = new LightOpenID(Yii::app()->createAbsoluteUrl('//user'));
	}

	public function __call($name, $arguments = array()) {
		if(method_exists($this->__lightopenid, $name))
			return call_user_func_array(array($this->__lightopenid, $name), $arguments);
	}

	public function __get($name) {
		if(property_exists($this->__lightopenid, $name))
			return $this->__lightopenid->$name;
		else
			return $this->__lightopenid->__get($name);
	}

	public function __set($name, $value) {
		if(property_exists($this->__lightopenid, $name))
			return $this->__lightopenid->$name = $value;
		else
			return $this->__lightopenid->__set($name, $value);
	}
}