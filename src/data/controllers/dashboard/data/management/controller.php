<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DashboardDataManagementController extends DataDashboardBaseController {

	public function view($dID = null) {
		if (!$dID) {
			$DataType = new DataType;
			$this->set('dataTypes', $DataType->Find('1=1'));
			$this->render('data_types');
		}

		$data = new Data;
		if (!$data->load('dID=?', array($dID))) {
			$this->flashError(t('Data Type Not Found'));
			$DataType = new DataType;
			$this->set('dataTypes', $DataType->Find('1=1'));
			$this->render('data_types');
		}

		if (!$data->getDataType()->permissions->canViewDatas()) {
			$this->flashError(t('Access Denied'));
			$DataType = new DataType;
			$this->set('dataTypes', $DataType->Find('1=1'));
			$this->render('data_types');
		}

		$this->navigation = array(
			t('Search') => '/dashboard/data/management/search/' . $dataType->dtID
		);
		$this->set('data', $data);
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

		if (!$dataType->permissions->canCreateData()) {
			$this->flashError('Access Denied');
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

		$this->navigation = array(
			t('Search') => '/dashboard/data/management/search/' . $dataType->dtID
		);
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

		if (!$dataType->permissions->canEditData()) {
			$this->flashError('Access Denied');
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

		$this->navigation = array(
			t('Search') => '/dashboard/data/management/search/' . $dataType->dtID
		);
		$this->render('edit');
	}
	
	/**
	 * @param $dtID int
	 * @param $dID int
	 */
	public function duplicate($dtID, $dID) {
		$dataType = new DataType;
		if (!$dataType->Load('dtID=?', array($dtID))) {
			$this->flashError('Data Type Not Found');
			$this->redirect($this->path());
		}

		if (!$dataType->permissions->canEditData()) {
			$this->flashError('Access Denied');
			$this->redirect($this->path());
		}

		$data = new Data;
		if (!$data->Load('dID=?', array($dID))) {
			$this->flashError('Data Not Found');
			$this->redirect($this->path(), $dataType->dtID);
		}
		
		$db = Loader::db();
		$data2 = $data->Duplicate();
		
		$this->flashSuccess(t('Data Duplicated'));
		$this->redirect($this->path('search'), $dataType->dtID);
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

		if (!$dataType->permissions->canDeleteData()) {
			$this->flashError('Access Denied');
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
				$this->redirect($this->path('search'), $dataType->dtID);
			}
			$this->flashError(t('Unknown Error'));
		}

		$this->set('data', $data);
		$this->set('dataType', $dataType);


		$this->navigation = array(
			t('Search') => '/dashboard/data/management/search/' . $dataType->dtID,
			t('Edit') => '/dashboard/data/management/edit/' . $dataType->dtID . '/' . $data->dID
		);
		$this->render('delete');
	}

	/**
	 * @param $dtID int
	 */
	public function search($dtID) {
		$dataType = new DataType;
		if (!$dataType->Load('dtID=?', array($dtID))) {
			$this->flashError('Data Type Not Found');
			$this->redirect($this->path());
		}
		$DataList = new DataList($dataType);
		if ($name = $this->get('name')) {
			$DataList->filterByAttribute('name', "%$name%", 'LIKE');
		}

		$this->set('DataList', $DataList);
		$this->set('dataType', $dataType);
		$this->set('datas', $DataList->getPage());

		$this->render('search');
	}
		
	/**
	 * @param int $dtID
	 */
	public function download_xml($dtID) {
		// Check data presence
		$dataType = new DataType;
		if (!$dataType->Load('dtID=?', array($dtID))) {
			$this->flashError(t('Data Type Not Found'));
			$this->redirect($this->path());
		}
		$dataList = new DataList( $dataType );
		if ($dataList->getTotal() === 0) {
			$this->flashError(t('Dataset Is Empty'));
			$this->redirect($this->path());
		}
		
		// Build xml
		$xml = new SimpleXMLElement('<xml/>');
		$xml->addAttribute('version', '1.0');
		$xml->addAttribute('encoding', 'utf-8');
		$list = $xml->addChild( $dataType->dtHandle );
		$datas = $dataList->get();
		foreach($datas as $d){
			$ak = new DataAttributeKey;
			$avl = $ak->getAttributes($d->dID, 'getValue');
			$b = $list->addChild( $dataType->dtHandle );
			$b->addChild('dID', $d->dID);
			foreach($avl as $key => $value) {
				$cnode = $b->addChild( $key );
				$node = dom_import_simplexml( $cnode );
				$no = $node->ownerDocument;
				$node->appendChild($no->createCDataSection( $value ));
			}
		}
		
		// Set header and output
		$filename = $dataType->dtHandle.'.xml';
		$now = gmdate("D, d M Y H:i:s");
		header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
		header("Last-Modified: {$now} GMT");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename={$filename}");
		header("Content-Transfer-Encoding: utf-8");
		header('Content-Type: text/xml');
		echo $xml->asXML();
		exit;
	}

	/**
	 * @param int $dtID
	 */
	public function download_csv($dtID) {
		// Check data presence
		$dataType = new DataType;
		if (!$dataType->Load('dtID=?', array($dtID))) {
			$this->flashError(t('Data Type Not Found'));
			$this->redirect($this->path());
		}
		$dataList = new DataList( $dataType );
		if ($dataList->getTotal() === 0) {
			$this->flashError(t('Dataset Is Empty'));
			$this->redirect($this->path());
		}
		
		// Build csv
		$fh = fopen('php://output', 'w');
		$datas = $dataList->get();
		foreach($datas as $d){
			$ak = new DataAttributeKey;
			$avl = $ak->getAttributes($d->dID, 'getValue');
			foreach($avl as $key => $value) {
				$dataset[$key] = $value;
			}
			fputcsv($fh, $dataset);
		}
		fclose($fh);
		
		// Set header and output
		$filename = $dataType->dtHandle.'.csv';
		$now = gmdate("D, d M Y H:i:s");
		header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
		header("Last-Modified: {$now} GMT");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename={$filename}");
		header("Content-Transfer-Encoding: utf-8");
		header('Content-Type: text/csv');
		exit;
	}

}
