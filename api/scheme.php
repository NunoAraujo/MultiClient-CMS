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
}


?>