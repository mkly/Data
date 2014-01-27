<?php

class DataListTest extends DataDatabaseTestCase {

	public function testAutoload() {
		$this->assertInstanceOf('DataList', new DataList(new DataType));
	}
}
