<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper(t('Edit Data Type'), false, false, false) ?>
<form method="post" action="<?= $this->action('edit', $dataType->dtID) ?>">
	<div class="ccm-pane-body">
		<?= $form->label('dtName', t('Name')) ?>
		<?= $form->text('dtName', $dataType->dtName) ?>
		<?= $form->label('dtHandle', t('Handle')) ?>
		<?= $form->text('dtHandle', $dataType->dtHandle) ?>
	</div>
	<div class="ccm-pane-footer">
		<div class="ccm-buttons">
			<?= $interface->submit(t('Save')) ?>
			<?= $interface->button(t('Delete'), $this->url('/dashboard/data/administration/delete', $dataType->dtID)) ?>
		</div>
	</div>
</form>
<?= $dashboard->getDashboardPaneFooterWrapper(false) ?>
