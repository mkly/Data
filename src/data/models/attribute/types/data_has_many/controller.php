<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataHasManyAttributeTypeController extends AttributeTypeController {

	public function type_form() {
		$this->set('dataTypeSelector', Loader::helper('form/data_type_selector'));
	}

	/**
	 * @param array $data
	 */
	public function saveKey($data) {
	}

}
