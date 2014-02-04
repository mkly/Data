<?php

class PermisssionAccessCategoriesDataTypeTest extends PHPUnit_Framework_TestCase {

	public function testAutoload() {
		$this->assertInstanceOf('DataTypePermissionAccess', new DataTypePermissionAccess);
	}
}
