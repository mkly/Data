<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataTypePermissionKey extends PermissionKey {

	public static function getList() {
		return parent::getList('data_type');
	}

}
