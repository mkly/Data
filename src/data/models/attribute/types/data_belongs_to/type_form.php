<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<fieldset>
<legend><?= t('Belongs To Association') ?></legend>

<div class="clearfix">
	<label><?= t('Data Type') ?></label>
	<div class="input">
		<?= $dataTypeSelector->select('belongsTo_dtID', $dtID) ?>
	</div>
</div>
</fieldset>
