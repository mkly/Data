<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataSimpleAssociationAttributeTypeController extends AttributeTypeController {

	protected $searchIndexFieldDefinition = 'I NOT NULL';
	protected $settings;

	/**
	 * return array|Data
	 */
	public function getValue() {
		if ($this->getSettings()->multipleAssociations) {
			return $this->getMultipleValue();
		}
		return $this->getSingleValue();

	}

	protected function getMultipleValue() {
		$Assoc = new DataSimpleAssociationAttributeTypeValue;
		$datas = array();
		foreach ($Assoc->Find('avID=?', array($this->getAttributeValueID())) as $value) {
			$datas[] = $value->getData();
		}
		return $datas;
	}

	/**
	 * @return Data
	 */
	protected function getSingleValue() {
		$assoc = new DataSimpleAssociationAttributeTypeValue;
		$assoc->Load('avID=?', array($this->getAttributeValueID()));
		return $assoc->getData();
	}

	/**
	 * @return string
	 */
	public function getDisplayValue() {
		if ($this->getSettings()->multipleAssociations) {
			return $this->getMultipleDisplayValue();
		}
		return $this->getSingleDisplayValue();
	}

	/**
	 * @return string
	 */
	protected function getDisplayMultipleValue() {
		$displays = array();
		foreach ($this->getValue() as $data) {
			foreach ($data->getAttributeValueObjects() as $avo) {
				if (method_exists($avo->getAttributeTypeObject()->getController(), 'getDisplayValue')) {
					$displays[] = $avo->getValue('display');
				}
				$displays[] = $avo->getValue();
			}
		}
		return implode("\n", $displays);
	}

	/**
	 * @return string
	 */
	protected function getDisplaySingleValue() {
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
		if ($this->getSettings()->multipleAssociations) {
			return $this->getDisplayMultipleSanitizedValue();
		}
		return $this->getDisplaySingleSanitizedValue();
	}

	/**
	 * @return string
	 */
	protected function getDisplayMultipleSanitizedValue() {
		$displays = array();
		foreach ($this->getValue() as $data) {
			foreach ($data->getAttributeValueObjects() as $avo) {
				$controller = $avo->getAttributeTypeObject()->getController();
				if (method_exists($controller, 'getDisplaySanitizedValue')) {
					$displays[] = nl2br($avo->getValue('display_sanitized'));
					continue;
				}
				if (method_exists($controller, 'getDisplayValue')) {
					$displays[] = nl2br($avo->getValue('display'));
					continue;
				}
			}
		}
		return implode('<br/>', $displays);
	}

	/**
	 * @return string
	 */
	protected function getDisplaySingleSanitizedValue() {
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
		return implode('<br/>', $displays);
	}

	/**
	 * @param int $value
	 */
	public function saveValue($value) {
		if ($this->getSettings()->multipleAssociations) {
			$this->saveMultipleValues((array) $value);
			return;
		}
		$this->saveSingleValue($value);
	}

	/**
	 * @todo get this in the model
	 * @param int $value
	 */
	protected function saveSingleValue($value) {
		$assoc = new DataSimpleAssociationAttributeTypeValue;
		if (!$assoc->Load('avID=?', array($this->getAttributeValueID()))) {
			$assoc->avID = $this->getAttributeValueID();
		}
		$assoc->dID = $value;
		$assoc->Save();
	}

	/**
	 * @todo get this in the model
	 * @param array $values
	 */
	protected function saveMultipleValues($values) {
		$Assoc = new DataSimpleAssociationAttributeTypeValue;
		$assocs = $Assoc->Find('avID=?', array($this->getAttributeValueID()));

		foreach ($values as $value) {
			$assoc = new DataSimpleAssociationAttributeTypeValue;
			/**
			 * Find any skip any that are already saved
			 * If not found Insert a new one
			 */
			if ($assoc->Load('avID=? AND dID=?', array($this->getAttributeValueID(), $value))) {
				foreach ($assocs as $j => $a) {
					if ($a->aID === $assoc->aID) {
						unset($assocs[$j]);
						break;
					}
				}
			} else {
				$assoc->avID = $this->getAttributeValueID();
				$assoc->dID = $value;
				$assoc->Insert();
			}
		}

		/**
		 * Delete any records not found in this update
		 * As that means they were not set(unchecked)
		 */
		foreach ($assocs as $a) {
			$a->Delete();
		}
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
		$this->set('form', Loader::helper('form'));
		$this->set('dataTypeSelector', Loader::helper('form/data_type_selector', 'data'));
		$this->set('settings', $this->getSettings());
	}

	/**
	 * @param array $data
	 */
	public function saveKey($data) {
		$this->settings = $this->getSettings();
		$this->settings->dtID = $data['assoc_dtID'];
		$this->settings->multipleAssociations = isset($data['multipleAssociations']) ? 1 : 0;
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
		$this->set('settings', $this->settings);

		if ($this->getSettings()->multipleAssociations) {
			$Assoc = new DataSimpleAssociationAttributeTypeValue;
			$datas = array();
			foreach ($Assoc->Find('avID=?', array($this->getAttributeValueID())) as $assoc) {
				$datas[] = $assoc->getData();
			}
			$this->set('datas', $datas);
		} else {
			$assoc = new DataSimpleAssociationAttributeTypeValue;
			$assoc->Load('avID=?', array($this->getAttributeValueID()));
			$this->set('data', $assoc->getData());
		}
	}

	/**
	 * @param SimpleXMLElement $xml
	 */
	public function exportKey($xml) {
		$type = $xml->addChild('type');
		$type->addAttribute('dtHandle', $this->getSettings()->dataType->dtHandle);
		$type->addAttribute('multipleAssociations', $this->getSettings()->multipleAssociations);
	}
}
