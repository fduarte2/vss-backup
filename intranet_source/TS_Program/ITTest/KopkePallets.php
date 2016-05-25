<?php
/* Created April 9, 2008 by Inigo Thomas
*  
*  This is an example of the phpgrid used to bring up a single table
*  
*******************************************************************/

  // include this file to use phpgrid
include("include/phpgrid.php");

$hostName = "172.22.15.238:1521";
$userName = "sag_owner";
$password = "rftest238";
$dbName  = "RFTEST";

?>
<html>
<head>
<title>phpGrid - Example 2k</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
$dg = new C_DataGrid($hostName, $userName, $password, $dbName, "oracle");
 
$dg -> set_gridpath     ("include/");
$dg -> set_sql          ("SELECT ARRIVAL_NUM, FUMIGATION_CODE, PALLET_ID FROM CARGO_TRACKING WHERE RECEIVER_ID = '1131'");
$dg -> set_sql_table    ("CARGO_TRACKING");
$dg -> set_sql_key      ("ARRIVAL_NUM");

//$dg -> set_sql          ("SELECT * FROM DC_ORDER");
//$dg -> set_sql_table    ("DC_ORDER");
//$dg -> set_sql_key      ("ORDERNUM");

// make the datagrid editable
//$dg -> set_allow_actions(true);

// set data administrative level
// "V" means View, "E" means Edit, and "D" means Delete.
$dg -> set_action_type ("V");   // view and edit only

// turn on inline-editing with Ajax enabled
//$dg -> set_inlineedit_enabled(true, true);

$dg -> set_page_size(100);
$dg -> set_theme("bone");
$dg -> display();
?>

</body>
</html>
