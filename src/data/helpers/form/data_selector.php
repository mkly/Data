<?php
defined('C5_EXECUTE') or die('Access Denied.');

class FormDataSelectorHelper {

	/**
	 * @param string $fieldName
	 * @param Data $data
	 * @param DataType $dataType
	 * @return string
	 */
	public function select($fieldName, $data, $dataType) {
		$DataList = new DataList($dataType);
		$dataSelects = array();
		foreach ($DataList->get() as $data) {
			$dataSelects[$data->dID] = $data->dID . ':' . $data->name->getValue();
		}
		return Loader::helper('form')->select($fieldName, $dataSelects, $dID);
	}

	/**
	 * @param string $fieldName
	 * @param array $datas Data
	 * @param DataType $dataType
	 * @return string
	 */
	public function multiSelect($fieldName, $datas, $dataType) {
		$form = Loader::helper('form');
		$DataList = new DataList($dataType);
		$html = $form->hidden($fieldName . '[]', 0);
		$html .= '<ul class="inputs-list">';
		$dIDs = array();
		foreach ($datas as $data) {
			$dIDs[] = $data->dID;
		}
		foreach ($DataList->get() as $data) {
			$html .= '<li>' . $form->checkbox($fieldName . '[]', $data->dID, in_array($data->dID, $dIDs) ? $data->dID : 0) . ' ' . $data->name->getValue() . '</li>';
		}
		$html .= '</ul>';
		return $html;
	}
}
