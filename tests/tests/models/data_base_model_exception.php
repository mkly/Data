<?php

class DataBaseModelExceptionTest extends PHPUnit_Framework_TestCase {

	public function testAutoload() {
		$this->assertInstanceOf('DataBaseModelException', new DataBaseException);
	}

	public function testException() {
		$this->setExpectedException('DataBaseModelException');
		throw new DataBaseModelException;
	}
}
