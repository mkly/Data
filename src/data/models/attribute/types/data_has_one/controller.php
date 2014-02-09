<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataHasOneAttributeTypeController extends AttributeTypeController {

	protected $searchIndexFieldDefinition = 'I NOT NULL';
	protected $settings;

	/**
	 * @return DataHasOneAttributeTypeValue
	 */
	public function getValue() {
		$hasOne = new DataHasOneAttributeTypeValue;
		$hasOne->Load('avID=?', array($this->getAttributeValueID()));
		return $hasOne;
	}

	public function deleteValue() {
		$hasOne = new DataHasOneAttributeTypeValue;
		$hasOne->Delete();
	}

	/**
	 * @return DataHasOneAssociation
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
