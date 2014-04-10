<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataList extends DatabaseItemList {

	protected $dataType;
	protected $attributeClass = 'DataAttributeKey';
	protected $attributeFilters = array();

	/**
	 * @param $dataType DataType
	 */
	public function __construct($dataType) {
		$this->dataType = $dataType;
	}
	
	/**
	 * @param $dtID int
	 * @return DataType|null Null if DataType not found
	 */
	static public function getByDataTypeID($dtID) {
		$dataType = new DataType;
		if ($dataType->Load('dtID=?', array($dtID))) {
			$dataList = new DataList($dataType);
			return $dataList;
		}
		return null;
	}

	/**
	 * @param $dtHandle string
	 * @return DataType|null Null if DataType not found
	 */
	static public function getByDataTypeHandle($dtHandle) {
		$dataType = new DataType;
		if ($dataType->Load('dtHandle=?', array($dtHandle))) {
			$dataList = new DataList($dataType);
			return $dataList;
		}
		return null;
	}

	protected function setBaseQuery() {
		$this->setQuery('
			SELECT d.dID
			FROM Datas d
		');
		$this->addToQuery('
			INNER JOIN DataTypes dt
			ON         dt.dtID = d.dtID
		');
		$this->filter('d.dtID', $this->dataType->dtID);
		$this->setupAttributeFilters('
			INNER JOIN DataSearchIndexAttributes dsia
			ON         dsia.dID = d.dID
		');
	}

	/**
	 * @param $handle string Handle for Data Attribute without prefix
	 * @param $value string
	 * @param $comparison string
	 */
	public function filterByAttribute($handle, $value, $comparison = '=') {
		return parent::filterByAttribute(
			'data_' . $this->dataType->dtHandle . '_' . $handle,
			$value,
			$comparison
		);
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

	/**
	 * @return DataType
	 */
	public function getDataType() {
		return $this->dataType;
	}
	
	/**
	 * @param SimpleXMLElement $xml
	 */
	public function export($xml) {
		$node = $xml->addChild('datalist');
		$node->addAttribute('dtHandle', $this->dtHandle);
		$node->addAttribute('dtName', $this->dtName);
		$attributes = $node->addChild('datasets');
		
		$datas = $this->get($this->getTotal(), 0);
		/*
		foreach ($datas as $data) {
			$attribute->export($attributes);
		}*/
		return $node;
	}
}
