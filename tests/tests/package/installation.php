<?php

class InstallationTest extends DataDatabaseTestCase {

	public function getDataSet() {
		$dataSet = parent::getDataSet();
		foreach(array(
			'Pages.xml',
			'PagePaths.xml',
			'Collections.xml',
			'CollectionVersions.xml',
			'CollectionAttributeValues.xml',
			'CollectionSearchIndexAttributes.xml',
			'atBoolean.xml') as $fixture) {
			$dataSet->addDataSet(
				$this->createMySQLXMLDataSet($this->fixturesDir . $fixture)
			);
		}
		return $dataSet;
	}

	public function testIsInstalled() {
		$this->assertInstanceOf('DataPackage', Package::getByHandle('data'));
	}

	public function testPagesInstalled() {
		foreach(array(
			'/dashboard/data',
			'/dashboard/data/management',
			'/dashboard/data/administration',
			'/dashboard/data/administration/attributes'
		) as $path) {
			$page = Page::getByPath($path);
			$this->assertEquals('', $page->isError());
		}
	}

	public function testExcludeNav() {
		foreach(array(
			'/dashboard/data/administration/attributes'
		) as $path) {
			$this->assertEquals(1, Page::getByPath($path)->getAttribute('exclude_nav'));
		}
	}

	public function testNotExcludeNav() {
		foreach(array(
			'/dashboard/data',
			'/dashboard/data/management',
			'/dashboard/data/administration'
		) as $path) {
			$page = Page::getByPath($path);
			$this->assertFalse($page->getAttribute('exclude_nav'));
		}
	}
}
