<?php defined('C5_EXECUTE') or die('Access Denied.') ?>
<?php foreach ($attributes as $attribute) { ?>
	<?= $ah->display($attribute) ?>
<?php } ?>
