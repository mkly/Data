<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataType extends Model {

	protected $datas;
	protected $permissionKeyCategory;
	protected $permissions;
	protected $attributes;
	
	public function __construct($args = null){
    	parent::__construct();
    	if(is_int($args)){
        	self::Load('dtID=?', array($args));
    	}else if(is_string($args)){
        	self::Load('dtHandle=?', array($args));
    	}
	}
	
	static public function getByID($dtID) {
    	$dataType = new DataType($dtID);
    	return $dataType;
	}

	static public function getByHandle($dtHandle) {
        $dataType = new DataType($dtHandle);
        return $dataType;
	}

	public function __get($name) {
		$method = 'get' . ucfirst($name);
		if (method_exists($this, $method)) {
			return $this->$method();
		}
		return parent::__get($name);
	}

	public function reset($name) {
		unset($this->{$name});
	}

	protected function getDatas() {
		if (isset($this->datas)) return $this->datas;

		$DataList = new DataList($this);
		$this->datas = $DataList->get();
		return $this->datas;
	}

	protected function getPermissionKeyCategory() {
		if (isset($this->permissionKeyCategory)) return $this->permissionKeyCategory;

		$this->permissionKeyCategory = PermissionKeyCategory::getByHandle('data_type');
		return $this->permissionKeyCategory;
	}

	protected function getPermissions() {
		if (isset($this->permissions)) return $this->permissions;

		$this->permissions = new Permissions($this);
		return $this->permissions;
	}

	protected function getAttributes() {
		if (isset($this->attributes)) return $this->attributes;

		$this->attributes = DataAttributeKey::getListByDataTypeID($this->dtID);
		return $this->attributes;
	}

	protected function isAdvancedPermissions() {
		return PERMISSIONS_MODEL === 'advanced';
	}

	public function getPermissionObjectIdentifier() {
		return $this->dtID;
	}

	public function import($node) {
		if ($node->getName() !== 'datatype') {
			throw new DataTypeException(t('Invalid Element'));
		}
		$dataType = new DataType;
		$dataType->dtName = $node->attributes()->dtName;
		$dataType->dtHandle = $node->attributes()->dtHandle;
		$dataType->Insert();
		if ($node->attributekeys) {
			foreach ($node->attributekeys->attributekey as $akNode) {
				DataAttributeKey::import($akNode);
			}
		}
		if ($node->children()->data) {
			foreach ($node->children()->data as $data) {
				$data = new Data;
				$data->dtID = $dataType->dtID;
				$data->Insert();
			}
		}
		return $dataType;
	}

	/**
	 * @param SimpleXMLElement $xml
	 */
	public function export($xml) {
		$node = $xml->addChild('datatype');
		$node->addAttribute('dtHandle', $this->dtHandle);
		$node->addAttribute('dtName', $this->dtName);
		$attributes = $node->addChild('attributekeys');
		foreach ($this->getAttributes() as $attribute) {
			$attribute->export($attributes);
		}
		return $node;
	}

	public function validate() {
		$e = Loader::helper('validation/error');

		if (strlen($this->dtName) > 255) {
			$e->add(t('Maximum Name length is 255 characters'));
		}
		if (strlen($this->dtHandle) > 255) {
			$e->add(t('Maximum Handle length is 255 characters'));
		}
		if (preg_match('/[^a-z0-9_]/', $this->dtHandle)) {
			$e->add(t('Handle can only contain a-z 0-9 and _'));
		}
		if (strlen($this->dtName) === 0) {
			$e->add(t('Name is required'));
		}
		if (strlen($this->dtHandle) === 0) {
			$e->add(t('Handle is required'));
		}

		$db = Loader::db();
		if ($dtID = $db->GetOne('
			SELECT dtID
			FROM DataTypes
			WHERE dtName = ?
		', array($this->dtName))) {
			$dataType = new DataType;
			$dataType->Load('dtID=?', $dtID);
			$e->add(t('Name already in use by %s', $dataType->name));
		}
		if ($db->GetOne('
			SELECT dtID
			FROM DataTypes
			WHERE dtName = ?
		', array($this->dtHandle))) {
			$e->add(t('Handle already in use by %s', $dataType->name));
		}

		return $e;
	}

	public function Insert() {
		parent::Insert();
		if ($this->isAdvancedPermissions()) {
			foreach (DataTypePermissionKey::getList() as $pk) {
				$pk->setPermissionObject($this);
				$pa = PermissionAccess::create($pk);
				$pe = GroupPermissionAccessEntity::getOrCreate(Group::getByID(ADMIN_GROUP_ID));
				$pa->addListItem($pe, false, PermissionKey::ACCESS_TYPE_INCLUDE);
				$pao = $pk->getPermissionAssignmentObject();
				$pao->assignPermissionAccess($pa);
			}
		}
	}

	public function Delete() {
		if ($this->isAdvancedPermissions()) {
			foreach (DataTypePermissionKey::getList() as $pk) {
				$pk->setPermissionObject($this);
				$pk->getPermissionAssignmentObject()
				   ->clearPermissionAssignment();
			}
		}

		foreach ($this->getAttributes() as $ak) {
			$ak->delete();
		}

		foreach ($this->getDatas() as $data) {
			$data->Delete();
		}

		parent::Delete();
	}

}
