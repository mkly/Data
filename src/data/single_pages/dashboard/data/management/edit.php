<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper($dataType->dtName, false, false, false, $navigation) ?>
<form method="post" action="<?= $this->action('edit', $dataType->dtID, $data->dID) ?>">
	<div class="ccm-pane-body">
		<?php foreach ($attributes as $attribute) { ?>
			<?= $ah->display($attribute) ?>
		<?php } ?>
	</div>
	<div class="ccm-pane-footer">
		<div class="ccm-buttons">
			<?= $interface->submit(t('Update')) ?>
			<?= $interface->button(t('Delete'), $this->action('delete', $dataType->dtID, $data->dID)) ?>
			<?= $interface->button(t('Cancel'), $this->action('search', $dataType->dtID)) ?>
		</div>
	</div>
</form>
</div>
<?= $dashboard->getDashboardPaneFooterWrapper(false) ?>
