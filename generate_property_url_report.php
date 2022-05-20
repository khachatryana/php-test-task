<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once('src/Validation/ValidationPropertyArgs.php');
require_once('config/Constant.php');
require_once('src/db/Connection.php');
require_once('src/Service/SalesReport.php');
require_once('src/Service/RentalReport.php');
require_once('src/db/Mysql.php');
require_once('src/Helpers/Log.php');

$constants = new Constant();

$db = $constants->getDb();
$hosts = $constants->getHost();
$user = $constants->getUser();
$password = $constants->getPass();

$connection = new Connection($user, $hosts, $password, $db);
$mysqli = '';
try {
    $mysqli = $connection->connect();
} catch (e $ex) {

}
$mysql = new Mysql($mysqli);
$validation = new ValidationPropertyArgs();
$log = new Log();
switch ($argv[1]) {
    case "sales":
        $salesReport = new SalesReport($validation, $mysqli, $argv, $mysql, $log);
        $salesReport->run();
        break;
    case "rental":
        $rentalReport = new RentalReport($validation, $mysqli, $argv, $mysql, $log);
        $rentalReport->run();
        break;
    default:
        $fileDir = 'logs/' . gmdate('Y-m-d:h:i:s \G\M\T', time()) . '.txt';
        $log->setDir($fileDir);
        $log->setLog('For arguments please use rental or sales types');
        $log->createLog();
        trigger_error('For arguments please use rental or sales types', E_USER_WARNING);
}

