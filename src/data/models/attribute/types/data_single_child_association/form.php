<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<div style="background: pink">
<?= $form->hidden($this->field('dID'), $data->dID) ?>
<?= $form->hidden($this->field('dtID'), $dataType->dtID) ?>
<?php foreach ($attributes as $attribute) { ?>
	<?= $ah->display($attribute) ?>
<?php } ?>
