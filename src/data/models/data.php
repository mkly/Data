<?php
defined('C5_EXECUTE') or die('Access Denied.');

class Data extends Model {

	public $_table = 'Datas';
	private $_indexingDisabled = false;

	/**
	 * $data = new Data;
	 * $data->Load('dID=?', array(1));
	 * echo $data->height->getValue();
	 * echo $data->address->getAddress1();
	 * echo $data->richText->getDisplaySanitizedValue();
	 */
	public function __get($name) {
		$ak = DataAttributeKey::getByHandle('data_' . $this->getDataType()->dtHandle . '_' . strtolower(preg_replace("/([A-Z])/", "_$1", $name)));
		if (!$ak) {
			return;
		}

		$avID = Loader::db()->GetOne('
			SELECT avID
			FROM   DataAttributeValues
			WHERE  dID = ?
			AND    akID = ?
		', array(
			$this->dID,
			$ak->getAttributeKeyID()
		));

		if (!$avID) {
			return;
		}

		$av = DataAttributeValue::getByID($avID);
		$av->setAttributeKey($ak);
		return $av;
	}

	/**
	 * @param $ak DataAttributeKey
	 * @param $createIfNotFound boolean
	 * @return DataAttributeValue|null
	 */
	public function getAttributeValueObject($ak, $createIfNotFound = false) {
		$db = Loader::db();
		$av = false;

		if ($avID = $db->GetOne('
			SELECT avID
			FROM   DataAttributeValues
			WHERE  dID = ?
			AND    akID = ?
		', array(
			$this->dID,
			$ak->getAttributeKeyID()
		))) {
			$av = DataAttributeValue::getByID($avID);
			$av->setAttributeKey($ak);
		}

		if ($createIfNotFound && (!$av || !$db->GetOne('
			SELECT count(avID)
			FROM   DataAttributeValues
			WHERE  avID = ?
		', array($av->getAttributeValueID())))) {
			$av = $ak->addAttributeValue();
		}

		return $av;
	}

	/**
	 * @param $ak AttributeKey|string
	 * @param $value mixed
	 */
	public function setAttribute($ak, $value) {
		if (!is_object($ak)) {
			$ak = DataAttributeKey::getByHandle('data_' . $this->getDataType()->dtHandle . '_' . $ak);
		}
		$ak->setAttribute($this, $value);
		$this->reindex();
	}

	public function clearAttribute($ak) {
		if ($dav = $this->getAttributeValueObject($ak)) {
			$dav->delete();
		}
		$this->reindex();
	}

	public function Delete() {
		$db = Loader::db();

		$res = $db->Execute('
			SELECT avID
			FROM DataAttributeValues
			WHERE dID = ?
		', array($this->dID));

		while ($row = $res->FetchRow()) {
			if ($dav = DataAttributeValue::getByID($row['avID'])) {
				$dav->delete();
			}
		}

		return parent::Delete();
	}

	public function Insert() {
		parent::Insert();
		$this->reindex();
	}

	public function Update() {
		parent::Update();
		$this->reindex();
	}
	
	/**
	 * Duplicates a data object
	 * 
	 * @return Data or false
	 */
	public function Duplicate(){
		$db = Loader::db();
		
		$duplicate = clone $this;
		$duplicate->dID = null;
		$duplicate->Insert();
		
		$duplicate = new Data;
		$duplicate->Load('dID=?', array($db->Insert_ID()));
		
		if(!$duplicate){
			return false;
		}
		
		$v = array($this->dID);
		$q = "select * from DataAttributeValues where dID = ?";
		$r = $db->query($q, $v);
		while ($row = $r->fetchRow()) {
			$v2 = array($duplicate->dID, $row['akID'], $row['avID']);
			$db->query("insert into DataAttributeValues (dID, akID, avID) values (?, ?, ?)", $v2);
		}
		
		return $duplicate;
	}

	public function getDataType() {
		$dataType = new DataType;
		$dataType->Load('dtID=?', array($this->dtID));
		return $dataType;
	}

	public function getAttributeValueObjects() {
		$avos = array();
		foreach ($this->getDataType()->attributes as $attribute) {
			if ($avo = $this->getAttributeValueObject($attribute)) {
				$avos[] = $this->getAttributeValueObject($attribute);
			}
		}
		return $avos;
	}

	public function disableIndexing() {
		$this->_indexingDisabled = true;
	}

	public function enableIndexing() {
		$this->_indexingDisabled = false;
	}

	public function reindex() {
		if ($this->_indexingDisabled) {
			return;
		}

		$db = Loader::db();
		$db->Execute('
			DELETE
			FROM   DataSearchIndexAttributes
			WHERE  dID = ?
		', array($this->dID));
		return AttributeKey::reindex(
			'DataSearchIndexAttributes',
			array('dID' => $this->dID, 'dtID' => $this->getDataType()->dtID),
			DataAttributeKey::getAttributes($this->dID, 'getSearchIndexValue'),
			$db->Execute('
				SELECT *
				FROM DataSearchIndexAttributes
				WHERE 1=2
			')
		);
	}

	public function import($node) {
		if ($node->getName() !== 'data') {
			throw new DataException(t('Invalid Element'));
		}
		$parent = current($node->xpath('parent::*'));
		if (!$parent || empty($parent->attributes()->dtHandle)) {
			throw new DataException(t('DataType handle dtHandle not found'));
		}
		$dataType = new DataType;
		if (!$dataType->Load('dtHandle=?', array($parent->attributes()->dtHandle))) {
			throw new DataException(t('DataType not found'));
		}
		$data = new Data;
		$data->dtID = $dataType->dtID;
		$data->Insert();
		return $data;
	}
}
