<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper(t('Data Type') . ': ' . $dataType->dtName, false, false, false) ?>
<div class="ccm-pane-body">
	<p><h4 style="display: inline"><?= t('Handle') ?>:</h4> <?= $dataType->dtHandle ?></p>
	<p><h4 style="display: inline"><?= t('Name') ?>:</h4> <?= $dataType->dtName ?></p>
	<h2><?= t('Attributes') ?></h2>
</div>
<div class="ccm-pane-footer">
</div>
<?= $dashboard->getDashboardPaneFooterWrapper(true) ?>
