<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataSimpleAssociationAttributeTypeController extends AttributeTypeController {

	protected $searchIndexFieldDefinition = 'I NOT NULL';
	protected $settings;

	/**
	 * return Data
	 */
	public function getValue() {
		$belongsTo = new DataSimpleAssociationAttributeTypeValue;
		$belongsTo->Load('avID=?', array($this->getAttributeValueID()));
		return $belongsTo->getData();
	}

	/**
	 * @return string
	 */
	public function getDisplayValue() {
		$displays = array();
		foreach ($this->getData()->getAttributeValueObjects() as $avo) {
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
	public function saveValue($value) {
		$belongsTo = new DataSimpleAssociationAttributeTypeValue;
		if (!$belongsTo->Load('avID=?', array($this->getAttributeValueID()))) {
			$belongsTo->avID = $this->getAttributeValueID();
		}
		$belongsTo->dID = $value;
		$belongsTo->Save();
	}

	/**
	 * @param array $args
	 */
	public function saveForm($args) {
		$this->saveValue($args['value']);
	}

	public function deleteValue() {
		$belongsTo = new DataSimpleAssociationAttributeTypeValue;
		$belongsTo->Delete();
	}

	/**
	 * @return DataSimpleAssociationAttributeTypeSettings
	 */
	public function getSettings() {
		if (isset($this->settings)) return $this->settings;

		$this->settings = new DataSimpleAssociationAttributeTypeSettings;
		if ($this->getAttributeKey()) {
			$this->settings->Load('akID=?', $this->getAttributeKey()->getAttributeKeyID());
		}
		return $this->settings;
	}

	public function type_form() {
		$this->set('dataTypeSelector', Loader::helper('form/data_type_selector', 'data'));
		$this->set('dtID', $this->getSettings()->dtID ? $this->getSettings()->dtID : 0);
	}

	/**
	 * @param array $data
	 */
	public function saveKey($data) {
		$this->settings = $this->getSettings();
		$this->settings->dtID = $data['belongsTo_dtID'];
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

	/**
	 * @param AttributeKey $ak
	 */
	public function deleteKey() {
		$DataSimpleAssociationAttributeTypeValue = new DataSimpleAssociationAttributeTypeValue;
		foreach (
			$DataSimpleAssociationAttributeTypeValue->Find('avID=?', array(
				$this->getAttributeKey()->getAttributeKeyID()
			)
		) as $avo) {
			$avo->Delete();
		}
	}

	public function form() {
		$this->set('form', Loader::helper('form'));
		$dataType = new DataType;
		$dataType->Load('dtID=?', array($this->getSettings()->dtID));
		$this->set('dataType', $dataType);
		$this->set('dataSelector', Loader::helper('form/data_selector', 'data'));
	}
}
