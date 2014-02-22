<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?= $dashboard->getDashboardPaneHeaderWrapper($dataType->dtName, false, false, false, $navigation) ?>
<form method="post" action="<?= $this->action('create', $dataType->dtID) ?>">
	<div class="ccm-pane-body">
		<?php foreach ($attributes as $attribute) { ?>
			<?= $ah->display($attribute) ?>
		<?php } ?>
	</div>
	<div class="ccm-pane-footer">
		<div class="ccm-buttons">
			<?= $interface->submit(t('Create')) ?>
		</div>
	</div>
</form>
</div>
<?= $dashboard->getDashboardPaneFooterWrapper(false) ?>
