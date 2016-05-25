<?php
include_once("cls_db.php");
include_once("cls_datagrid.php");
include_once("cls_util.php");
require("../adodb/adodb.inc.php");

if(!session_id()){ session_start();}
// Fix incompatibility issuse with PHP5 when register_long_arrays = off in config
if (!isset($HTTP_POST_VARS) && isset($_POST)){ $HTTP_POST_VARS = $_POST;}

$util = new C_Utility();
if($_SESSION["DATASOURCE"] == "array"){
	
	$grid_data = isset($_SESSION["grid_data"]) ? $_SESSION["grid_data"] : array();
	foreach($HTTP_POST_VARS as $key => $value){
		if(strstr($key, "___")){
			array_splice($grid_data, $value, 1);
			$_SESSION["grid_data"] = $grid_data;
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
		}// else		
	}// for


}else{

	
	$hostName 		= isset($_SESSION["hostName"]) ? $_SESSION["hostName"] : ""; 
	$userName 		= isset($_SESSION["userName"]) ? $_SESSION["userName"] : ""; 
	$password 		= isset($_SESSION["password"]) ? $_SESSION["password"] : ""; 
	$databaseName 	= isset($_SESSION["databaseName"]) ? $_SESSION["databaseName"] : "";  
	$dbType		 	= isset($_SESSION["dbType"]) ? $_SESSION["dbType"] : "";
	$sql_key		= isset($_SESSION["sql_key"]) ? $_SESSION["sql_key"] : "";  
	$sql			= isset($_SESSION["sql"]) ? $_SESSION["sql"] : "";  
	$home_url		= "";
	$orderby		= "";
	$direction 		= "";
	$pg				= "";
	
	// echo $hostName ." ". $userName ." ". $password ." ". $databaseName;
	$db 			= new C_DataBase($hostName, $userName, $password, $databaseName, $dbType);
	$tbl_name		= $_SESSION["sql_table"]; 
	
	foreach($HTTP_POST_VARS as $key => $value){
		if(strstr($key, "___")){
			$keyset = split("___", $key);
			$name = $keyset[1];
			$pk	  = $value;
			// do not do anything if the name and key is blank
			if($name != "" && $pk != ""){
				$value= $util->add_slashes($value);
				$value = is_numeric($value) ? $value : "'".trim($value, "'")."'";
				$delete_sql ="DELETE FROM $tbl_name WHERE $sql_key = $value";
	//			echo "<br>". $delete_sql; 
				echo "<div style='font-size:8pt;color:lightgrey;font-family:arial;'>Double-checking against database schema... </div>";
				echo "<div style='font-size:8pt;color:lightgrey;font-family:arial;'>Making sure the data type is in correct format without any key constrains...</div><br>";

	  			$db->db_query($delete_sql);
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
	
}
?>
deleting selected records...
<script language="JavaScript" type="text/javascript">
{
location.replace("<?php print("$home_url?". $orderby ."&". $direction ."&". $pg); ?>");
}
</script>
