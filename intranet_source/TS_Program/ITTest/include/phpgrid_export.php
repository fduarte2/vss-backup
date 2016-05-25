<?php
include_once("cls_db.php");
include_once("cls_datagrid.php");
include_once("cls_arraygrid.php");
require("../adodb/adodb.inc.php");

if(!session_id()){ session_start();} // this is nessecory for PHP that running on Windows

if($_SESSION["DATASOURCE"] == "array"){
	
//	$alt_bgcolor	= isset($_GET["alt_bgcolor"]) ? $_GET["alt_bgcolor"] : "white, white";
	header("Cache-control: private");
	header("Content-Type: application/octet-stream");
	header("Content-disposition:  attachment; filename=phpGrid_Export_" .date("Y-m-d").".xls");
	header("Pragma: public");
	$uid = $_GET["_uid_"];
	// obtain datagrid object via session and reset some properties before export
//	$dg = new C_ArrayGrid($_SESSION[$uid]);
	$dg = unserialize($_SESSION["obj_". $uid]);
	$dg -> set_allow_actions(false);
	$dg -> set_multidel_enabled(false);
	$dg -> set_page_size(9999);
	$dg -> set_ok_rowindex(false);

	$dg -> set_sorted_by	("order_id", "DESC");
	$dg -> set_alt_bgcolor	("white, lightcyan");
	$dg -> set_gridpath("include/");
	// print customized header on top of everythign else
	print(isset($_SESSION["exp_header"]) ? $_SESSION["exp_header"] : "");		
	print("<table border='1'>");
	print($dg -> render_header());
	print($dg -> render_body());
	print("</table>");
	// print customized footer on top of everything else
	print(isset($_SESSION["exp_header"]) ? $_SESSION["exp_header"] : "");
	$dg = NULL;
	
}else{

	$hostName 		= isset($_SESSION["hostName"]) ? $_SESSION["hostName"] : ""; 
	$userName 		= isset($_SESSION["userName"]) ? $_SESSION["userName"] : ""; 
	$password 		= isset($_SESSION["password"]) ? $_SESSION["password"] : ""; 
	$databaseName 	= isset($_SESSION["databaseName"]) ? $_SESSION["databaseName"] : "";  
	$dbType		 	= isset($_SESSION["dbType"]) ? $_SESSION["dbType"] : "";
	$query 			= isset($_SESSION["sql"]) ? $_SESSION["sql"] : ""; 
//	$alt_bgcolor	= isset($_GET["alt_bgcolor"]) ? $_GET["alt_bgcolor"] : "white, white";
	header("Cache-control: private");
	header("Content-Type: application/octet-stream");
	header("Content-disposition:  attachment; filename=". $databaseName ."_" .date("Y-m-d").".xls");
	header("Pragma: public");
	$dg = new C_DataGrid($hostName, $userName, $password, $databaseName, $dbType);
	$dg -> set_sql			($query);
	$dg -> set_allow_sort	(false);
	$dg -> set_alt_bgcolor	("white, lightcyan");
	print("<table border='1'>");
	print($dg -> render_header());
	print($dg -> render_body());
	print("</table>");
	$dg = NULL;
}
?>


