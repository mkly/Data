<?php defined('C5_EXECUTE') or die('Access Denied.') ?>

<?php
$pkID = $_REQUEST['pkID'] ? $_REQUEST['pkID'] : null;
$pk = DataTypePermissionKey::getByID($pkID);
Loader::element('permission/detail', array('permissionKey' => $pk));
?>
<script>
	var ccm_permissionDialogURL = '<?= Loader::helper('concrete/urls')->getToolsURL('permissions/dialogs/data_type', 'data') ?>';
</script>
