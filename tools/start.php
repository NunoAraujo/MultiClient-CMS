<?php
require_once($_SERVER['DOCUMENT_ROOT']."/tools/mysql.php");

$dbHost = "localhost";
$dbName =  "multiclientcms";
$dbUser = "root";
$dbPass = "";
$ftpRoot = "";
$ftpHost = "";
$ftpUser = "";
$ftpPass = "";
$host = "http://multiclientcms.dev";

$connected = mySqlConnect($dbHost, $dbName, $dbUser, $dbPass);
if(!$connected){
    die( "Sorry! There seems to be a problem connecting to our database.");
}

$client = 1;
$result = MySqlSelectSingle("clients", "*", "id=$client");
if ($result) {
	$dbHost = $result['dbHost'];
	$dbUser = $result['dbUser'];
	$dbPass =  $result['dbPass'];
	$dbName = $result['dbName'];
	$ftpRoot = $result['ftpRoot'];
	$ftpHost = $result['ftpHost'];
	$ftpUser = $result['ftpUser'];
	$ftpPass =  $result['ftpPass'];
	$host = $result['webHost'];
}

$GLOBALS['DBHOST'] = $dbHost;
$GLOBALS['DBUSER'] = $dbUser;
$GLOBALS['DBPASS'] = $dbPass;
$GLOBALS['DBNAME'] = $dbName;
$GLOBALS['FTPROOT'] = $ftpRoot;
$GLOBALS['FTPHOST'] = $ftpHost;
$GLOBALS['FTPUSER'] = $ftpUser;
$GLOBALS['FTPPASS'] = $ftpPass;

define('SODBHOST',"localhost");
define('SODBUSER',"root");
define('SODBPASS',"");
define('SODBNAME',"multiclientcms");
// make a connection to mysql here
$connected = mySqlConnect($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
if(!$connected){
    die("Sorry! There seems to be a problem connecting to our database.");
}

// define site path
define('DIR','multiclientcms.dev');

define('HOST', $host);
define('SOHOST','http://multiclientcms.dev');

require_once($_SERVER['DOCUMENT_ROOT']."/tools/misc.php");
?>