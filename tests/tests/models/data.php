<?php

class DataTest extends DataDatabaseTestCase {

	public function testAutoload() {
		$this->assertInstanceOf('Data', new Data);
	}

	public function testImportData() {
		$dataType = new DataType;
		$dataType->dtHandle = 'testing';
		$dataType->dtName = 'Testing';
		$dataType->Insert();
		$xml = new SimpleXMLElement('
			<DataType dtHandle="testing"><Data/></DataType>
		');
		$Data = new Data;
		$data = $Data->import($xml->children()->Data);
		$this->assertNotNull($data->dID);
	}

	public function testImportDataIncorrectElementName() {
		$xml = new SimpleXMLElement('
			<DataType/>
		');
		$Data = new Data;
		$this->setExpectedException('DataException');
		$Data->import($xml);
	}

	public function testImportDataNoDataTypeHandle() {
		$xml = new SimpleXMLElement('
			<DataType><Data/></DataType>
		');
		$Data = new Data;
		$this->setExpectedException('DataException');
		$Data->import($xml->children()->Data);
	}

	public function testImportDataTypeNotFound() {
		$xml = new SimpleXMLElement('
			<DataType dtHandle="randomstring"><Data/></DataType>
		');
		$Data = new Data;
		$this->setExpectedException('DataException');
		$Data->import($xml->children()->Data);
	}
}
