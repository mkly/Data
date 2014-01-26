<?php

class DataTest extends PHPUnit_Framework_TestCase {

	public function testAutoload() {
		$this->assertInstanceOf('Data', new Data);
	}
}
