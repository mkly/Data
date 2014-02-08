<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper(t('Data Type Management'), false, false) ?>
<table class="ccm-results-list">
	<tr class="ccm-results-list-header">
		<th><?= t('Name') ?></th>
	</tr>
	<?php $alt = ''; foreach ($dataTypes as $dataType) { ?>
		<tr class="ccm-list-record<?= $alt ?>">
			<td><a href="<?= $this->url('/dashboard/data/management/search', $dataType->dtID) ?>"><?= $dataType->dtName ?></a></td>
		</tr>
	<?php $alt = $alt ? '' : ' ccm-list-record-alt'; } ?>
</table>
<?= $dashboard->getDashboardPaneFooterWrapper(true) ?>
