<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<fieldset>
<legend><?= t('Has Many Association') ?></legend>

<div class="clearfix">
	<label><?= t('Data Type') ?></label>
	<div class="input">
		<?= $dataTypeSelector->select('dtID') ?>
	</div>
</div>
</fieldset>
