<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper(t('Data Type') . ': ' . $dataType->dtName, false, false, false, $navigation) ?>
<div class="ccm-pane-body">
	<h1><?= $dataType->dtName ?></h1>
	<h3><small><?= t('Handle') ?>:</small> <?= $dataType->dtHandle ?></h3>
	<h3 style="margin-top: 1em"><?= t('Attributes') ?></h3>
	<table class="ccm-results-list">
		<tr class="ccm-results-list">
			<th><?= t('Name') ?></th>
			<th><?= t('Handle') ?></th>
			<th><?= t('Type') ?></th>
		</tr>
		<?php if (!$dataType->attributes) { ?>
			<tr><td colspan="3"><?= t('No attributes found') ?></td></tr>
		<?php } else { ?>
			<?php foreach ($dataType->attributes as $attribute) { ?>
				<tr>
					<td><?= $attribute->getAttributeKeyName() ?></td>
					<td><?= $attribute->getShortHandle() ?></td>
					<td><?= $attribute->getAttributeKeyType()->getAttributeTypeName() ?></td>
				</tr>
			<?php } ?>
		<?php } ?>
	</table>
</div>
<div class="ccm-pane-footer">
	<?php if ($dataType->permissions->canEditDataTypePermissions()) { ?><a href="<?= $dataType->permissionKeyCategory->getToolsURL('display_list') . h('&dtID=' . $dataType->dtID) ?>"
	   class="btn ccm-button-v2-right"
	   id="data-permissions-dialog-button"
	   data-permissions-dialog-button=""
	   dialog-title="<?= t('Permissions') ?>"
	   dialog-modal="true"
	   dialog-height="500"
	   dialog-width="420"
	   dialog-append-buttons="true"
	><?= t('Permissions') ?></a><?php } ?><?php if ($dataType->permissions->canEditDataType()) { ?><?= $interface->button(t('Metadata'), $this->url('/dashboard/data/administration/edit', $dataType->dtID)) ?><?= $interface->button(t('Attributes'), $this->url('/dashboard/data/administration/attributes', $dataType->dtID)) ?><?php } ?><h3 style="float: right; margin: 0;"><?= t('Edit') ?>:</h3><?= $interface->button(t('Export'), $this->url('/dashboard/data/administration/export', $dataType->dtID), 'left') ?>
</div>
<script>
+function($) {
	$(function() {
		$("[data-permissions-dialog-button]").dialog();
	});
}(jQuery);
</script>
<?= $dashboard->getDashboardPaneFooterWrapper(true) ?>
