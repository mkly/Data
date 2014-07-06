<?php

class DataAttributeKeyTest extends DataDatabaseTestCase {

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

	public function testAddDataAttributeKey() {
		$res = DataAttributeKey::add(
			'text',
			array('akHandle' => 'one', 'akName' => 'One'),
			$dataType
		);
		$dak = DataAttributeKey::getByID($res->getAttributeKeyID());
		$this->assertInstanceOf('DataAttributeKey', $dak);
		$this->assertEquals('One', $dak->getAttributeKeyName());
	}

}
