<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataType extends Model {

	public function validate() {
		$e = Loader::helper('validation/error');

		if (strlen($this->dtName) > 255) {
			$e->add(t('Maximum Name length is 255 characters'));
		}
		if (strlen($this->dtHandle) > 255) {
			$e->add(t('Maximum Handle length is 255 characters'));
		}
		if (preg_match('/[^a-z0-9_]/', $this->dtHandle)) {
			$e->add(t('Handle can only contain a-z 0-9 and _'));
		}
		if (strlen($this->dtName) === 0) {
			$e->add(t('Name is required'));
		}
		if (strlen($this->dtHandle) === 0) {
			$e->add(t('Handle is required'));
		}

		$db = Loader::db();
		if ($dtID = $db->GetOne('
			SELECT dtID
			FROM DataTypes
			WHERE dtName = ?
		', array($this->dtName))) {
			$dataType = new DataType;
			$dataType->Load('dtID=?', $dtID);
			$e->add(t('Name already in use by %s', $dataType->name));
		}
		if ($db->GetOne('
			SELECT dtID
			FROM DataTypes
			WHERE dtName = ?
		', array($this->dtHandle))) {
			$e->add(t('Handle already in use by %s', $dataType->name));
		}

		return $e;
	}

}
