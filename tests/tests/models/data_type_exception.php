<?php

class DataTypeExceptionTest extends PHPUnit_Framework_TestCase {

	public function testAutoload() {
		$this->assertInstanceOf('DataTypeException', new DataTypeException);
	}

	public function testException() {
		$this->setExpectedException('DataTypeException');
		throw new DataTypeException;
	}
}
