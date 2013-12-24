<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper(t('Data Types'), false, false) ?>
<?= $interface->button(t('Create'), $this->url('/dashboard/data/administration/create')) ?>
<div class="clearfix"></div>
<ul>
<?php foreach ($dataTypes as $dataType) { ?>
	<li class="clearfix"><?= $dataType->dtName ?><?= $interface->button(t('Edit'), $this->url('/dashboard/data/administration/edit', $dataType->dtID)) ?><?= $interface->button(t('Attributes'), $this->url('/dashboard/data/administration/attributes', $dataType->dtID)) ?></li>
<?php } ?>
</ul>
<?= $dashboard->getDashboardPaneFooterWrapper(true) ?>
