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
$home_url		= "";
$orderby		= "";
$sort_direction = "";
$pg				= "";

// echo $hostName ." ". $userName ." ". $password ." ". $databaseName;
$db 			= new C_DataBase($hostName, $userName, $password, $databaseName, $dbType);
$util			= new C_Utility();
$tbl_name		= $_SESSION["sql_table"]; 

foreach($HTTP_POST_VARS as $key => $value){
	if(strstr($key, "___")){
		$keyset = split("___", $key);
		$name = $keyset[1];
		$pk	  = $keyset[2];
		// do not do anything if the name and key is blank
		if($name != "" && $pk != ""){
			$update_sql ="UPDATE $tbl_name SET $name = '". $util->add_slashes($value) ."' WHERE $sql_key = '". $util->add_slashes($pk). "'";
//			echo $update_sql ."<br>";
			$db->db_query($update_sql);
		}
	}
	else{
		$value = trim($value);
		switch($key){
			case "home_url":
				$home_url = $value;	
				break;
			case "orderby":
				$orderby = ($value != "") ? "orderby=$value" : "";	
				break;
			case "direction":
				$direction = ($value != "") ? "direction=$value" : "";	
				break;
			case "pg":
				$pg = ($value != "") ? "pg=$value" : "";	
				break;
		}
	}
	
}
// header("Location: $home_url?". $orderby ."&". $direction ."&". $pg);
?>
Saving...
<script language="JavaScript" type="text/javascript">
{
	location.replace("<?php print("$home_url?". $orderby ."&". $direction ."&". $pg); ?>");
}
</script>

