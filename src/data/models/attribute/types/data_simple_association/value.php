<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataSimpleAssociationAttributeTypeValue extends Model {
	public $_table = 'atDataSimpleAssociation';

	public function getData() {
		$data = new Data;
		$data->Load('dID=?', array($this->dID));
		return $data;
	}
}
