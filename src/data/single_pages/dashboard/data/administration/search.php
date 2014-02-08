<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper(t('Data Type Administration'), false, false) ?>
<?= $interface->button(t('Create'), $this->url('/dashboard/data/administration/create')) ?>
<div class="clearfix"></div>
<table class="ccm-results-list">
	<tr class="ccm-results-list-header">
		<th><?= t('Name' ) ?></th>
	</tr>
	<?php if (!$dataTypes) { ?>
		<tr class="ccm-list-record"><td><?= t('No Data Types found') ?></td></tr>
	<?php } else { ?>
		<?php $alt = ''; foreach ($dataTypes as $dataType) { ?>
			<?php if (!$dataType->permissions->canViewDataType()) continue ?>
			<tr class="ccm-list-record<?= $alt ?>">
<td><a href="<?= $this->url('/dashboard/data/administration', $dataType->dtID) ?>"><?= $dataType->dtName ?></a></td>
			</tr>
		<?php $alt = $alt ? '' : ' ccm-list-record-alt'; } ?>
	<?php } ?>
</table>

<?= $dashboard->getDashboardPaneFooterWrapper(true) ?>
