<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataList extends DatabaseItemList implements Iterator {

	protected $dataType;
	protected $attributeClass = 'DataAttributeKey';
	protected $attributeFilters = array();
	protected $cursor = false;
	protected $row = false;
	protected $eof = false;
	
	/**
	 * @param $dataType DataType
	 */
	public function __construct($dataType) {
		$this->dataType = $dataType;
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
	 * Returns an array of whatever objects extends this class (e.g. PageList returns a list of pages).
	 */
	public function getRow($itemsToGet = 0, $offset = 0) {
		$q = $this->executeBase();
		
		// handle order by
		$this->setupAttributeSort();
		$this->setupAutoSort();
		$this->setupSortByString();
		
		if ($this->sortByString != '') {
			$q .= 'order by ' . $this->sortByString . ' ';
		}	
		if ($this->itemsPerPage > 0 && (intval($itemsToGet) || intval($offset)) ) {
			$q .= 'limit ' . $offset . ',' . $itemsToGet . ' ';
		}
		
		$db = Loader::db();
		if ($this->debug) { 
			Database::setDebug(true);
		}
		$resp = $db->Execute($q);
		if ($this->debug) { 
			Database::setDebug(false);
		}
		
		$this->start = $offset;
		return $resp;
	}
	
	/**
	 * Returns the numeric key of the current data element
	 * @return int
	 */
	public function key() {
		return $this->row['dID'];
	}
	
	/**
	 * Returns the current Data element
	 * @return \Data
	 */
	public function current() {
		$data = new Data;
		$data->Load('dID=?', array($this->row['dID']));
		return $data;
	}
	
	/**
	 * Move forward to next element in iterator
	 */
	public function next() {
		// initialize cursor
		if (!$this->cursor) {
			$this->rewind();
		}

		$this->ind++;        
		$this->row = $this->cursor->FetchRow();
		$this->eof = !is_array($this->row);        
	}
	
	/**
	 * Rewind iterator to start with first element
	 */
	public function rewind() {
		if ($this->getQuery() == '') {
			$this->setBaseQuery();
		}
		$this->cursor = $this->getRow($itemsToGet, $offset);
	}

	/**
	 * Returns true if we're at a valid position
	 * @return bool
	 */
	public function valid() {
		return !$this->eof;
	}	
}
