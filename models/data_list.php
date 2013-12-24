<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataList extends DatabaseItemList {

	protected function setBaseQuery() {
		$this->setQuery('SELECT d.dID FROM Datas d ');
		$this->addToQuery('INNER JOIN DataTypes dt ON dt.dtID = d.dtID ');
	}

	/**
	 * @param $itemsToGet int
	 * @param $offset int
	 * @return array of Data
	 */
	public function get($itemsToGet = 100, $offset = 0) {
		if (!$this->getQuery()) {
			$this->setBaseQuery();
		}
		$datas = array();
		$rows = parent::get($itemsToGet, $offset);
		foreach ($rows as $row) {
			$data = new Data;
			$data->Load('dID=?', array($row['dID']));
			$datas[] = $data;
		}
		return $datas;
	}

	/**
	 * @return int
	 */
	public function getTotal() {
		if (!$this->getQuery()) {
			$this->setBaseQuery();
		}
		return parent::getTotal();
	}

	/**
	 * @param $dtID int
	 */
	public function filterByDataTypeID($dtID) {
		$this->filter('d.dtID', $dtID, '=');
	}

	/**
	 * @param $dtHandle string
	 */
	public function filterByDataTypeHandle($dtHandle) {
		$this->filter('dt.handle', $dtHandle, '=');
	}

	/**
	 * @param $dtName string
	 */
	public function filterByDataTypeName($dtName) {
		$this->filter('dt.name', $dtName, '=');
	}

	/**
	 * @param $dataType DataType
	 */
	public function filterByDataType($dataType) {
		$this->filterByDataTypeID($dataType->dtID);
	}

	public function __call($method, $args) {
		if (strpos('filterBy', $method) !== 0) {
			return;
		}
		$handle = Loader::helper('text')->uncamelcase(substr($method, 8));
		if (count($args) > 1) {
			$this->filterByAttribute($handle, $args[0], $args[1]);
			return;
		}
		$this->filterByAttribute($handle, $args[0]);
	}
}
