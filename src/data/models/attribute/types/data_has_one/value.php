<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataHasOneAttributeTypeValue extends Model {
	public $_table = 'atDataHasOne';

	public function getData() {
		$data = new Data;
		$data->Load('dID=?', array($this->dID));
		return $data;
	}
}
