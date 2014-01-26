<?php

class DataAttributeValueTest extends PHPUnit_Framework_TestCase {

	public function testAutoload() {
		$this->assertInstanceOf('DataAttributeValue', new DataAttributeValue);
	}
}
