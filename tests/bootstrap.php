<?php
define('C5_EXECUTE', 1);
define('C5_ENVIRONMENT_ONLY', 1);
define('DIR_BASE', dirname(__FILE__) . '/core/concrete5/web');
define('REDIRECT_TO_BASE_URL', false);
require_once(dirname(__FILE__) . '/core/concrete5/web/concrete/dispatcher.php');
require(ADODB_DIR . '/adodb-lib.inc.php');
$dataPackage = new DataPackage;
$dataPackage->registerAutoloaders();
