<?php
function mySqlConnect($servername, $database, $username, $password) {
	$result = true;
	// Create connection
	global $conn;
	$conn = new mysqli($servername, $username, $password, $database);

	// Check connection
	if ($conn->connect_error) {
	    //die("Connection failed: " . $conn->connect_error);
		$result = false;
	} 
	return $result;
}

function mySqlConnectSO() {
	return mySqlConnect(SODBHOST, SODBNAME, SODBUSER, SODBPASS);
}

function mysqlSetClient($clientId) {
	$dbHost = "localhost";
	$dbName =  "asquaredoffice";
	$dbUser = "root";
	$dbPass = "";
	$ftpRoot = "";
	$ftpHost = "";
	$ftpUser = "";
	$ftpPass = "";
	$host = "http://asquaredoffice.dev";

	$result = MySqlSelectSingle("clients", "*", "id=$clientId");
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
	mySqlConnectClient();
}

function mySqlConnectClient() {
	return mySqlConnect($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
}

function mySqlQuery($sql) {
	global $conn;
	$result = $conn->query($sql);
	if ($result) {
		if (isset($result->num_rows) && $result->num_rows == 1) {
			$result = $result->fetch_assoc();
		} else if (isset($result->num_rows) && $result->num_rows > 1) {
			$aux = array();
			while ($row = $result->fetch_assoc()) {
				$aux[] = $row;
			}
			$result = $aux;
		}
	}
	return $result;
}

function mySqlRaw($sql) {
	global $conn;
	$result = $conn->query($sql);
	if ($result) {
		$aux = array();
		while ($row = $result->fetch_assoc()) {
			$aux[] = $row;
		}
		$result = $aux;
	}
	return $result;
}

function mySqlGetFields($table){
	global $conn;
	$sql = "SELECT * FROM $table";
	$result = $conn->query($sql);
	if ($result) {
		$aux = array();
		foreach ($result->fetch_fields() as $key => $value) {
			$aux2 = array();
			foreach ($value as $k => $v) {
				$aux2[$k] = $v;
			}
			$aux[] = $aux2;
		}
		$result = $aux;
	}
	return $result;
}

function mySqlSelectSingle($table, $fields = "*", $filters = false, $groupBy = false) {
	$result = mySqlSelect($table, $fields, $filters, $groupBy);
	$result = isset($result[0]) ? $result[0] : false;
	$result = $result && count($result) == 1 ? $result[array_keys($result)[0]] : $result;
	return	$result;
}

function mySqlSelect($table, $fields = "*", $filters = false, $groupBy = false, $orderBy = false) {
	global $conn;
	if($orderBy == "user-defined-order" && !$groupBy) {
		$sql = "SELECT $table.$fields FROM $table, _assortment";
		if ($filters) {
			$newFilters = "";
			$count = 1;
			$filters = explode(" AND ", $filters);
			foreach ($filters as $f) {
				if (count($filters) == 1 || count($filters) == $count) {
					$newFilters .= "$table.$f";
				} else {
					$newFilters .= "$table.$f AND ";
				}
				$count++;
			}
			$sql .= " WHERE $newFilters AND $table.id=_assortment.itemId AND _assortment.tableName='$table'";
		} else {
			$sql .= " WHERE $table.id=_assortment.itemId AND _assortment.tableName='$table'";
		}
		if ($orderBy) {
			$sql .= " ORDER BY _assortment.position";
		}
		$result = $conn->query($sql);
		if ($result) {
			$aux = array();
			while ($row = $result->fetch_assoc()) {
				$aux[] = $row;
			}
			$result = $aux;
		}
	} else {
		$sql = "SELECT $fields FROM $table";
		if ($filters) {
			$sql .= " WHERE $filters";
		}
		if ($orderBy) {
			$sql .= " ORDER BY $orderBy";
		}
		if ($groupBy) {
			$sql .= " GROUP BY $groupBy";
		}
		$result = $conn->query($sql);
		if ($result) {
			$aux = array();
			while ($row = $result->fetch_assoc()) {
				$aux[] = $row;
			}
			$result = $aux;
		}
	}
	return $result;
}

function mySqlInsert($table, $fields = "", $values = "") {
	global $conn;
	$sql = "INSERT INTO $table($fields) VALUES ($values)";
	$result = $conn->query($sql);
	if ($result) $result = $conn->insert_id;
	return $result;
}

function mySqlUpdate($table, $fields, $filters) {
	global $conn;
	$sql = "UPDATE $table SET $fields WHERE $filters";	
	$result = $conn->query($sql);
	return $result;
}

function mySqlDelete($table, $filter = "") {
	global $conn;
	$sql = "DELETE FROM $table WHERE $filter";
	$result = $conn->query($sql);
	return $result;
}

function mySqlGetForeignKeys($table, $field = false) {
	$sql = "";
	if ($field) {
		$sql = "SELECT column_name AS 'key', referenced_table_name AS 'table', referenced_column_name AS 'field' FROM information_schema.key_column_usage WHERE referenced_table_name IS NOT NULL AND table_schema = DATABASE() AND table_name = '$table' AND column_name = '$field'";
	} else {
		$sql = "SELECT column_name AS 'key', referenced_table_name AS 'table', referenced_column_name AS 'field' FROM information_schema.key_column_usage WHERE referenced_table_name IS NOT NULL AND table_schema = DATABASE() AND table_name = '$table'";
	}
	$result = mySqlRaw($sql);
	return $result;
}
function mySqlIsFieldNullable($table, $field) {
	$sql = "SELECT * FROM information_schema.columns WHERE table_schema = '".$GLOBALS['DBNAME']."' AND table_name = '$table' AND column_name = '$field' AND is_nullable = 'YES'";
	$result = mySqlRaw($sql);
	return empty($result) ? false : true;
}
?>