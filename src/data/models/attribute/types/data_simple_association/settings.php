<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataSimpleAssociationAttributeTypeSettings extends Model {

	public $_table = 'atDataSimpleAssociationAttributeTypeSettings';

	protected $dataType;

	public function __get($name) {
		$method = 'get' . ucfirst($name);
		if (method_exists($this, $method)) {
			return $this->$method();
		}
		return parent::__get($name);
	}

	protected function getDataType() {
		if (isset($this->dataType)) return $this->dataType;

		$this->dataType = new DataType;
		$this->dataType->Load('dtID=?', array($this->dtID));
		return $this->dataType;
	}
}
