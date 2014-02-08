<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper(t('Data') . ': ' . $data->name->getValue('display'), false, false, false) ?>
<div class="ccm-pane-body">
	<h1><?= $data->name->getValue('display') ?></h1>
	<h3><small><?= t('Data Type') ?>:</small> <?= $data->getDataType()->dtName ?></h3>
	<h3 style="margin-top: 1em"><?= t('Attributes') ?></h3>
	<table class="ccm-results-list">
		<tr class="ccm-results-list-header">
			<th><?= t('Name') ?></th>
			<th><?= t('Value') ?></th>
		</tr>
		<?php foreach ($data->getAttributeValueObjects() as $avo) { ?>
			<tr>
				<td><?= $avo->getAttributeKey()->getAttributeKeyName() ?></td>
				<td><?= $avo->getValue('display') ?></td>
			</tr>
		<?php } ?>
	</table>
</div>
<div class="ccm-pane-footer">
	<div class="ccm-buttons">
		<?php if ($data->getDataType()->permissions->canViewEditInterface()) { ?><?= $interface->button(t('Edit'), $this->url('/dashboard/data/management/edit', $data->dtID, $data->dID)) ?><?php } ?>
	</div>
</div>
<?= $dashboard->getDashboardPaneFooterWrapper(false) ?>
