<?php
defined('C5_EXECUTE') or die('Access Denied.');

if ($settings->multipleAssociations) {
	$dataSelector->multiSelect($this->field('value'), array($data->dID), $dataType);
} else {
	$dataSelector->select($this->field('value'), $data->dID, $dataType);
}
