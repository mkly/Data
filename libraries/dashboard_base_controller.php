<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataDashboardBaseController extends DashboardBaseController {

	protected $message;
	protected $success;

	public function on_start() {
		parent::on_start();
		$this->set('dashboard', Loader::helper('concrete/dashboard'));
		$this->set('token', Loader::helper('validation/token'));
		
	}

	public function on_before_render() {
		if (isset($_SESSION['flash_error'])) {
			foreach ($_SESSION['flash_error']->getList() as $error) {
				$this->error->add($error);
			}
		}

		if (isset($_SESSION['flash_message'])) {
			$this->message = $_SESSION['flash_message'];
		}

		if (isset($_SESSION['flash_success'])) {
			$this->success = $_SESSION['flash_success'];
		}

		unset($_SESSION['flash_success']);
		unset($_SESSION['flash_message']);
		unset($_SESSION['flash_error']);

		$this->set('error', $this->error);
		$this->set('message', $this->message);
		$this->set('success', $this->success);
	}

	protected function flashSuccess($text) {
		$_SESSION['flash_success'] = $text;
	}

	protected function flashMessage($text) {
		$_SESSION['flash_message'] = $text;
	}

	protected function flashError($text) {
		if (!isset($_SESSION['flash_error'])) {
		//	$_SESSION['flash_error'] = Loader::helper('validation/error');
		}
		$_SESSION['flash_error']->add($text);
	}

	public function render($view) {
		return parent::render($this->path($view));
	}

	protected function path($view = '') {
		if ($view === '') {
			return $this->getCollectionObject()->getCollectionPath();
		}
		return $this->getCollectionObject()->getCollectionPath() . '/' . $view;
	}

}
