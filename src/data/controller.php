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
			'DataDashboardBaseController' => array('library', 'dashboard_base_controller', 'data')
		));
	}

	public function on_start() {
		$this->registerAutoloaders();
	}

	public function install() {
		$pkg = parent::install();
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
