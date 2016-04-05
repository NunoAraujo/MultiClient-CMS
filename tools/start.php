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

define('DBHOST',"localhost");
define('DBUSER',"root");
define('DBPASS',"");
define('DBNAME',"multiclientcms");
define('FTPROOT',"");
define('FTPHOST',"localhost");
define('FTPUSER',"");
define('FTPPASS',"");
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