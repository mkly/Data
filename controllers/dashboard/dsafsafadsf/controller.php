<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DashboardDataAdminController extends DataDashboardBaseController {

	public function view() {
		$this->redirect($this->path('search'));
	}

	public function search() {
		$DataType = new DataType;
		$this->set('dataTypes', $DataType->Find('1=1'));

		$this->render('search');
	}

	public function create() {
		if (!$this->isPost()) {
			$this->render('create');
		}

		$dataType = new DataType;
		$dataType->name = $this->post('name');
		$dataType->handle = $this->post('handle');

		$errors = $dataType->validate();
		if ($errors->has()) {
			$this->set('errors', $errors);
			return;
		}

		$dataType->Insert();

		$this->flashSuccess(t('Data Type Created'));
		$this->redirect($this->path('attributes'), $dataType->dtID);
	}

	public function edit($dtID) {
		$dataType = new DataType;
		if (!$dataType->Load('dtID=?', array($dtID))) {
			$this->flashError(t('Data Type Not Found'));
			$this->redirect('search');
		}
		if ($this->isPost()) {
			$dataType->name = $this->post('name');
			$dataType->handle = $this->post('handle');

			$errors = $dataType->validate();
			if ($errors->has()) {
				$this->set('errors', $errors);
				$this->render('edit');
			}

			$dataType->Update();

			$this->flashSuccess(t('Data Type Updated'));
			$this->redirect('attributes', $dataType->dtID);
		}

		$types = array();
		foreach (AttributeType::getList('data') as $at) {
			$types[$at->getAttributeTypeID()] = $at->getAttributeTypeDisplayName();
		}
		$this->set('types', $types);
		$this->set('dataType', $dataType);

		$this->render('edit');
	}

	public function delete($dtID) {
		$dataType = new DataType;
		if (!$dataType->Load('dtID=?', array($dtID))) {
			$this->flashError(t('Data Type Not Found'));
			$this->redirect('search');
		}
		if (!$this->isPost()) {
			$this->flashError(t('Data Type Not Found'));
			$this->redirect('search');
		}
		$dataType->Delete();

		$this->flashSuccess(t('Data Type Deleted'));
		$this->redirect('search');
	}
}
