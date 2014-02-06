<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataPackage extends Package {

	protected $pkgHandle = "data";
	protected $appVersionRequired = "5.6";
	protected $pkgVersion = "0.1";

	public function getPackageName() {
		return t('Data');
	}

	public function getPackageDescription() {
		return t('Data for concrete5');
	}

	public function registerAutoloaders() {
		Loader::registerAutoload(array(
			'DataAttributeKey' => array('model', 'attribute/categories/data', 'data'),
			'DataAttributeValue' => array('model', 'attribute/values/data', 'data'),
			'Data' => array('model', 'data', 'data'),
			'DataException' => array('model', 'data_exception', 'data'),
			'DataList' => array('model', 'data_list', 'data'),
			'DataListException' => array('model', 'data_list_exception', 'data'),
			'DataType' => array('model', 'data_type', 'data'),
			'DataTypeException' => array('model', 'data_type_exception', 'data'),
			'DataBaseModelException' => array('model', 'data_base_model_exception', 'data'),
			'DataDashboardBaseController' => array('library', 'dashboard_base_controller', 'data'),
			'DataTypePermissionKey' => array('model', 'permission/keys/data_type', 'data'),
			'DataTypePermissionAccess' => array('model', 'permission/access/categories/data_type', 'data'),
			'DataTypePermissionAccessListItem' => array('model', 'permission/access/list_items/data_type', 'data'),
			'DataTypePermissionAssignment' => array('model', 'permission/assignments/data_type', 'data'),
			'DataTypePermissionResponse' => array('model', 'permission/response/data_type', 'data')
		));
	}

	public function on_start() {
		$this->registerAutoloaders();
	}

	public function install() {
		$pkg = parent::install();

		$this->registerAutoloaders();

		$akc = AttributeKeyCategory::add(
			'data',
			AttributeKeyCategory::ASET_ALLOW_SINGLE, // TODO MULTIPLE
			$pkg
		);

		foreach (array(
			'address',
			'boolean',
			'date_time',
			'default',
			'image_file',
			'number',
			'rating',
			'select',
			'text',
			'textarea'
		) as $type) {
			if ($at = AttributeType::getByHandle($type)) {
				$akc->associateAttributeKeyType($at);
			}
		}

		foreach (array(
			'data',
			'data/management',
			'data/administration'
		) as $path) {
			SinglePage::add('/dashboard/' . $path, $pkg);
		}
		foreach (array(
			'data/administration/attributes',
		) as $path) {
			if (!$sp = SinglePage::add('/dashboard/' . $path, $pkg)) {
				$sp = Page::getByPath('/dashboard/' . $path);
			}
			$sp->setAttribute('exclude_nav', 1);
		}

		$pkc = PermissionKeyCategory::add('data_type', $pkg);
		foreach (array(
			'group',
			'user',
			'group_set',
			'group_combination',
			'page_owner'
		) as $handle) {
			if ($paet = PermissionAccessEntityType::getByHandle($handle)) {
				$pkc->associateAccessEntityType($paet);
			}
		}

		foreach (array(
			'edit_data_type' => array(
				'name' => t('Edit Data Type'),
				'description' => t('Edit Data Type')
			),
			'delete_data_type' => array(
				'name' => t('Delete Data Type'),
				'description' => t('Delete Data Type')
			),
			'create_data_type' => array(
				'name' => t('Create Data Type'),
				'description' => t('Create Data Type')
			),
			'edit_datas' => array(
				'name' => t('Edit Datas'),
				'description' => t('Edit Datas')
			),
			'delete_datas' => array(
				'name' => t('Delete Datas'),
				'description' => t('Delete Datas')
			),
			'create_datas' => array(
				'name' => t('Create Datas'),
				'description' => t('Create Datas')
			)
		) as $keyHandle => $data) {
			DataTypePermissionKey::add(
				'data_type',
				$keyHandle,
				$data['name'],
				$data['description'],
				1,
				0,
				$pkg
			);
		}

		BlockType::installBlockTypeFromPackage('data_display', $pkg);
	}

	public function uninstall() {
		parent::uninstall();
		$db = Loader::db();
		foreach (array(
			'Datas',
			'DataTypes',
			'DataAttributeKeys',
			'DataAttributeValues',
			'DataSearchIndexAttributes'
		) as $table) {
			$db->Execute('DROP TABLE IF EXISTS ' . $table );
		}
	}

}
