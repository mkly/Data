<?php defined('C5_EXECUTE') or die('Access Denied.') ?>

<?= $dashboard->getDashboardPaneHeaderWrapper(t('Delete Attribute'), false, false, false, $navigation) ?>
<form method="post">
<div class="ccm-pane-body">
	<h2><?= t('Warning') ?></h2>
	<p><?= t(/*i18n: %s is a DataType Name and a Data Name*/'You are about to delete %s. There is no undo.', $dataType->dtName . ' ' . '<strong>' . $data->name->getValue('display') . '</strong>') ?></p>
</div>
<div class="ccm-pane-footer">
	<div class="ccm-buttons">
		<?= $interface->submit(t('Delete')) ?>
		<?= $interface->button(t('Cancel'), $this->action('search', $dataType->dtID)) ?>
	</div>
</div>
</div>
<?= $dashboard->getDashboardPaneFooterWrapper(false) ?>
