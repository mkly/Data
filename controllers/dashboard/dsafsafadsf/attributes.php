<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DashboardDataAdminAttributesController extends DataDashboardBaseController {

	public function view($dtID) {
		$dataType = new DataType;
		$dataType->Load('dtID=?', array($dtID));
		if ($this->isPost()) {
			$this->redirect($this->path('create'), $dataType->dtID, $this->post('atID'));
		}

		$this->set('dataType', $dataType);
		$types = array();
		foreach (AttributeType::getList('data') as $at) {
			$types[$at->getAttributeTypeID()] = $at->getAttributeTypeDisplayName();
		}
		$this->set('types', $types);
		$this->set('attributes', DataAttributeKey::getListByDataTypeID($dtID));

	}

	/**
	 * @param $dtID int
	 * @param $atID int
	 */
	public function create($dtID, $atID) {
		$dataType = new DataType;
		if (!$dataType->Load('dtID=?', $dtID)) {
			$this->flashError(t('Data Type Not Found'));
			$this->redirect($this->path());
		}
		$category = AttributeKeyCategory::getByHandle('data');
		if (!$type = AttributeType::getByID($atID)) {
			$this->flashError(t('Attribute Type Not Found'));
			$this->redirect($this->path());
		}

		if ($this->isPost()) {
			$DataAttributeKey = new DataAttributeKey;
			if($ak = $DataAttributeKey->add(
				$type,
				array('dtID' => $dataType->dtID) + $this->post()
			)) {
				$this->flashSuccess(t('Attribute Added'));
				$this->redirect($this->path(), $dataType->dtID);
			}
			$this->error = t('Unknown Error');
		}
		$sets = array('0' => t('** None'));
		foreach ($category->getAttributeSets() as $set) {
			$sets[$set->getAttributeSetID()] = $set->getAttributeSetDisplayName();
		}
		$this->set('sets', $sets);
		$this->set('type', $type);
		$this->set('category', $category);

		$this->render('create');
	}

	/**
	 * @param $dtID int
	 * @param $akID int
	 */
	public function edit($dtID, $akID) {
		$dataType = new DataType;
		if (!$dataType->Load('dtID=?', $dtID)) {
			$this->flashError(t('Data Type Not Found'));
			$this->redirect($this->path());
		}

		$category = AttributeKeyCategory::getByHandle('data');
		if (!$ak = DataAttributeKey::getByID($akID)) {
			$this->flashError(t('Attribute Key Not Found'));
			$this->redirect($this->path());
		}

		if ($this->isPost()) {
			if ($ak->update(array('dtID' => $dataType->dtID) + $this->post())) {
				$this->flashSuccess(t('Attribute Updated'));
				$this->redirect($this->path(), $dataType->dtID);
			}
			$this->error(t('Unknown Error'));
		}

		$sets = array('0' => t('** None'));
		foreach ($category->getAttributeSets() as $set) {
			$sets[$set->getAttributeSetID()] = $set->getAttributeSetDisplayName();
		}

		$this->set('sets', $sets);
		$this->set('type', $ak->getAttributeType());
		$this->set('category', $category);
		$ak = DataAttributeKey::getByID($akID);
		$this->set('key', $ak);

		$this->set('akHandle', $this->post('akHandle', $ak->getAttributeKeyHandle()));
		$this->set('akName', $this->post('akName', $ak->getAttributeKeyName()));
		$this->set('asID', $this->post('asID', 0));
		$this->set('akIsSearchableIndexed', $this->post('akIsSearchableIndexed', $ak->isAttributeKeyContentIndexed()));
		$this->set('akIsSearchable', $this->post('akIsSearchable', $ak->isAttributeKeySearchable()));

		$this->render('edit');
	}
}
