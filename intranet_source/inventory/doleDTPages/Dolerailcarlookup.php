<?php
/* Created September 11, 2008 by Inigo Thomas
*  
*  Goal is to allow Inventory to Lookup Dole Railcars
*  
*******************************************************************/

  // include this file to use phpgrid
include("../../functions/phpgrid/include/phpgrid.php");
/*
$hostName = "172.22.15.238:1521";
$userName = "TCONSTANT";
$password = "TCONSTANT";
$dbName  = "RFTEST";
*/
$hostName = "172.22.15.204:1521";
$userName = "SAG_OWNER";
$password = "OWNER";
$dbName  = "RF";
$railcar  = $HTTP_POST_VARS['railcar'];

?>
<html>
<head>
<title>Dole Dock Ticket Paper Lookup</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
$dg = new C_DataGrid($hostName, $userName, $password, $dbName, "oracle");
if($railcar != ""){
	$more_where = " AND ARRIVAL_NUM = '".$railcar."' ";
} else {
	$more_where = "";
}

$dg -> set_gridpath     ("../../functions/phpgrid/include/");

$dg -> set_sql          ("SELECT DISTINCT ARRIVAL_NUM, BOL DT FROM CARGO_TRACKING WHERE REMARK = 'DOLEPAPERSYSTEM' ".$more_where." AND DATE_RECEIVED >= SYSDATE - 365 ORDER BY ARRIVAL_NUM, BOL");
$dg -> set_sql_table    ("CT");
$dg -> set_sql_key      ("DT");


// create master/detail tables
$dg -> set_masterdetail("SELECT ARRIVAL_NUM, BOL DT, BATCH_ID CODE, PALLET_ID, CARGO_DESCRIPTION, COMMODITY_CODE, DATE_RECEIVED, WEIGHT, MARK BASIS_WEIGHT, QTY_IN_HOUSE, QTY_DAMAGED FROM CARGO_TRACKING","BOL"," order by ARRIVAL_NUM, BOL, PALLET_ID"); 

// make the datagrid editable
//$dg -> set_allow_actions(true);

// set data administrative level
// "V" means View, "E" means Edit, and "D" means Delete.
// $dg -> set_action_type ("VE");   // view and edit only

// turn on inline-editing with Ajax enabled
//$dg -> set_inlineedit_enabled(true, true);

$dg -> set_page_size(30);
$dg -> set_theme("bone");
$dg -> display();
?>
</body>
</html>
