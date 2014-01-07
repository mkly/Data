<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataAttributeKey extends AttributeKey {

	protected $searchIndexFieldDefinition = 'dID I(11) UNSIGNED NOTNULL DEFAULT 0 PRIMARY';

	/**
	 * @return string
	 */
	public function getIndexedSearchTable() {
		return 'DataSearchIndexAttributes';
	}

	/**
	 * @param $akID int
	 * @return DataAttributeKey|bool
	 */
	public static function getByID($akID) {
		$ak = new DataAttributeKey;
		$ak->load($akID);
		if ($ak->getAttributeKeyID()) {
			return $ak;
		}
	}

	/**
	 * @param $dID int Data id
	 * @param $method string
	 */
	public static function getAttributes($dID, $method = 'getValue') {
		$avl = new AttributeValueList;

		foreach (Loader::db()->GetAll('
			SELECT avID, akID
			FROM   DataAttributeValues
			WHERE  dID = ?
		', array($dID)) as $row) {
			if ($ak = DataAttributeKey::getByID($row['akID'])) {
				$data = new Data;
				$data->Load('dID=?', array($dID));
				$av = $data->getAttributeValueObject($ak);
				$avl->addAttributeValue(
					$ak,
					$av->getValue($this)
				);
			}
		}

		return $avl;
	}

	/**
	 * @param $akID int
	 */
	public function load($akID) {
		parent::load($akID);
		$this->setPropertiesFromArray(Loader::db()->GetRow('
			SELECT     dt.dtID dtID,
			           dt.dtHandle handle,
			           dt.dtName name
			FROM       DataAttributeKeys dak
			INNER JOIN DataTypes dt
			ON dt.dtID = dak.dtID
			WHERE  akID = ?
		', array($akID)));
	}

	/**
	 * @param $akHandle
	 * @return DataAttributeKey|bool
	 */
	public static function getByHandle($akHandle) {
		$cachedAK = CacheLocal::getEntry('data_attribute_key_by_handle', $akHandle);
		if ($cachedAK == -1) {
			return false;
		}
		if ($cachedAK) {
			return $cachedAK;
		}

		if ($ak = static::getByID(Loader::db()->GetOne("
			SELECT     ak.akID
			FROM       AttributeKeys ak
			INNER JOIN DataAttributeKeys dak
			ON         dak.akID = ak.akID
			INNER JOIN AttributeKeyCategories akc
			ON         ak.akCategoryID = akc.akCategoryID
			WHERE      ak.akHandle = ?
			AND        akc.akCategoryHandle = ?
		", array($akHandle, 'data')))) {
			CacheLocal::set('data_attribute_key_by_handle', $akHandle, $ak);
			return $ak;
		}
		CacheLocal::set('data_attribute_key_by_handle', $akHandle, -1);
		return false;
	}

	public static function getListByDataTypeID($dtID, $filters = array()) {
		$q = "
			SELECT     ak.akID
			FROM       DataAttributeKeys dak
			INNER JOIN AttributeKeys ak
			ON         ak.akID = dak.akID
			INNER JOIN AttributeKeyCategories akc
			ON         ak.akCategoryID = akc.akCategoryID
			WHERE      akc.akCategoryHandle = ?
			AND        dak.dtID = ?
		";
		foreach ($filters as $key => $value) {
			$q .= ' and ' . $key . ' = ' . $value . ' ';
		}

		$daks = array();
		foreach (Loader::db()->GetCol($q, array('data', $dtID)) as $akID) {
			$daks[] = DataAttributeKey::getByID($akID);
		}
		return $daks;
	}

	public static function getList($filters = array()) {
		$q = '
			SELECT     akID
			FROM       AttributeKeys ak
			INNER JOIN AttributeKeyCategories akc
			ON         akc.akCategoryID = ak.akCategoryID
			WHERE      akCategoryHandle = "data"
		';
		foreach ($filters as $key => $value) {
			$q .= ' AND ' . $key . ' = ' . $value . ' ';
		}

		$list = array();
		foreach (Loader::db()->GetCol($q) as $akID) {
			if ($ak = DataAttributeKey::getByID($akID)) {
				$list[] = $ak;
			}
		}

		return $list;
	}

/*
	public static function getList() {
		return parent::getList('data');
	}
*/

	public static function getSearchableList() {
		return parent::getList('data', array('akIsSearchable' => 1));
	}

	public static function getSearchableIndexList() {
		return parent::getList('data', array('akIsSearchableIndexed' => 1));
	}

	public static function getUserAddedList() {
		return parent::getList('data', array('akIsAutoCreated' => 0));
	}

	public static function getColumnHeaderList() {
		return parent::getList('data', array('akIsColumnHeader' => 1));
	}

	public function get($akID) {
		return static::getByID($akID);
	}

	/**
	 * @param $data Data
	 */
	protected function saveAttribute($data, $value = false) {
		$av = $data->getAttributeValueObject($this, true);
		parent::saveAttribute($av, $value);

		Loader::db()->Replace('DataAttributeValues', array(
			'dID' => $data->dID,
			'akID' => $this->getAttributeKeyID(),
			'avID' => $av->getAttributeValueID()
		), array('dID', 'akID'), true);

		$data->reindex();
	}

	public function add($type, $args, $dataType) {
		$args['akHandle'] = 'data_' . $dataType->dtHandle . '_' . $args['akHandle'];
		$ak = parent::add(
			'data',
			$type,
			$args,
			Package::getByHandle('data')
		);

		Loader::db()->AutoExecute(
			'DataAttributeKeys',
			array_merge($args, array('akID' => $ak->getAttributeKeyID())),
			'INSERT'
		);

		$dak = new DataAttributeKey;
		$dak->load($ak->getAttributeKeyID());
		return $dak;
	}

	public function update($args, $dataType) {
		$args['akHandle'] = 'data_' . $dataType->dtHandle . '_' . $args['akHandle'];
		$ak = parent::update($args);
		$db = Loader::db();
		$db->AutoExecute(
			'DataAttributeKeys',
			$args,
			'UPDATE',
			'akID = ' . $db->quote($ak->getAttributeKeyID())
		);
		$dak = new DataAttributeKey;
		$dak->load($ak->getAttributeKeyID());
		return $dak;
	}

	public function delete() {
		parent::delete();
		$db = Loader::db();

/*
		$res = $db->Execute('
			SELECT avID
			FROM   DataAttributeKeys
			WHERE  akID = ?
		', array($this->getAttributeKeyID()));

		while ($row = $res->FetchRow()) {
			$db->Execute('
				DELETE
				FROM   AttributeValues
				WHERE  avID = ?
			', array($row['avID']));
		}
*/

		$db->Execute('
			DELETE
			FROM   DataAttributeValues
			WHERE  akID = ?
		', array($this->getAttributeKeyID()));

		$db->Execute('
			DELETE
			FROM   DataAttributeKeys
			WHERE  akID = ?
		', $this->getAttributeKeyID());
	}
}
