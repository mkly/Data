<?php defined('C5_EXECUTE') or die('Access Denied.') ?>

<?= $dashboard->getDashboardPaneHeaderWrapper(t('Delete %s Attribute', $dataType->dtName), false, false, false) ?>
<form method="post">
<div class="ccm-pane-body">
	<h2><?= t('Warning') ?></h2>
	<p><?= t('You are about to delete the attribute%s%s. There is no undo.', ': ', '<strong>' . $key->getAttributeKeyDisplayName(). '</strong>') ?></p>
</div>
<div class="ccm-pane-footer">
	<div class="ccm-buttons">
		<?= $interface->submit(t('Delete')) ?>
	</div>
</div>
</div>
<?= $dashboard->getDashboardPaneFooterWrapper(false) ?>
