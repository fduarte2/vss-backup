<?php
include_once("cls_db.php");
include_once("cls_datagrid.php");
include_once("cls_arraygrid.php");
require("../adodb/adodb.inc.php");

if(!session_id()){ session_start();} // this is nessecory for PHP that running on Windows

if($_SESSION["DATASOURCE"] == "array"){
	
	// for later use...
	
}else{

	$hostName 		= isset($_SESSION["hostName"]) ? $_SESSION["hostName"] : ""; 
	$userName 		= isset($_SESSION["userName"]) ? $_SESSION["userName"] : ""; 
	$password 		= isset($_SESSION["password"]) ? $_SESSION["password"] : ""; 
	$databaseName 	= isset($_SESSION["databaseName"]) ? $_SESSION["databaseName"] : "";  
	$dbType		 	= isset($_SESSION["dbType"]) ? $_SESSION["dbType"] : "";
	$query 			= isset($_SESSION["sql"]) ? $_SESSION["sql"] : ""; 
	$tablename		= isset($_SESSION["sql_table"]) ? $_SESSION["sql_table"] : "record";	// table name as the first child of xml root node. 

	header("Cache-control: private");
	header("Content-Type: application/octet-stream");
	header("Content-disposition:  attachment; filename=phpGrid_XMLExport_" .date("Y-m-d").".xml");
	header("Pragma: public");
	$dg = new C_DataGrid($hostName, $userName, $password, $databaseName, $dbType);
	$dg -> set_sql			($query);
	$dg -> set_allow_sort	(false);
	print($dg->get_xml_export("root", $tablename, true));
	$dg = NULL;
}
?>


