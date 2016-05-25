<?php
// Any function name starts with underscore "_" indicates it has private scope to the class
if(!session_id()){ session_start();} // this is nessecory for PHP that running on Windows
class C_Database{
	var $hostName;
	var $userName;
	var $password;
	var $databaseName;
	var $tableName;
	var $link;
	var $dbType;
	var $db;
	
	function C_Database($host, $user, $pass, $dbName, $db_type = "mysql"){

		$this -> hostName = $host;
		$this -> userName = $user;
		$this -> password = $pass;
		$this -> databaseName = $dbName;
		$this -> dbType  = $db_type;
		
		$this -> _db_connect();
		$this -> _select_db();
		
		$_SESSION["hostName"] 		= $host;
		$_SESSION["userName"]	 	= $user;
		$_SESSION["password"] 		= $pass;
		$_SESSION["databaseName"] 	= $dbName;
		$_SESSION["dbType"] 		= $db_type;
		
	}
	
	function _destructor(){
	}
	
	/*
	************ Most likely you will be working with this function in database layer class *************
	*                                                                                                   *
	*  Connect to the Database                                                                          *
	*  Go to http://phplens.com/lens/adodb/docs-adodb.htm#connect_ex for database connection reference  *
	*  to other types of databases and simply modify/add to the Switch statement.                       *
	*                                                                                                   *
	*  There are multiple ways to connect Oracle db                                                     *
	*  see "http://phplens.com/adodb/code.initialization.html#oci8"                                     *
	*  and change it properly to suit your needs                                                        *
	*                                                                                                   *
	*****************************************************************************************************
	*/
	function _db_connect(){
		switch($this->dbType){
			case "access":
				$this->db =& ADONewConnection($this->dbType);
				$dsn = "Driver={Microsoft Access Driver (*.mdb)};Dbq=".$this->databaseName.";Uid=".$this->userName.";Pwd=".$this->password.";";
				$this->db->Connect($dsn);
				break;
			case "odbc_mssql":
				$this->db =& ADONewConnection($this->dbType);
				$dsn = "Driver={SQL Server};Server=".$this->hostName.";Database=".$this->databaseName.";";
				$this->db->Connect($dsn, $this->userName, $this->password);
				break;
			case "postgres":
				$this->db = &ADONewConnection($this->dbType);
				$this->db->PConnect($this->hostName, $this->userName, $this->password, $this->databaseName) or die("Could not connect to the database");
				break;
			case "db2":
				$this->db =& ADONewConnection($this->dbType);
				$dsn = "driver={IBM db2 odbc DRIVER};Database=".$this->databaseName.";hostname=".$this->hostName.";port=50000;protocol=TCPIP;uid=".$this->userName."; pwd=".$this->password;
				$this->db->Connect($dsn);
				break;
			case "ibase":
				$this->db = &ADONewConnection($this->dbType); 
				$this->db->PConnect($this->hostName . $this->databaseName, $this->userName, $this->password);
				break;
			case "oci8":
				// PHP and Oracle reside on the same machine, use default SID
				$this->db->Connect(false, $this->userName, $this->password);
				
				// TNS Name defined in tnsnames.ora (or ONAMES or HOSTNAMES), eg. 'myTNS'
				// $this->db->PConnect(false, $this->userName, $this->password, 'myTNS');
				
				// Host Address and SID
				// $this->db->connectSID = true;
				// $this->db->Connect('192.168.0.1', $this->userName, $this->password, 'SID');
				
				// Host Address and Service Name
				// $this->db->Connect('192.168.0.1', $this->userName, $this->password, 'servicename');
				break;
			case "sqlit":
				// $this->db = &ADONewConnection('sqlite');
				// $this->db->PConnect('c:\path\to\sqlite.db'); # sqlite will create if does not exist
				break;
			// default should be mysql and all other databases using the following form of connection
			default:	
				$this->db = &ADONewConnection($this->dbType);		
				$this->db->PConnect($this->hostName, $this->userName, $this->password, $this->databaseName) or die("Could not connect to the database");
		}			
	}
	
	// Desc: Select the Database - not used
	function _select_db(){
	}
	
	// Desc: query database
	function db_query($query_str){
		$this->db->SetFetchMode(ADODB_FETCH_BOTH);
		$result = $this->db->Execute($query_str) or die("Could not execute query $query_str in db_query()");
		return $result;
	}
	
	function select_limit($query_str, $size, $starting_row){
		$result = $this->db->SelectLimit($query_str, $size, $starting_row) or die("Could not execute query $query_str in select_limit()");
		return $result;
	}
	
	// Desc: helper function to get array from select_limit function
	function select_limit_array($query_str, $size, $starting_row){
		$result = $this->select_limit($query_str, $size, $starting_row);
		$resultArray = $result->GetArray();
		return $resultArray;
	}
	
	// Desc: fetch record from database as row
	// Note: the parameter is passed as reference
	function fetch_row(&$result){
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if(!$result->EOF){
		 	$rs = $result->fields;
		 	$result->MoveNext();
		 	return $rs;
		}
	}
	
	// Desc: fetch record from database as array
	// Note: the parameter is passed as reference
	function fetch_array(&$result){
		$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
		if(!$result->EOF){
		 	$rs = $result->fields;
		 	$result->MoveNext();
		 	return $rs;
		}  
	}
	
	// Desc: fetch record from database as associative array
	// Note: the parameter is passed as reference
	function fetch_array_assoc(&$result){
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		if(!$result->EOF){
		 	$rs = $result->fields;
		 	$result->MoveNext();
		 	return $rs;
		}
	}	
	
	// Desc: helper function. query first then fetch record from database as row
	function query_then_fetch_row($query_str){
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		$result = $this->db->Execute($query_str) or die("Could not execute query $query_str");
		if(!$result->EOF){
		 	$rs = $result->fields;
		 	$result->MoveNext();
		 	return $rs;
		}
	}
	
	// Desc: helper function. query first fetch record from database as associative array
	function query_then_fetch_array($query_str){
		$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
		$result = $this->db->Execute($query_str) or die("Could not execute query $query_str");
		if(!$result->EOF){
		 	$rs = $result->fields;
		 	$result->MoveNext();
		 	return $rs;
		}
	}
	
	// Desc: number of rows query returned
	function num_rows($result){
		return $result->RecordCount();
	} 
	
	// Desc: number of data fields in the recordset
	function num_fields($result){
		return $result->FieldCount();
	}
	
	// Desc: a specific field name (column name) with that index in the recordset
	function field_name($result, $index){
		$obj_field = new ADOFieldObject();
		$obj_field = $result->FetchField($index);
		return isset($obj_field->name) ? $obj_field->name : "";
	}

	// Desc: the type of a specific field name (column name) with that index in the recordset
	function field_type($result, $index){
		$obj_field = new ADOFieldObject();
		$obj_field = $result->FetchField($index);
		return isset($obj_field->type) ? $obj_field->type : "";
	}
	
	// Desc: the length of a speciifc field name (column name) with that index in the recordset
	function field_len($result, $index){
		$obj_field = new ADOFieldObject();
		$obj_field = $result->FetchField($index);
		return isset($obj_field->max_length) ? $obj_field->max_length : "";
	}
	
	// **** field_table_by_querystr is deprecating ****
	//
	// Desc: obtain the table name of the current query
	// Basic a helper function to directly obtain table name from a query string
	// Note: only ONE single table is supported in phpGrid
	function field_table_by_querystr($query_str){
		$arr_sql1 = split(" FROM ", $query_str);	// get everything after FROM
		$after_sqlfrom = trim($arr_sql1[1]);
		$arr_sql2 = split(" ", $after_sqlfrom);		// get everything before WHERE
		$before_sqlwhere = trim($arr_sql2[0]);
		$arr_sql3 = split(",", $before_sqlwhere);	// get the first table before "," in case of multiple tables used.
													
		$this->tableName = $arr_sql3[0];
	}
}
?>