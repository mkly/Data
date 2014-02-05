<?php
defined('C5_EXECUTE') or die('Access Denied.');

$task = isset($_REQUEST) ? $_REQUEST['task'] : null;
if (!$task) {
	return;
}

if (!Loader::helper('validation/token')->validate($task)) {
	return;
}

$pkID = isset($_REQUEST['pkID']) ? $_REQUEST['pkID'] : null;
$paID = isset($_REQUEST['paID']) ? $_REQUEST['paID'] : null;
$peID = isset($_REQUEST['peID']) ? $_REQUEST['peID'] : null;
$pdID = isset($_REQUEST['pdID']) ? $_REQUEST['pdID'] : null;
$dtID = isset($_REQUEST['dtID']) ? $_REQUEST['dtID'] : null;
$accessType = isset($_REQUEST['accessType']) ? $_REQUEST['accessType'] : null;

switch ($task) {

	case 'display_list':
		$dataType = new DataType;
		$dataType->Load('dtID=?', array($dtID));
		Loader::element('permission/lists/data_type', array('dataType' => $dataType), Package::getByHandle('data'));
	break;

	case 'add_access_entity':
		$pk = DataTypePermissionKey::getByID($pkID);
		$dataType = new DataType;
		$dataType->Load('dtID=?', array($dtID));
		$pk->setPermissionObject($dataType);
		$pa = PermissionAccess::getByID($paID, $pk);
		$pe = PermissionAccessEntity::getByID($peID);
		$pd = PermissionDuration::getByID($pdID);
		$pa->addListItem($pe, $pd, $accessType);
	break;

	case 'remove_access_entity':
		$pk = DataTypePermissionKey::getByID($pkID);
		$dataType = new DataType;
		$dataType->Load('dtID=?', array($dtID));
		$pk->setPermissionObject($dataType);
		$pa = PermissionAccess::getByID($paID, $pk);
		$pe = PermissionAccessEntity::getByID($peID);
		$pa->removeListItem($pe);
	break;

	case 'save_permission':
	break;

	case 'display_access_cell':
		$pk = DataTypePermissionKey::getByID($pkID);
		$pa = PermissionAccess::getByID($paID, $pk);
		Loader::element('permission/labels', array('pk' => $pk, 'pa' => $pa));
	break;

	case 'save_permission_assignments':
		/**
		 * In this case pkID is actually an array
		 * Move it to a clearer name and null it
		 */
		$pkIDs = $pkID;	
		unset($pkID);

		$dataType = new DataType;
		$dataType->Load('dtID=?', array($dtID));
		foreach (DataTypePermissionKey::getList() as $pk) {
			$pk->setPermissionObject($dataType);
			$pt = $pk->getPermissionAssignmentObject();
			$pt->clearPermissionAssignment();

			$pkID = $pk->getPermissionKeyID();
			if (!isset($pkIDs[$pkID])) {
				continue;
			}
			if (!$paID = $pkIDs[$pkID]) {
				continue;
			}

			if (!$pa = PermissionAccess::getByID($pkIDs[$pkID], $pk)) {
				continue;
			}
			$pt->assignPermissionAccess($pa);
		}
	break;

}
