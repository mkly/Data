<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper(t('Data Types'), false, false) ?>
<table class="ccm-results-list">
	<?php $alt = ' ccm-list-record-alt'; foreach ($dataTypes as $dataType) { ?>
		<tr class="ccm-list-record<?= $alt ?>">
			<td><a href="<?= $this->url('/dashboard/data/management/search', $dataType->dtID) ?>"><?= $dataType->dtName ?></a></td>
		</tr>
	<?php $alt = $alt ? '' : ' ccm-list-record-alt'; } ?>
</table>
<?= $dashboard->getDashboardPaneFooterWrapper(true) ?>
