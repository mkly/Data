<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataSingleChildAssociationAttributeTypeController extends AttributeTypeController {

	protected $searchIndexFieldDefinition = 'I NOT NULL';
	protected $settings;

	/**
	 * @return Data
	 */
	public function getValue() {
		$assoc = new DataSingleChildAssociationAttributeTypeValue;
		$assoc->Load('avID=?', array($this->getAttributeValueID()));
		return $assoc->getData();
	}

	/**
	 * @return string
	 */
	public function getDisplayValue() {
		$displays = array();
		foreach ($this->getValue()->getAttributeValueObjects() as $avo) {
			if (method_exists($avo->getAttributeTypeObject()->getController(), 'getDisplayValue')) {
				$displays[] = $avo->getValue('display');
				continue;
			}
			$displays[] = $avo->getValue();
		}
		return implode("\n", $displays);
	}

	/**
	 * @return string
	 */
	public function getDisplaySanitizedValue() {
		$displays = array();
		foreach ($this->getValue()->getAttributeValueObjects() as $avo) {
			$controller = $avo->getAttributeTypeObject()->getController();
			if (method_exists($controller, 'getDisplaySanitizedValue')) {
				$displays[] = nl2br($avo->getValue('display_sanitized'));
				continue;
			}
			if (method_exists($controller, 'getDisplayValue')) {
				$displays[] = nl2br($avo->getValue('display'));
				continue;
			}
			$displays[] = nl2br(h($avo->getValue()));
		}
		return implode("<br/>", $displays);
	}

	/**
	 * @param int $value
	 */
	public function saveValue($data) {
		$assoc = new DataSingleChildAssociationAttributeTypeValue;
		// todo adodb active record being wierd?
		if (!$assoc->Load('avID=?', array($this->getAttributeValueID()))) {
			$assoc->avID = $this->getAttributeValueID();
		}
		$assoc->dID = $data->dID;
		$assoc->Save();
	}

	/**
	 * @param array $data
	 */
	public function saveForm($args) {
		$data = new Data;
		if (!$data->Load('dID=?', array($args['dID']))) {
			$data->dtID = $args['dtID'];
			$data->Insert();
		}
		$dataType = new DataType;
		$dataType->Load('dtID=?', array($args['dtID']));
		foreach ($dataType->attributes as $dak) {
			$dak->saveAttributeForm($data);
			unset($_POST['akID'][$dak->getAttributeKeyID()]);
		}
		$this->saveValue($data);
	}

	public function deleteValue() {
		$assoc = new DataSingleChildAssociationAttributeTypeValue;
		$assoc->Delete();
	}

	/**
	 * @return DataSingleChildAssociationAttributeTypeSettings
	 */
	public function getSettings() {
		if (isset($this->settings)) return $this->settings;

		$this->settings = new DataSingleChildAssociationAttributeTypeSettings;
		if ($this->getAttributeKey()) {
			$this->settings->Load('akID=?', $this->getAttributeKey()->getAttributeKeyID());
		}
		return $this->settings;
	}

	public function type_form() {
		$this->set('dataTypeSelector', Loader::helper('form/data_type_selector', 'data'));
		$this->set('dtID', $this->getSettings()->dtID ? $this->getSettings()->dtID : 0);
	}

	public function form() {
		$this->set('form', Loader::helper('form'));
		$ah = Loader::helper('form/attribute');
		if ($data = $this->getValue()) {
			$ah->setAttributeObject($data);
		}
		$this->set('ah', $ah);
		$dataType = new DataType;
		$dataType->Load('dtID=?', array($this->getSettings()->dtID));
		$this->set('attributes', DataAttributeKey::getListByDataTypeID($dataType->dtID));
		$this->set('dataType', $dataType);
		$this->set('data', $data);
	}

	/**
	 * @param array $data
	 */
	public function saveKey($data) {
		$this->settings = $this->getSettings();
		$this->settings->dtID = $data['assoc_dtID'];
		$this->settings->akID = $this->getAttributeKey()->getAttributeKeyID();
		$this->settings->Save();
	}

	/**
	 * @todo
	 * @param AttributeKey $ak
	 */
	public function duplicateKey($ak) {
		$association = $this->getSettings();
		$association->akID = $ak->getAttributeKeyID();
		$association->Insert();
	}

	public function deleteKey() {
		$DataSingleChildAssociationAttributeTypeValue = new DataSingleChildAssociationAttributeTypeValue;
		foreach (
			$DataSingleChildAssociationAttributeTypeValue->Find('avID=?', array(
				$this->getAttributeKey()->getAttributeKeyID()
			)
		) as $value) {
			$value->Delete();
		}
	}

}
