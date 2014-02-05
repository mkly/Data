<?php
defined('C5_EXECUTE') or die('Access Denied.');
$pkc = PermissionKeyCategory::getByHandle('data_type');
$urlh = Loader::helper('url');
$curlh = Loader::helper('concrete/urls');
?>

<div class="ccm-ui">
	<form>
		<table class="ccm-permission-grid">
			<tbody>
			<?php foreach (DataTypePermissionKey::getList() as $pk) { ?>
				<tr>
					<td class="ccm-permission-grid-name"
							id="ccm-permission-grid-name-<?= $pk->getPermissionKeyID() ?>">
						<strong><a class="data-permission-key-edit-button" href="<?= $curlh->getToolsURL('permissions/dialogs/data_type', 'data') . h('?pkID=' . $pk->getPermissionKeyID() . '&paID=' . $pk->getPermissionAccessID()) ?>"
						           dialog-title="<?= tc('PermissionKeyName', $pk->getPermissionKeyName()) ?>"
						><?= tc('PermissionKeyName', $pk->getPermissionKeyName()) ?></a></strong>
					</td>
					<td id="ccm-permission-grid-cell-<?= $pk->getPermissionKeyID() ?>"
							class="ccm-permission-grid-cell"><?= Loader::element('permission/labels', array('pk' => $pk)) ?>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</form>
</div><!-- .ccm-ui -->

<script>
+function($) {
	$(function() {
		$(".data-permission-key-edit-button").dialog();
	});
}(jQuery);
</script>
