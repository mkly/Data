<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataTestStartingPointPackage extends StartingPointPackage {

	protected $pkgHandle = 'data_test';

	public function getPackageName() {
		return t('Data Test');
	}

	public function getPackageDescription() {
		return t('Data test sample content');
	}
}
