<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataHasOneAttributeTypeController extends AttributeTypeController {

	protected $searchIndexFieldDefinition = 'I NOT NULL';
	protected $settings;

	/**
	 * @return Data
	 */
	public function getValue() {
		$hasOne = new DataHasOneAttributeTypeValue;
		$hasOne->Load('avID=?', array($this->getAttributeValueID()));
		return $hasOne->getData();
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
		$hasOne = new DataHasOneAttributeTypeValue;
		// todo adodb active record being wierd?
		if (!$hasOne->Load('avID=?', array($this->getAttributeValueID()))) {
			$hasOne->avID = $this->getAttributeValueID();
		}
		$hasOne->dID = $data->dID;
		$hasOne->Save();
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
		$hasOne = new DataHasOneAttributeTypeValue;
		$hasOne->Delete();
	}

	/**
	 * @return DataHasOneAttributeTypeSettings
	 */
	public function getSettings() {
		if (isset($this->settings)) return $this->settings;

		$this->settings = new DataHasOneAttributeTypeSettings;
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
		$this->settings->dtID = $data['hasOne_dtID'];
		$this->settings->akID = $this->getAttributeKey()->getAttributeKeyID();
		$this->settings->Save();
	}

	/**
	 * @param AttributeKey $ak
	 */
	public function duplicateKey($ak) {
		$association = $this->getAssociation();
		$association->aID = null;
		$association->Insert();
	}

	public function deleteKey() {
		$DataHasOneAttributeTypeValue = new DataHasOneAttributeTypeValue;
		foreach (
			$DataHasOneAttributeTypeValue->Find('avID=?', array(
				$this->getAttributeKey()->getAttributeKeyID()
			)
		) as $value) {
			$value->Delete();
		}
	}

}
