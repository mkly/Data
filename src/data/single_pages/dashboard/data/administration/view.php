<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper(t('Data Types'), false, false) ?>
<?= $interface->button(t('Create'), $this->url('/dashboard/data/administration/create')) ?>
<div class="clearfix"></div>
<table class="ccm-results-list">
	<tr class="ccm-results-list-header">
		<th><?= t('Name' ) ?></th>
	</tr>
	<?php $alt = ''; foreach ($dataTypes as $dataType) { ?>
		<tr class="ccm-list-record<?= $alt ?>">
			<td><?= $dataType->dtName ?><?= $interface->button(t('Edit'), $this->url('/dashboard/data/administration/edit', $dataType->dtID)) ?><?= $interface->button(t('Attributes'), $this->url('/dashboard/data/administration/attributes', $dataType->dtID)) ?></td>
	<?php $alt = $alt ? '' : ' ccm-list-record-alt'; } ?>
</table>
<?= $dashboard->getDashboardPaneFooterWrapper(true) ?>
