<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataAttributeValue extends AttributeValue {

	/**
	 * @var $data Data
	 */
	protected $data;

	/**
	 * @return Data
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * @param $avID int
	 * @return DataAttributeValue
	 */
	public static function getByID($avID) {
		$av = new DataAttributeValue;
		$av->load($avID);
		if ($av->getAttributeValueID()) {
			return $av;
		}
	}

	/**
	 * @TODO
	 */
	public function delete() {
		$db = Loader::db();
		
		$db->Execute('
			DELETE
			FROM  DataAttributeValues
			WHERE dID = ?
			AND   akID = ?
			AND   avID = ?
		', array(
			$this->getData()->dID,
			$this->akID,
			$this->getAttributeValueID()
		));

		if ($db->GetOne('
			SELECT count(avID)
			FROM   AttributeValues
			WHERE  avID = ?
		', array(
			$this->getAttributeValueID()
		))) {
			parent::delete();
		}
	}

	/**
	 * @param $avID int
	 */
	public function load($avID) {
		parent::load($avID);
		$this->setPropertiesFromArray(Loader::db()->GetRow('
			SELECT dID
			FROM   DataAttributeValues
			WHERE  avID = ?
		', array($avID)));
		$data = new Data;
		$data->Load('dID=?', array($this->dID));
		$this->data = $data;
	}

}
