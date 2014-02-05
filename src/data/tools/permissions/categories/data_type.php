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
$accessType = isset($_REQUEST['accessType']) ? $_REQUEST['accessType'] : null;
$wfIDs = (array) (isset($_REQUEST['wfID']) ? $_REQUEST['wfID'] : null);

switch ($task) {

	case 'display_list':
		Loader::element('permission/lists/data_type', array(), Package::getByHandle('data'));
	break;

	case 'add_access_entity':
		$pk = DataTypePermissionKey::getByID($pkID);
		$pa = PermissionAccess::getByID($paID, $pk);
		$pe = PermissionAccessEntity::getByID($peID);
		$pd = PermissionDuration::getByID($pdID);
		$pa->addListItem($pe, $pd, $accessType);
	break;

	case 'remove_access_entity':
		$pk = DataTypePermissionKey::getByID($pkID);
		$pa = PermissionAccess::getByID($paID, $pk);
		$pe = PermissionAccessEntity::getByID($peID);
		$pa->removeListItem($pe);
	break;

	case 'save_permission':
		$pk = DataTypePermissionKey::getByID($pkID);
		$pa = PermissionAccess::getByID($paID, $pk);
		$pa->save($_POST);
	break;

	case 'display_access_cell':
		$pk = DataTypePermissionKey::getByID($pkID);
		$pa = PermissionAccess::getByID($paID, $pk);
		Loader::element('permission/labels', array('pk' => $pk, 'pa' => $pa));
	break;

	case 'save_workflows':
		$pk = DataTypePermissionkey::getByID($pkID);
		foreach ($wfIDs as $wfID) {
			$wf = Workflow::getByID($wfID);
			if (is_object($wf)) {
				$pk->attacheWorkflow($wf);
			}
		}
	break;
}
