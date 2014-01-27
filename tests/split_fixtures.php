#!/usr/bin/env php
<?php
$dir = dirname(__FILE__);
echo 'Adding fixtures to ' . $dir;
$xml = new SimpleXMLElement($dir . '/fixtures/database.xml', 0, true);
foreach ($xml->xpath('/mysqldump/database/table_data') as $table) {
	$filename = $dir . '/fixtures/' . $table->attributes()->name . '.xml';
	file_put_contents($filename, '<?xml version="1.0"?>
			<mysqldump xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
			<database name="concrete5_tests">
	' . $table->asXML() . '
		</database>
		</mysqldump>');
}
