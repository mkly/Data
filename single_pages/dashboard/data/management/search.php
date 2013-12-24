<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper($dataType->dtName, false, false, false) ?>
<div class="ccm-pane-options">
</div>
<div class="ccm-pane-body">
	<?= $interface->button(t('Create'), $this->url('/dashboard/data/management/create', $dataType->dtID)) ?>
	<div class="clearfix"></div>
	<ul>
	<?php foreach ($datas as $data) { ?>
		<li class="clearfix"><?= $data->name ? $data->name->getValue('display_sanitized') : $data->dID ?><?= $interface->button(t('Edit'), $this->url('/dashboard/data/management/edit', $dataType->dtID, $data->dID)) ?></li>
	<?php } ?>
	</ul>
	<?php $DataList->displayPagingV2() ?>
</div>
<div class="ccm-pane-footer">
</div>
<?= $dashboard->getDashboardPaneFooterWrapper(false) ?>
