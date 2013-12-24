<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataDisplayBlockController extends BlockController {

	protected $btTable = "btDataDisplay";
	protected $btInterfaceWidth = "400";
	protected $btInterfaceHeight = "400";
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = true;
	protected $btCacheBlockOutputForRegisteredUsers = true;
	protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;

	public function getBlockTypeName() {
		return t('Data Display');
	}

	public function getBlockTypeDescription() {
		return t('Display Data');
	}

	public function add() {
		$this->edit();
	}

	public function edit() {
	}
}
