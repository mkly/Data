<?php
require_once "PHPUnit/Extensions/Database/Autoload.php";

abstract class DataDatabaseTestCase extends PHPUnit_Extensions_Database_TestCase {

	public function getConnection() {
		$db = Loader::db();
		$pdo = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo);
	}

	public function getDataSet() {
		$dir = CONCRETE_5_DATA_TEST_DIR . '/fixtures/';
		return new PHPUnit_Extensions_Database_DataSet_CompositeDataSet(array(
			$this->createMySQLXMLDataSet($dir . 'Config.xml'),
			$this->createMySQLXMLDataSet($dir . 'Datas.xml'),
			$this->createMySQLXMLDataSet($dir . 'DataTypes.xml'),
			$this->createMySQLXMLDataSet($dir . 'DataAttributeKeys.xml'),
			$this->createMySQLXMLDataSet($dir . 'AttributeKeys.xml')
		));
	}
}
