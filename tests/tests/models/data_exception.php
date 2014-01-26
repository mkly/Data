<?php

class DataExceptionTest extends PHPUnit_Framework_TestCase {

	public function testAutoload() {
		$this->assertInstanceOf('Exception', new DataException);
	}

	public function testException() {
		$this->setExpectedException('DataException');
		throw new DataException;
	}
}
