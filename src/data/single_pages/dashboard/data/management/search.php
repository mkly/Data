<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper($dataType->dtName, false, false, false, $navigation) ?>
<div class="ccm-pane-options">
	<form class="form-horizontal">
		<div class="ccm-pane-options-permanent-search">
			<input placeholder="Name" type="text" class="ccm-input-text" name="name" />
			<?= $interface->submit(t('Search')) ?>
		</div>
	</form>
</div>
<div class="ccm-pane-body">
	<?php if ($datas) { ?>
		<table class="ccm-results-list">
			<tr class="ccm-results-list-header">
				<th><?= t('Name') ?></th>
			</tr>
		<?php $alt = ''; foreach ($datas as $data) { ?>
			<tr class="ccm-list-record<?= $alt ?>">
				<td>
					<?php if ($data->name) { ?>
						<a href="<?= $this->url('/dashboard/data/management', $data->dID) ?>"><?= h($data->name->getValue('display')) ?></a>
					<?php } else { ?>
						<?= '&nbsp;' ?>
					<?php } ?>
					<?php if ($data->getDataType()->permissions->canEditData()) { ?>
						<?= $interface->button(t('Edit'), $this->url('/dashboard/data/management/edit', $dataType->dtID, $data->dID)) ?>
						<?= $interface->button(t('Duplicate'), $this->url('/dashboard/data/management/duplicate', $dataType->dtID, $data->dID)) ?>
					<?php } ?>
				</td>
			</tr>
		<?php $alt = $alt ? '' : ' ccm-list-record-alt'; } ?>
		</table>
		<?php $DataList->displayPagingV2() ?>
	<?php } ?>
</div>
<div class="ccm-pane-footer">
	<div class="ccm-buttons">
		<?php if ($dataType->permissions->canViewData()) { ?>
			<?= $interface->button(t('Download CSV'), $this->url('/dashboard/data/management/download_csv', $dataType->dtID), 'left') ?>
		<?php } ?>
		<?php if ($dataType->permissions->canViewData()) { ?>
			<?= $interface->button(t('Download XML'), $this->url('/dashboard/data/management/download_xml', $dataType->dtID), 'left') ?>
		<?php } ?>
		<?php if ($dataType->permissions->canCreateData()) { ?>
			<?= $interface->button(t('Create'), $this->url('/dashboard/data/management/create', $dataType->dtID)) ?>
		<?php } ?>
	</div>
</div>
<?= $dashboard->getDashboardPaneFooterWrapper(false) ?>
