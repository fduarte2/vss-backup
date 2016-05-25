<?php
/* Created September 11, 2008 by Inigo Thomas
*  
*  Goal is to allow Inventory to Lookup Dole Railcars
*  
*******************************************************************/

  // include this file to use phpgrid
include("../../functions/phpgrid/include/phpgrid.php");

$hostName = "172.22.15.204:1521";
$userName = "sag_owner";
$password = "OWNER";
$dbName  = "RF";

?>
<html>
<head>
<title>Abitibi (312) Rail Car Lookup</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
$dg = new C_DataGrid($hostName, $userName, $password, $dbName, "oracle");
 
$dg -> set_gridpath     ("../../functions/phpgrid/include/");

$dg -> set_sql          ("SELECT DISTINCT CONTAINER_ID FROM CARGO_TRACKING WHERE RECEIVER_ID = '312' ORDER BY CONTAINER_ID");
$dg -> set_sql_table    ("CT");
$dg -> set_sql_key      ("CONTAINER_ID");


// create master/detail tables
$dg -> set_masterdetail("SELECT CONTAINER_ID, BATCH_ID, PALLET_ID, CARGO_DESCRIPTION, COMMODITY_CODE,  DATE_RECEIVED, WEIGHT, MARK, REMARK, QTY_UNIT, QTY_DAMAGED FROM CARGO_TRACKING",                         "CONTAINER_ID",                         " order by BATCH_ID, PALLET_ID"); 

// make the datagrid editable
//$dg -> set_allow_actions(true);

// set data administrative level
// "V" means View, "E" means Edit, and "D" means Delete.
// $dg -> set_action_type ("VE");   // view and edit only

// turn on inline-editing with Ajax enabled
//$dg -> set_inlineedit_enabled(true, true);

$dg -> set_page_size(20);
$dg -> set_theme("bone");
$dg -> display();
?>
</body>
</html>
