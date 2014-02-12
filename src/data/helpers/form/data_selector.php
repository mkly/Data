<?php
defined('C5_EXECUTE') or die('Access Denied.');

class FormDataSelectorHelper {

	/**
	 * @param string $fieldName
	 * @param int $dtID
	 * @return string
	 */
	public function select($fieldName, $dID = 0, $dataType) {
		$DataList = new DataList($dataType);
		$dataSelects = array();
		foreach ($DataList->get() as $data) {
			$dataSelects[$data->dID] = $data->dID . ':' . $data->name->getValue();
		}
		return Loader::helper('form')->select($fieldName, $dataSelects, $dID);
	}
}
