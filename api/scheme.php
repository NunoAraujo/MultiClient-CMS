<?php

/**
* 
*/
class scheme
{
	public $id;
	public $name;
	public $client;
	public $tableName;
	public $listFields;
	public $maxRecords;
	public $sortable;
	public $editFields;
	public $groupBy;
	public $filterBy;
	public $hide;
	public $imageSettings;
	public $listThumbnail;
	public $subScheme;
	public $isLoaded;

	function __construct($schemeId) {
		$this->id = $schemeId;
		$this->isLoaded = true;
		$this->loadData($this->id);
	}

	function loadData($id) {
		mySqlConnectSO();
		$scheme = mySqlSelectSingle("schemes", "*", "id=$id");
		if ($scheme) {
			foreach ($scheme as $key => $value) {
				$this->$key = $value;
			}
		} else {
			$this->isLoaded = false;
		}
		$this->subScheme = $this->subScheme != "" ? new Scheme($this->subScheme) : false;  
		mySqlConnectClient();
	}

	function getListItems($groupByValue = false){
		$result = false;
		if ($groupByValue) {
			if ($this->sortable) {
				$items = mySqlSelect($this->tableName, "*", $this->groupBy."=$groupByValue", false, "user-defined-order");
			} else {
				$items = mySqlSelect($this->tableName, "*", $this->groupBy."=$groupByValue");
			}
		} else if($this->sortable) {
			$items = mySqlSelect($this->tableName, "*", false, false, "user-defined-order");
		} else {
			$items = mySqlSelect($this->tableName, "*");
		}
		if ($items) foreach ($items as $i) {
			$result = array();
			foreach ($items as $i) {
				$count = 0;
				$fieldsValueArray = array();
				foreach ($i as $key => $value) {
					if ($count <= $this->listFields) {
						$fieldsValueArray[$key] = $value;
					}
					$count++;
				}
				$result[] = $fieldsValueArray;
			}
		}
		return $result;
	}

	function getListItemsHeaders(){
		$fields = mySqlGetFields($this->tableName);
		$result = array();
		if ($fields) { 
			unset($fields[0]);
			for ($i=1; $i <= $this->listFields; $i++) { 
				$result[] = $fields[$i]['name'];
			}
		}
		return $result;
	}

	function getListGroupItems(){
		$groupBy = $this->groupBy;
		$schemeItems = MySqlSelect($this->tableName,"id, ".$groupBy, "", $groupBy);
		if ($this->isSchemeForeignKey($groupBy)) {
			foreach ($schemeItems as $count => $item) {
				foreach ($item as $key => $value) {
					if ($key == $groupBy) {
						$schemeItems[$count][$key] = $this->getSchemeForeignKeyValue($key, $value);
					}
				}
			}
		}
		
		return $schemeItems;
	}

	function getItemTypes() {
		$fields = mySqlGetFields($this->tableName);
		$result = array();
		if ($fields) { 
			for ($i=0; $i <= $this->editFields; $i++) { 
				$result[] = $fields[$i]['type'];
			}
		}
		return $result;
	}

	function getItemFieldsNames() {
		$fields = mySqlGetFields($this->tableName);
		$result = false;
		if ($fields) { 
			$result = array();
			for ($i=0; $i <= $this->editFields; $i++) { 
				$result[] = $fields[$i]['name'];
			}
		}
		return $result;
	}

	function getEditItems($schemeItemId){
		$items = mySqlSelectSingle($this->tableName, "*", "id=$schemeItemId");
		$result = false;
		if ($items) {
			$result = array();
			$count = 0;
			foreach ($items as $key => $value) {
				if ($count <= $this->editFields) {
					$result[$key] = $value;
				}
				$count++;
			}
		}
		return $result;
	}

	function getSchemeForeignKeys() {
		return mySqlGetForeignKeys($this->tableName);
	}

	function isSchemeForeignKey($field) {
		$result = mySqlGetForeignKeys($this->tableName);
		$isFK = false;
		if (!empty($result)) {
			foreach ($result as $r) {
				if ($r['key'] == $field) {
					$isFK = true;
				}
			}
		}
		return $isFK;
	}

	function isSchemeFieldNullable($field) {
		return mySqlIsFieldNullable($this->tableName, $field);
	}

	function getSchemeForeignKeyValue($key, $value) {
		$result = false;
		$schemeForeignKeys = $this->getSchemeForeignKeys();
		$table = "";
		$field = "";
		if ($schemeForeignKeys && !empty($schemeForeignKeys)) foreach ($schemeForeignKeys as $sfki => $sfk) {
			if ($sfk['key'] == $key) {
				$table = $sfk['table'];
				$field = $sfk['field'];
			}
		}
		$result = mySqlSelectSingle($table, "*", "$field=$value");
		return isset($result['title'])? $result['title'] : $result['name'];
	}
}


?>