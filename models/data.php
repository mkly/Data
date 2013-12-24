<?php
defined('C5_EXECUTE') or die('Access Denied.');

class Data extends Model {

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

		if ($createIfNotFound && (!$av || $db->GetOne('
			SELECT count(avID)
			FROM   DataAttributeValues
			WHERE  avID = ?
		', array($av->getAttributeValueID())))) {
			$av = $ak->addAttributeValue();
		}

		return $av;
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

	public function getDataType() {
		$dataType = new DataType;
		$dataType->Load('dtID=?', array($this->dtID));
		return $dataType;
	}
}
