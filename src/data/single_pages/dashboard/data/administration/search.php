<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper(t('Data Type Administration'), false, false, false) ?>
<div class="ccm-pane-options clearfix">
	<div class="ccm-buttons">
		<?= $interface->button(t('Create'), $this->url('/dashboard/data/administration/create'), 'right', null, array('style' => 'margin-left: 10px')) ?>
		<?= $interface->button(t('Import'), $this->url('/dashboard/data/administration/import')) ?>
	</div>
</div>
<div class="ccm-pane-body">
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
</div>
<div class="ccm-pane-footer">
</div>

<?= $dashboard->getDashboardPaneFooterWrapper() ?>
