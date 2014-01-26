<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper(t('Edit Data Type'), false, false, false) ?>
<form method="post" action="<?= $this->action('delete', $datatype->dtID) ?>">
	<div class="ccm-pane-body">
		<h3>Are you sure you want to delete this data type?</h3>
	</div>
	<div class="ccm-pane-footer">
		<div class="ccm-buttons">
			<?= $interface->submit(t('Delete')) ?>
		</div>
	</div>
</form>
<?= $dashboard->getDashboardPaneFooterWrapper(false) ?>
