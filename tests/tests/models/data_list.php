<?php

class DataListTest extends PHPUnit_Framework_TestCase {

	public function testAutoload() {
		$this->assertInstanceOf('DataList', new DataList(new DataType));
	}
}
