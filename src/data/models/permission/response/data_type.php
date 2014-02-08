<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataTypePermissionResponse extends PermissionResponse {

	public function canViewEditInterface() {
		if ($this->canEditDataType()) {
			return true;
		}
		if ($this->canEditDataTypePermissions()) {
			return true;
		}
		return false;
	}

}
