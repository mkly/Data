<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DashboardDataAdministrationController extends DataDashboardBaseController {

	public function on_start() {
		parent::on_start();
		$this->set('navigation', array(
			Page::getByPath('/dashboard/data/management')
		));
	}

	/**
	 * @todo view permissions
	 * @param int $dtID
	 */
	public function view($dtID = null) {
		if (!$dtID) {
			$this->redirect($this->path('search'));
		}
		$dataType = new DataType;
		if (!$dataType->Load('dtID=?', array($dtID))) {
			$this->redirect($this->path('search'));
		}
		$this->set('dataType', $dataType);
	}

	/**
	 * @todo view permissions
	 */
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
		$dataType->dtName = $this->post('dtName');
		$dataType->dtHandle = $this->post('dtHandle');

		$errors = $dataType->validate();
		if ($errors->has()) {
			$this->set('errors', $errors);
			return;
		}

		$dataType->Insert();

		if ($type = AttributeType::getByHandle('text')) {
			$DataAttributeKey = new DataAttributeKey;
			$DataAttributeKey->add(
				$type,
				array(
					'dtID' => $dataType->dtID,
					'akHandle' => 'name',
					'akName' => 'Name',
					'akIsSearchable' => 1
				),
				$dataType
			);
		}

		$this->flashSuccess(t('Data Type Created'));
		$this->redirect($this->path('attributes'), $dataType->dtID);
	}

	/**
	 * @param $dtID int DataType id
	 */
	public function edit($dtID) {
		$dataType = new DataType;
		if (!$dataType->Load('dtID=?', array($dtID))) {
			$this->flashError(t('Data Type Not Found'));
			$this->redirect($this->path());
		}

		if (!$dataType->permissions->canEditDataType()) {
			$this->flashError(t('Access Denied'));
			$this->redirect($this->path());
		}

		if ($this->isPost()) {
			$dataType->dtName = $this->post('dtName');
			$dataType->dtHandle = $this->post('dtHandle');

			$errors = $dataType->validate();
			if ($errors->has()) {
				$this->set('errors', $errors);
				$this->render('edit');
			}

			$dataType->Update();

			$this->flashSuccess(t('Data Type Updated'));
			$this->redirect($this->path());
		}

		$types = array();
		foreach (AttributeType::getList('data') as $at) {
			$types[$at->getAttributeTypeID()] = $at->getAttributeTypeName();
		}
		$this->set('types', $types);
		$this->set('dataType', $dataType);

		$this->render('edit');
	}

	/**
	 * @param $dtID int DataType id
	 */
	public function delete($dtID) {
		$dataType = new DataType;
		if (!$dataType->Load('dtID=?', array($dtID))) {
			$this->flashError(t('Data Type Not Found'));
			$this->redirect($this->path());
		}

		if (!$dataType->permissions->canDeleteDataType()) {
			$this->flashError(t('Access Denied'));
			$this->redirect($this->path());
		}

		if ($this->isPost()) {
			$dataType->Delete();
			$this->flashSuccess(t('Data Type Deleted'));
			$this->redirect($this->path());
		}

		$this->set('dataType', $dataType);
		$this->render('delete');
	}

	/**
	 * @todo export permissions
	 * @param int $dtID
	 */
	public function export($dtID) {
		$dataType = new DataType;
		if (!$dataType->Load('dtID=?', array($dtID))) {
			$this->flashError(t('Data Type Not Found'));
			$this->redirect($this->path());
		}

		$xml = new SimpleXMLElement('<concrete5-cif/>');
		$xml->addAttribute('version', '1.0');
		$dataType->export($xml);
		header('Content-Type: text/xml');
		echo $xml->asXML();
		exit;
	}

	public function import() {
		if ($this->isPost()) {
			if (!isset($_FILES['import'])) {
				$this->flashError(t('Invalid File'));
				$this->redirect($this->path('import'));
			}
			$file = $_FILES['import']['tmp_name'];
			$filename = $_FILES['import']['name'];
			if (!is_uploaded_file($file)) {
				$this->flashError(t('Invalid File'));
				$this->redirect($this->path('import'));
			}
			if (!Loader::helper('validation/file')->extension($filename, array('xml'))) {
				$this->flashError(t('File must be .xml'));
				$this->redirect($this->path('import'));
			}
			$xml = new SimpleXMLElement($file, 0, true);
			$DataType = new DataType;
			foreach ($xml->datatype as $datatype) {
				$DataType->import($datatype);
			}
			$this->flashSuccess(t('Data Type Imported'));
			$this->redirect($this->path());
		}
		$this->render('import');
	}

}
