<?php

class DataListTest extends DataDatabaseTestCase {

	public function testAutoload() {
		$this->assertInstanceOf('DataList', new DataList(new DataType));
	}

	public function testGetByDataTypeID() {
		$dataType = new DataType;
		$dataType->dtHandle = 'testing';
		$dataType->dtName = 'Testing';
		$dataType->Insert();

		$dataList = DataList::getByDataTypeID($dataType->dtID);
		$this->assertInstanceOf('DataList', $dataList);
		$this->assertInstanceOf('DataType', $dataList->getDataType());
	}

	public function testGetByDataTypeHandle() {
		$dataType = new DataType;
		$dataType->dtHandle = 'testing';
		$dataType->dtName = 'Testing';
		$dataType->Insert();

		$dataList = DataList::getByDataTypeHandle($dataType->dtHandle);
		$this->assertInstanceOf('DataList', $dataList);
		$this->assertInstanceOf('DataType', $dataList->getDataType());
	}
}
