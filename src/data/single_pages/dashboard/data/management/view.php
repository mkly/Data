<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper(t('Data Types'), false, false) ?>
<ul>
<?php foreach ($dataTypes as $dataType) { ?>
	<li><a href="<?= $this->url('/dashboard/data/management/search', $dataType->dtID) ?>"><?= $dataType->dtName ?></a></li>
<?php } ?>
</ul>
<?= $dashboard->getDashboardPaneFooterWrapper(true) ?>
