<?php

class DataTypeTest extends PHPUnit_Framework_TestCase {

	public function testAutoload() {
		$this->assertInstanceOf('DataType', new DataType);
	}
}
