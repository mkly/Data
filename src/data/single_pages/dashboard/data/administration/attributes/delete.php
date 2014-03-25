<?php defined('C5_EXECUTE') or die('Access Denied.') ?>

<?= $dashboard->getDashboardPaneHeaderWrapper(t('Delete %s Attribute', $dataType->dtName), false, false, false, $navigation) ?>
<form method="post">
<div class="ccm-pane-body">
	<h2><?= t('Warning') ?></h2>
	<p><?= t(/*i18n: %s is an attribute name*/'You are about to delete the attribute "%s". There is no undo.', '<strong>' . $key->getAttributeKeyName(). '</strong>') ?></p>
</div>
<div class="ccm-pane-footer">
	<div class="ccm-buttons">
		<?= $interface->submit(t('Delete')) ?>
	</div>
</div>
</div>
<?= $dashboard->getDashboardPaneFooterWrapper(false) ?>
