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
}


?>