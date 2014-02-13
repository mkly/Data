<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<fieldset>
<legend><?= t('Single Child Association') ?></legend>

<div class="clearfix">
	<label><?= t('Data Type') ?></label>
	<div class="input">
		<?= $dataTypeSelector->select('assoc_dtID', $dtID) ?>
	</div>
</div>
</fieldset>
