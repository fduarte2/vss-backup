<?php
include_once("cls_db.php");
include_once("cls_datagrid.php");
require("../adodb/adodb.inc.php");

$master_foreign_id = 0;
$home_url = "";
$orderby = "";
$direction = "";
$pg = "";

if(!session_id()){ session_start();}
	
	$master_foreign_id = $_GET["master_foreign_id"];
	$_SESSION["master_foreign_id"] = $master_foreign_id;

	$home_url = $_GET["home_url"];	
	$orderby = ($_GET["orderby"] != "") ? "orderby=". $_GET["orderby"] : "";	
	$direction = ($_GET["direction"] != "") ? "direction=". $_GET["direction"] : "";	
	$pg = ($_GET["pg"] != "") ? "pg=". $_GET["pg"] : "";	
	$master_row_index = ($_GET["master_row_index"] != "") ? "master_row_index=". $_GET["master_row_index"] : "";	


?>
retrieving selected master detail records...
<script language="JavaScript" type="text/javascript">
{
location.replace("<?php print("$home_url?". $orderby ."&". $direction ."&". $pg ."&". $master_row_index); ?>");
}
</script>
