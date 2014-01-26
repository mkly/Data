<?php

class DataAttributeKeyTest extends PHPUnit_Framework_TestCase {

	public function testAutoload() {
		$this->assertInstanceOf('DataAttributeKey', new DataAttributeKey);
	}
}
