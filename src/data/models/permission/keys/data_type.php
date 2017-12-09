<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataTypePermissionKey extends PermissionKey {

	public static function getList($pkCategoryHandle, $filters = array()) {
		return parent::getList('data_type');
	}

}
