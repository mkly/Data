<?php

class DataTypeTest extends DataDatabaseTestCase {

	public function testAutoload() {
		$this->assertInstanceOf('DataType', new DataType);
	}

	public function testImportDataType() {
		$xml = new SimpleXMLElement('
			<datatype dtName="Test" dtHandle="test"/>
		');
		$DataType = new DataType;
		$dataType = $DataType->import($xml);
		$this->assertNotNull($dataType->dtID);
		$this->assertEquals('Test', $dataType->dtName);
		$this->assertEquals('test', $dataType->dtHandle);
	}

	public function testImportDataTypeWithDatas() {
		$xml = new SimpleXMLElement('
			<datatype dtName="Testing" dtHandle="testing">
				<data/>
				<data/>
				<data/>
			</datatype>
		');
		$DataType = new DataType;
		$dataType = $DataType->import($xml);
		$this->assertEquals(3, count($dataType->datas));
	}

	public function testImportDataTypeIncorrectElementName() {
		$xml = new SimpleXMLElement('
			<data dtName="Test" dtHandle="test"/>
		');
		$DataType = new DataType;
		$this->setExpectedException('DataTypeException');
		$DataType->import($xml);
	}

	public function testExportDataType() {
		$xml = new SimpleXMLElement('<concrete5-cif/>');
		$xml->addAttribute('version', '1.0');
		$dataType = new DataType;
		$dataType->dtName = 'Test Name';
		$dataType->dtHandle = 'test_handle';
		$exportedXML = $dataType->export($xml);
		$this->assertInstanceOf('SimpleXMLElement', $exportedXML);
		$this->assertEquals('Test Name', (string) $xml->children()->datatype->attributes()->dtName);
	}

}
