<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper(t('Import Data Type'), false, false, false) ?>
<form enctype="multipart/form-data" method="post" action="<?= $this->action('import') ?>">
<div class="ccm-pane-body">
	<?= $form->file('import') ?>
</div>
<div class="ccm-pane-footer">
	<div class="ccm-buttons">
		<?= $interface->submit(t('Import')) ?>
		<?= $interface->button(t('Cancel'), $this->url('/dashboard/data/administration/search')) ?>
	</div>
</div>
</form>
<?= $dashboard->getDashboardPaneFooterWrapper() ?>
