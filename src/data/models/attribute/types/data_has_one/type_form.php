<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<fieldset>
<legend><?= t('Has One Association') ?></legend>

<div class="clearfix">
	<label><?= t('Data Type') ?></label>
	<div class="input">
		<?= $dataTypeSelector->select('hasOne_dtID', $dtID) ?>
	</div>
</div>
</fieldset>
