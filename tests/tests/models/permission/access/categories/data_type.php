<?php

class PermissionAccessListItemsDataType extends PHPUnit_Framework_TestCase {

	public function testAutoload() {
		$this->assertInstanceOf('DataTypePermissionAccessListItem', new DataTypePermissionAccessListItem);
	}
}
