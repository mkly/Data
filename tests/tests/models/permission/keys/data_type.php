<?php

class PermissionKeysDataTypeTest extends DataDatabaseTestCase {

	public function testAutoload() {
		$this->assertInstanceOf('DataTypePermissionKey', new DataTypePermissionKey);
	}

	public function testAddPermissionKey() {
		$pkgStub = $this->getMock('Package');
		$pkgStub->expects($this->any())
		        ->method('getPackageID')
		        ->will($this->returnValue(33));

		PermissionKeyCategory::add(
			'data_type',
			$pkgStub
		);

		DataTypePermissionKey::add(
			'data_type',
			'testingkey',
			'Name',
			'Description',
			1,
			0,
			$pkgStub
		);

		$dpk = DataTypePermissionKey::getByHandle('testingkey');
		$this->assertInstanceOf('DataTypePermissionKey', $dpk);
		$this->assertEquals('Name', $dpk->getPermissionKeyName());
	}
}
