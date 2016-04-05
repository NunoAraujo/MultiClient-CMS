<?php
// ob_start();
// session_start();

require_once($_SERVER['DOCUMENT_ROOT']."/tools/mysql.php");
// require_once($_SERVER['DOCUMENT_ROOT']."/tools/tools.php");
// require_once($_SERVER['DOCUMENT_ROOT']."/classes/scheme.php");

$dbHost = "localhost";
$dbName =  "multiclientcms";
$dbUser = "root";
$dbPass = "";
$ftpRoot = "";
$ftpHost = "";
$ftpUser = "";
$ftpPass = "";
$host = "http://multiclientcms.dev";
// if (isset($_SESSION['authorized']) && $_SESSION['authorized'] && isset($_SESSION['currClient'])) {
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
// }
define('DBHOST',$dbHost);
define('DBUSER',$dbUser);
define('DBPASS',$dbPass);
define('DBNAME',$dbName);
define('FTPROOT',$ftpRoot);
define('FTPHOST',$ftpHost);
define('FTPUSER',$ftpUser);
define('FTPPASS',$ftpPass);
define('SODBHOST',"localhost");
define('SODBUSER',"root");
define('SODBPASS',"");
define('SODBNAME',"multiclientcms");
// make a connection to mysql here
$connected = mySqlConnect(DBHOST, DBNAME, DBUSER, DBPASS);
if(!$connected){
    die("Sorry! There seems to be a problem connecting to our database.");
}

// define site path
define('DIR','multiclientcms.dev');

define('HOST', $host);
define('SOHOST','http://multiclientcms.dev');

require_once($_SERVER['DOCUMENT_ROOT']."/tools/misc.php");
?>