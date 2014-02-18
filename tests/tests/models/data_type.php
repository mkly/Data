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

	public function testImportDataTypeWithAttributeKeys() {
		$xml = new SimpleXMLElement('
			<datatype dtName="Test" dtHandle="test">
				<attributekeys>
					<attributekey handle="data_test_one" name="Data Test One" package="data" type="text" searchable="1" indexed="0" category="data" dtHandle="test"/>
					<attributekey handle="data_test_two" name="Data Test Twe" package="data" type="text" searchable="0" indexed="1" category="data" dtHandle="test"/>
				</attributekeys>
			</datatype>
		');
		$DataType = new DataType;
		$dataType = $DataType->import($xml);
		$this->assertEquals(2, count($dataType->attributes));
		$this->assertInstanceOf('DataAttributeKey', current($dataType->attributes));
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

	public function testExportDataWithAttributes() {
		$xml = new SimpleXMLElement('<concrete5-cif/>');
		$xml->addAttribute('version', '1.0');
		$dataType = new DataType;
		$dataType->dtName = 'Test Name';
		$dataType->dtHandle = 'test_handle';
		$dataType->Insert();
		DataAttributeKey::add(
			'text',
			array('akHandle' => 'number_one', 'akName' => 'One', 'dtID' => $dataType->dtID),
			$dataType
		);
		$dak = DataAttributeKey::add(
			'text',
			array('akHandle' => 'number_two', 'akName' => 'Two', 'dtID' => $dataType->dtID),
			$dataType
		);
		$exportedXML = $dataType->export($xml);
		$this->assertInstanceOf('SimpleXMLElement', $exportedXML);
		$this->assertCount(2, $xml->xpath('//datatype/attributekeys/attributekey'));
		$this->assertEquals('Two', $xml->xpath('//datatype/attributekeys/attributekey')[1]->attributes()->name);
	}

}
