<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataBelongsToAttributeTypeController extends AttributeTypeController {

	public function type_form() {
		echo Loader::helper('form/data_type_selector', 'data')->selectDataType('dtID');
	}

	/**
	 * @param array $data
	 */
	public function saveKey($data) {
	}

}
