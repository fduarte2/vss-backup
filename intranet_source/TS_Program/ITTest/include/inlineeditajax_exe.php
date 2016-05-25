<?php
include_once("cls_db.php");
include_once("cls_datagrid.php");
include_once("cls_arraygrid.php");
include_once("cls_util.php");
require("../adodb/adodb.inc.php");

if(!session_id()){ session_start();}
// Fix incompatibility issuse with PHP5 when register_long_arrays = off in config
if (!isset($HTTP_POST_VARS) && isset($_POST)){ $HTTP_POST_VARS = $_POST;}

$hostName 		= isset($_SESSION["hostName"]) ? $_SESSION["hostName"] : ""; 
$userName 		= isset($_SESSION["userName"]) ? $_SESSION["userName"] : ""; 
$password 		= isset($_SESSION["password"]) ? $_SESSION["password"] : ""; 
$databaseName 	= isset($_SESSION["databaseName"]) ? $_SESSION["databaseName"] : "";  
$dbType		 	= isset($_SESSION["dbType"]) ? $_SESSION["dbType"] : "";
$sql_key		= isset($_SESSION["sql_key"]) ? $_SESSION["sql_key"] : "";  
$sql			= isset($_SESSION["sql"]) ? $_SESSION["sql"] : "";  
$tbl_name		= isset($_SESSION["sql_table"]) ? $_SESSION["sql_table"] : "";
$ctrlname		= $_GET["ctrlname"];
$ctrlval		= $_GET["ctrlval"];

$db 			= new C_DataBase($hostName, $userName, $password, $databaseName, $dbType);
$util			= new C_Utility();
$keyset = split("___", $ctrlname);
$name = $keyset[1];
$pk	  = $keyset[2];

// do not do anything if the name and key is blank
if($name != "" && $pk != ""){
	$update_sql ="UPDATE $tbl_name SET $name = '". $util->add_slashes($ctrlval) ."' WHERE $sql_key = '". $util->add_slashes($pk). "'";
//		echo $update_sql ."<br>";
	$result = $db->db_query($update_sql);
	if($result == false){
		echo "false";
	}
}
?>
