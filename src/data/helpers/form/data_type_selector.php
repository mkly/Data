<?php
defined('C5_EXECUTE') or die('Access Denied.');

class FormDataTypeSelectorHelper {

	/**
	 * @param string $fieldName
	 * @param int $dtID
	 */
	public function select($fieldName, $dtID = 0) {
		$DataType = new DataType;
		$dataTypes = array();
		foreach ($DataType->Find('1=1') as $dataType) {
			if ($dataType->permissions->canViewDataType()) {
				$dataTypes[$dataType->dtID] = $dataType->dtName;
			}
		}
		return Loader::helper('form')->select($fieldName, $dataTypes, $dtID);
	}

}
