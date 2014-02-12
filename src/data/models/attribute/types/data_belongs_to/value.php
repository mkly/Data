<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataBelongsToAttributeTypeValue extends Model {
	public $_table = 'atDataBelongsTo';

	public function getData() {
		$data = new Data;
		$data->Load('dID=?', array($this->dID));
		return $data;
	}
}
