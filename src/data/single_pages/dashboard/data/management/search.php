<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper($dataType->dtName, false, false, false) ?>
<div class="ccm-pane-options">
	<form class="form-horizontal">
		<div class="ccm-pane-options-permanent-search">
			<input placeholder="Name" type="text" class="ccm-input-text" name="name" />
			<?= $interface->submit(t('Search')) ?>
		</div>
	</form>
</div>
<div class="ccm-pane-body">
	<?= $interface->button(t('Create'), $this->url('/dashboard/data/management/create', $dataType->dtID)) ?>
	<div class="clearfix"></div>
	<?php if ($datas) { ?>
		<table class="ccm-results-list">
			<tr class="ccm-results-list-header">
				<th><?= t('Name') ?></th>
			</tr>
		<?php $alt = ''; foreach ($datas as $data) { ?>
			<tr class="ccm-list-record<?= $alt ?>">
				<td><?= $data->name ? $data->name->getValue('display_sanitized') : $data->dID ?><?= $interface->button(t('Edit'), $this->url('/dashboard/data/management/edit', $dataType->dtID, $data->dID)) ?></td>
			</tr>
		<?php $alt = $alt ? '' : ' ccm-list-record-alt'; } ?>
		</table>
		<?php $DataList->displayPagingV2() ?>
	<?php } ?>
</div>
<div class="ccm-pane-footer">
</div>
<?= $dashboard->getDashboardPaneFooterWrapper(false) ?>
