<?php
defined('C5_EXECUTE') or die('Access Denied.');

if ($settings->multipleAssociations) {
	echo $dataSelector->multiSelect($this->field('value'), $datas, $dataType);
} else {
	echo $dataSelector->select($this->field('value'), $data, $dataType);
}
