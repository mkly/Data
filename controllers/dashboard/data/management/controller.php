<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DashboardDataManagementController extends DataDashboardBaseController {

	public function view() {
		$DataType = new DataType;
		$this->set('dataTypes', $DataType->Find('1=1'));
	}

	/**
	 * @param dtID int DataType id
	 */
	public function create($dtID) {
		$dataType = new DataType;
		if (!$dataType->Load('dtID=?', array($dtID))) {
			$this->flashError('Data Type Not Found');
			$this->redirect($this->path());
		}

		$this->set('dataType', $dataType);
		$this->set('attributes', DataAttributeKey::getListByDataTypeID($dataType->dtID));
		$this->set('ah', Loader::helper('form/attribute'));
		
		if ($this->isPost()) {
			$data = new Data;
			$data->dtID = $dtID;
			$data->Insert();
			foreach ($this->post('akID') as $akID => $values) {
				$dak = DataAttributeKey::getByID($akID);
				$dak->saveAttributeForm($data);
			}
			$this->flashSuccess(t('Data Created'));
			$this->redirect($this->path('search'), $dataType->dtID);
		}

		$this->render('create');
	}

	/**
	 * @param $dtID int
	 * @param $dID int
	 */
	public function edit($dtID, $dID) {
		$dataType = new DataType;
		if (!$dataType->Load('dtID=?', array($dtID))) {
			$this->flashError('Data Type Not Found');
			$this->redirect($this->path());
		}
		$data = new Data;
		if (!$data->Load('dID=?', array($dID))) {
			$this->flashError('Data Not Found');
			$this->redirect($this->path(), $dataType->dtID);
		}

		$this->set('data', $data);
		$this->set('dataType', $dataType);

		$this->set('attributes', DataAttributeKey::getListByDataTypeID($dataType->dtID));

		$ah = Loader::helper('form/attribute');
		$ah->setAttributeObject($data);
		$this->set('ah', $ah);

		if ($this->isPost()) {
			foreach ($this->post('akID') as $akID => $values) {
				$dak = DataAttributeKey::getByID($akID);
				$dak->saveAttributeForm($data);
			}
			$this->flashSuccess(t('Data Updated'));
			$this->redirect($this->path('search'), $dataType->dtID);
		}

		$this->render('edit');
	}

	/**
	 * @param $dtID int
	 * @param $dID int
	 */
	public function delete($dtID, $dID) {
		$dataType = new DataType;
		if (!$dataType->Load('dtID=?', array($dtID))) {
			$this->flashError('Data Type Not Found');
			$this->redirect($this->path());
		}
		$data = new Data;
		if (!$data->Load('dID=?', array($dID))) {
			$this->flashError('Data Not Found');
			$this->redirect($this->path(), $dataType->dtID);
		}

		if ($this->isPost()) {
			if ($data->Delete()) {
				$this->flashSuccess(t('Data Deleted'));
				$this->redirect($this->path());
			}
			$this->flashError(t('Unknown Error'));
		}

		$this->set('data', $data);
		$this->set('dataType', $dataType);

		$this->render('delete');
	}

	/**
	 * @param $dtID int
	 */
	public function search($dtID) {
		$dataType = new DataType;
		$dataType->Load('dtID=?', array($dtID));
		$DataList = new DataList;
		$DataList->filterByDataTypeID($dtID);
		$this->set('dataType', $dataType);
		$this->set('datas', $DataList->get(0));

		$this->render('search');
	}

}
