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

	/**
	 * @param string $fieldName
	 * @param array $dtID
	 * @return string
	 */
	public function multiSelect($fieldName, array $dIDs, $dataType) {
		$form = Loader::helper('form');
		$DataList = new DataList($dataType);
		$html = '<ul class="inputs-list">';
		foreach ($DataList->get() as $data) {
			$html .= '<li>' . $form->checkbox($fieldName . '[]', $data->dID, in_array($data->dID, $dIDs) ? $data->dID : 0) . ' ' . $data->name->getValue() . '</li>';
		}
		$html .= '</ul>';
		return $html;
	}
}
