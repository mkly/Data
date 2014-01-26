<?php
defined('C5_EXECUTE') or die('Access Denied.');

class EmployeeScheduleBaseModel extends Model {

	public function __get($name) {
		$method = 'get' . ucfirst($name);
		if (method_exists($this, $method)) {
			return $this->$method();
		}
		return parent::__get($name);
	}

	public function reset($property) {
		unset($this->{$property});
	}

	public function validate() {
		return Loader::helper('validation/error');
	}

	public function asArray() {
		$arr = array();
		$table = $this->TableInfo();
		foreach ($this->TableInfo()->flds as $field) {
			$arr[$field->name] = $this->{$field->name};
		}
		return $arr;
	}

}

