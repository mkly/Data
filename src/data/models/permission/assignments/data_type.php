<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataTypePermissionAssignment extends PermissionAssignment {

	public function getPermissionAccessObject() {
		return PermissionAccess::getByID(
			Loader::db()->GetOne('
				SELECT paID
				FROM   DataTypePermissionAssignments
				WHERE  dtID = ?
				AND    pkID = ?
			', array(
				$this->getPermissionObject()->dtID,
				$this->pk->getPermissionKeyID()
			))
		);
	}

	public function clearPermissionAssignment() {
		Loader::db()->Execute('
			UPDATE DataTypePermissionAssignments
			SET    paID = 0
			WHERE  dtID = ?
			AND    pkID = ?
		', array(
			$this->pk->getPermissionKeyID(),
			$this->getPermissionObject()->dtID
		));
	}

	public function assignPermissionAccess(PermissionAccess $pa) {
		Loader::db()->Replace(
			'DataTypePermissionAssignments',
			array(
				'dtID' => $this->getPermissionObject()->dtID,
				'paID' => $pa->getPermissionAccessID(),
				'pkID' => $this->pk->getPermissionKeyID()
			),
			array('dtID', 'pkID'),
			true
		);
		$pa->markAsInUse();
	}

	public function getPermissionKeyToolsURL($task = false) {
		return parent::getPermissionKeyToolsURL($task) . '&dtID=' . $this->getPermissionObject()->dtID;
	}
}
