<?php

class DataListExceptionTest extends PHPUnit_Framework_TestCase {

	public function testAutoload() {
		$this->assertInstanceOf('DataListException', new DataListException);
	}

	public function testException() {
		$this->setExpectedException('DataListException');
		throw new DataListException;
	}
}
