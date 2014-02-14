<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<fieldset>
<legend><?= t('Simple Association') ?></legend>

<div class="clearfix">
	<label><?= t('Data Type') ?></label>
	<div class="input">
		<?= $dataTypeSelector->select('assoc_dtID', $settings->dtID) ?>
	</div>
</div>
<div class="clearfix">
	<label style="padding-top: 0"><?= t('Allow Mutliple Associations') ?></label>
	<div class="input">
		<div>
			<?= $form->checkbox('multipleAssociations', 1, $settings->multipleAssociations) ?>
		</div>
	</div>
</div>
</fieldset>
