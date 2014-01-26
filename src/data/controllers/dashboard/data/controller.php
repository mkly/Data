<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DashboardDataController extends DataDashboardBaseController {

	public function on_start() {
		parent::on_start();
		$this->redirect($this->path('management'));
	}
}
