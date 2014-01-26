<?php

class DataTypeTest extends PHPUnit_Framework_TestCase {

	public function testAutoload() {
		$this->assertInstanceOf('DataType', new DataType);
	}

	public function testImportDataType() {
		$xml = new SimpleXMLElement('
			<DataType dtName="Test" dtHandle="test"/>
		');
		$DataType = new DataType;
		$dataType = $DataType->import($xml);
		$this->assertNotEmpty('dtID', $dataType);
		$this->assertEquals('Test', $dataType->dtName);
		$this->assertEquals('test', $dataType->dtHandle);
	}

	public function testImportDataTypeIncorrectElementName() {
		$xml = new SimpleXMLElement('
			<Data dtName="Test" dtHandle="test"/>
		');
		$DataType = new DataType;
		$dataType = new $DataType;
		$this->setExpectedException('DataTypeException');
		$dataType->import($xml);
	}
}
