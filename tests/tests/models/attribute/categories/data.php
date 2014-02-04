<?php

class DataAttributeKeyTest extends PHPUnit_Framework_TestCase {

	public function testAutoload() {
		$this->assertInstanceOf('DataAttributeKey', new DataAttributeKey);
	}

	public function testGetShortHandle() {
		$stub = $this->getMock('DataAttributeKey', array(
			'getAttributeKeyHandle',
			'getDataType'
		));
		$stub->expects($this->any())
		     ->method('getAttributeKeyHandle')
		     ->will($this->returnValue('data_testtype_testing'));

		$dataType = new DataType;
		$dataType->dtHandle = 'testtype';

		$stub->expects($this->any())
		     ->method('getDataType')
		     ->will($this->returnValue($dataType));

		$this->assertEquals('testing', $stub->getShortHandle());
	}
}
