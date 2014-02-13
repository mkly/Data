<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataSingleChildAssociationAttributeTypeValue extends Model {
	public $_table = 'atDataSingleChildAssociation';

	public function getData() {
		$data = new Data;
		$data->Load('dID=?', array($this->dID));
		return $data;
	}
}
